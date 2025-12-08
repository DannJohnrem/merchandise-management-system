<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItLeasing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category',
        'serial_number',
        'brand',
        'model',
        'cost',
        'assigned_to',
        'class',
        'status',
        'remarks',
        // 'purchase_date',
        // 'purchase_order_no',
        // 'warranty_expiration',
    ];

    protected $casts = [
        'status' => 'string',
        'cost' => 'decimal:2',
    ];
}
