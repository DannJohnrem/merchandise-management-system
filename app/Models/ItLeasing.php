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
        'item_name',
        'serial_number',
        'brand',
        'model',
        'purchase_cost',
        'supplier',
        'purchase_order_no',
        'purchase_date',
        'warranty_expiration',
        'assigned_company',
        'assigned_employee',
        'location',
        'status',
        'condition',
        'remarks',
    ];

    protected $casts = [
        'purchase_cost' => 'decimal:2',
        'purchase_date' => 'date:Y-m-d',
        'warranty_expiration' => 'date:Y-m-d',
    ];
}
