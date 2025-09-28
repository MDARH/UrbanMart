<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class Preorder extends Model
{
    use HasFactory,PreventDemoModeChanges;

    protected $guarded = [];

    // Mohammad Hassan - Cast date fields
    protected $casts = [
        'request_preorder_time' => 'datetime',
        'prepayment_confirm_time' => 'datetime',
        'final_order_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function preorder_product(){
        return $this->belongsTo(PreorderProduct::class,'product_id');
    }

    // Mohammad Hassan - Add relationship with regular products
    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
    
    public function address(){
        return $this->belongsTo(Address::class)->with(['country','state','city']);
    }

    public function shop()
    {
        return $this->hasOne(Shop::class, 'user_id', 'product_owner_id');
    }

    public function preorderCommissionHistory()
    {
        return $this->hasOne(PreorderCommissionHistory::class);
    }

    // Mohammad Hassan - Helper methods for preorder status
    public function getStatusLabel()
    {
        return match($this->status) {
            'pending' => translate('Pending'),
            'pending_payment' => translate('Pending Payment'),
            'confirmed' => translate('Confirmed'),
            'product_arrived' => translate('Product Arrived'),
            'completed' => translate('Completed'),
            'cancelled' => translate('Cancelled'),
            'out_of_stock_request' => translate('Out of Stock Request'),
            default => translate('Unknown')
        };
    }

    // Mohammad Hassan - Check if preorder can be marked as arrived
    public function canMarkAsArrived()
    {
        return in_array($this->status, ['confirmed', 'pending_payment']);
    }

    // Mohammad Hassan - Get remaining amount to be paid
    public function getRemainingAmount()
    {
        return $this->grand_total - $this->prepayment;
    }

    // Mohammad Hassan - Check if final payment is due
    public function isFinalPaymentDue()
    {
        return $this->status === 'product_arrived' && $this->getRemainingAmount() > 0;
    }
}

