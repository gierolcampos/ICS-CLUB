<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Routing\Controller as BaseController;

class PaymentController extends BaseController
{
    /**
     * Display a listing of the payments.
     */
    public function index()
    {
        $query = Order::with('user'); // Eager load user relationship
        
        // Apply search filter
        if (request('search')) {
            $query->where(function($q) {
                $q->where('id', 'like', '%' . request('search') . '%')
                  ->orWhereHas('user', function($q) {
                      $q->where('name', 'like', '%' . request('search') . '%');
                  });
            });
        }
        
        // Apply payment method filter
        if (request('payment_method')) {
            $query->where('method', request('payment_method'));
        }
        
        // Get paginated payments
        $payments = $query->orderBy('id', 'desc')->paginate(10);
        
        // Calculate statistics
        $totalPayments = Order::where('payment_status', 'Paid')->sum('total_price');
        $thisMonthPayments = Order::where('payment_status', 'Paid')
            ->whereMonth('placed_on', Carbon::now()->month)
            ->whereYear('placed_on', Carbon::now()->year)
            ->sum('total_price');
        $outstandingPayments = Order::where('payment_status', 'Pending')->sum('total_price');

        return view('payments.index', compact('payments', 'totalPayments', 'thisMonthPayments', 'outstandingPayments'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create()
    {
        return view('payments.create');
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'total_price' => 'required|numeric|min:0',
                'payment_method' => 'required|string|in:CASH,GCASH',
                'payment_status' => 'required|string|in:Paid,Pending,Failed,Refunded',
                'description' => 'nullable|string',
                // GCASH specific fields
                'gcash_name' => 'required_if:payment_method,GCASH|string|nullable',
                'gcash_num' => 'required_if:payment_method,GCASH|string|nullable',
                'gcash_amount' => 'required_if:payment_method,GCASH|numeric|min:0|nullable',
                'reference_number' => 'required_if:payment_method,GCASH|string|nullable',
                // CASH specific fields
                'officer_in_charge' => 'required_if:payment_method,CASH|string|nullable',
                'receipt_control_number' => 'required_if:payment_method,CASH|string|nullable',
            ]);

            // Calculate change amount for GCash payments
            $changeAmount = 0;
            if ($request->payment_method === 'GCASH' && isset($validated['gcash_amount'], $validated['total_price'])) {
                $changeAmount = $validated['gcash_amount'] - $validated['total_price'];
            }

            // Get the authenticated user's ID or use a default value
            $userId = Auth::check() ? Auth::id() : 1; // Using 1 as default admin ID

            // Create the payment record
            $order = Order::create([
                'user_id' => $userId,
                'method' => $validated['payment_method'],
                'total_price' => $validated['total_price'],
                'description' => $validated['description'] ?? null,
                // GCash details
                'gcash_name' => $validated['gcash_name'] ?? null,
                'gcash_num' => $validated['gcash_num'] ?? null,
                'gcash_amount' => $validated['gcash_amount'] ?? null,
                'change_amount' => $changeAmount,
                'reference_number' => $validated['reference_number'] ?? null,
                // Cash details
                'officer_in_charge' => $validated['officer_in_charge'] ?? null,
                'receipt_control_number' => $validated['receipt_control_number'] ?? null,
                'placed_on' => now()->format('Y-m-d H:i:s'),
                'payment_status' => $validated['payment_status']
            ]);

            // Store the order ID in session for guest users
            if (!Auth::check()) {
                session(['last_order_id' => $order->id]);
            }

            // Redirect based on payment method
            if ($validated['payment_method'] === 'GCASH') {
                return redirect()->route('gcash.confirmation', ['order_id' => $order->id])
                    ->with('success', 'GCash payment recorded successfully.');
            }

            return redirect()->route('payments.index')
                ->with('success', 'Payment recorded successfully.');
        } catch (\Exception $e) {
            \Log::error('Payment recording failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to record payment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified payment.
     */
    public function show($id)
    {
        $payment = Order::findOrFail($id);
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit($id)
    {
        $payment = Order::findOrFail($id);
        return view('payments.edit', compact('payment'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $payment = Order::findOrFail($id);
            
            $validated = $request->validate([
                'total_price' => 'required|numeric|min:0',
                'payment_method' => 'required|string|in:CASH,GCASH',
                'payment_status' => 'required|string|in:Paid,Pending,Failed,Refunded',
                'description' => 'nullable|string',
                // GCASH specific fields
                'gcash_name' => 'required_if:payment_method,GCASH|string|nullable',
                'gcash_num' => 'required_if:payment_method,GCASH|string|nullable',
                'gcash_amount' => 'required_if:payment_method,GCASH|numeric|min:0|nullable',
                'reference_number' => 'required_if:payment_method,GCASH|string|nullable',
                // CASH specific fields
                'officer_in_charge' => 'required_if:payment_method,CASH|string|nullable',
                'receipt_control_number' => 'required_if:payment_method,CASH|string|nullable',
            ]);

            // Calculate change amount for GCash payments
            $changeAmount = 0;
            if ($request->payment_method === 'GCASH' && isset($validated['gcash_amount'], $validated['total_price'])) {
                $changeAmount = $validated['gcash_amount'] - $validated['total_price'];
            }

            $payment->update([
                'method' => $validated['payment_method'],
                'total_price' => $validated['total_price'],
                'description' => $validated['description'] ?? null,
                // GCash details
                'gcash_name' => $validated['gcash_name'] ?? null,
                'gcash_num' => $validated['gcash_num'] ?? null,
                'gcash_amount' => $validated['gcash_amount'] ?? null,
                'change_amount' => $changeAmount,
                'reference_number' => $validated['reference_number'] ?? null,
                // Cash details
                'officer_in_charge' => $validated['officer_in_charge'] ?? null,
                'receipt_control_number' => $validated['receipt_control_number'] ?? null,
                'payment_status' => $validated['payment_status']
            ]);

            return redirect()->route('payments.show', $payment->id)
                ->with('success', 'Payment updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Payment update failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update payment. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy($id)
    {
        try {
            $payment = Order::findOrFail($id);
            $payment->delete();

            return redirect()->route('payments.index')
                ->with('success', 'Payment deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Payment deletion failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete payment. Please try again.');
        }
    }
} 