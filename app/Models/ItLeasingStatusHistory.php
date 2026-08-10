<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItLeasingStatusHistory extends Model
{
    protected $fillable = [
        'it_leasing_id',
        'pull_out_form_id',
        'changed_by',
        'from_status',
        'to_status',
        'remarks',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function itLeasing()
    {
        return $this->belongsTo(ItLeasing::class);
    }

    public function pullOutForm()
    {
        return $this->belongsTo(PullOutForm::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
