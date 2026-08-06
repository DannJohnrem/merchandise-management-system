<?php

namespace App\Http\Controllers;

use App\Models\PullOutForm;
use Barryvdh\DomPDF\Facade\Pdf;

class PullOutFormController extends Controller
{
    public function generate(PullOutForm $pullOutForm)
    {
        $pdf = Pdf::loadView('pdf.pull-out-form', [
            'form' => $pullOutForm,
        ])->setPaper('a4');

        return $pdf->stream("pull-out-form-{$pullOutForm->id}.pdf");
    }
}
