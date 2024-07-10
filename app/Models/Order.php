<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_owner',
        'gsm',
        'amount',
        'order_id',
        'order_description',
        'installment',
        'total_amount',
        'security_type',
        'transaction_id',
        'ip_address',
        'currency_code',
    ];
}
