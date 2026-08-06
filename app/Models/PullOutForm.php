<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PullOutForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'it_leasing_id',
        'form_date',
        'type',
        'type_other',
        'brand',
        'serial_no',
        'inclusion',
        'employee_name',
        'reasons',
        'reason_other',
        'condition_details',
        'has_replacement',
        'replacement_brand',
        'replacement_serial_no',
        'issued_by_name',
        'issued_by_company',
        'received_by_name',
        'received_by_company',
        'returned_by_name',
        'returned_by_company',
        'return_received_by_name',
        'return_received_by_company',
    ];

    protected $casts = [
        'form_date' => 'date:Y-m-d',
        'has_replacement' => 'boolean',
        'reasons' => 'array',
    ];

    public function itLeasing()
    {
        return $this->belongsTo(ItLeasing::class);
    }
}
