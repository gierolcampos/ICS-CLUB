<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class GcashController extends Controller
{
    /**
     * Display the Gcash payment confirmation page.
     */
    public function confirmation($order_id)
    {
        $order = null;
        
        if (Auth::check()) {
            // Get the specific GCASH order for authenticated user
            $order = Order::where('user_id', Auth::id())
                    ->where('method', 'GCASH')
                    ->where('id', $order_id)
                    ->first();
        } else if (session('last_order_id')) {
            // Get the order from session for guest users
            $order = Order::where('id', session('last_order_id'))
                    ->where('method', 'GCASH')
                    ->first();
        }

        // If no GCASH payment is found, redirect to payment page
        if (!$order) {
            return redirect()->route('gcash.payment');
        }

        return view('gcash.confirmation', compact('order'));
    }

    /**
     * Display the Gcash payment page.
     */
    public function payment()
    {
        return view('gcash.payment');
    }

    /**
     * Display the user's Gcash orders.
     */
    public function orders()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Login first to see your order history!');
        }

        $user_id = Auth::id();
        $orders = Order::where('user_id', $user_id)
                ->where('method', 'GCASH')
                ->orderBy('id', 'desc')
                ->get();

        return view('gcash.orders', compact('orders'));
    }

    /**
     * Display the customer GCASH receipt.
     */
    public function receipt()
    {
        $orders = collect();
        
        if (Auth::check()) {
            // Get all GCASH orders for authenticated user
            $orders = Order::where('user_id', Auth::id())
                    ->where('method', 'GCASH')
                    ->orderBy('id', 'desc')
                    ->get();
        } else if (session('last_order_id')) {
            // Get the order from session for guest users
            $order = Order::where('id', session('last_order_id'))
                    ->where('method', 'GCASH')
                    ->first();
            if ($order) {
                $orders = collect([$order]);
            }
        }
                    
        return view('payments.customer_gcash_receipt', compact('orders'));
    }
} 