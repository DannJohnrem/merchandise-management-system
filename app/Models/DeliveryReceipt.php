<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryReceipt extends Model
{
    protected $fillable = [
        'dr_number',
        'it_leasing_ids',
        'assigned_company',
        'assigned_employee',
        'generated_by',
    ];

    protected $casts = [
        'it_leasing_ids' => 'array',
    ];
}
