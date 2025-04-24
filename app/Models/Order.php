<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'number',
        'email',
        'method',
        'address',
        'total_products',
        'total_price',
        'gcash_name',
        'gcash_num',
        'gcash_amount',
        'change_amount',
        'reference_number',
        'placed_on',
        'payment_status',
        'officer_in_charge',
        'receipt_control_number'
    ];

    /**
     * Get the user that placed the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    /**
     * Get masked name for GCASH receipts.
     */
    public function getMaskedNameAttribute()
    {
        $gcashName = $this->gcash_name;
        
        if (!$gcashName) {
            return '';
        }
        
        $maskedName = '';
        $nameParts = explode(' ', $gcashName);
        
        foreach ($nameParts as $part) {
            if (strlen($part) > 2) {
                $maskedPart = substr($part, 0, 1) . str_repeat('*', strlen($part) - 2) . substr($part, -1);
            } else {
                $maskedPart = $part;
            }
            $maskedName .= $maskedPart . ' ';
        }

        return trim($maskedName);
    }
} 