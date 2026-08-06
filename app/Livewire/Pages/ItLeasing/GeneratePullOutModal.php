<?php

namespace App\Livewire\Pages\ItLeasing;

use App\Models\ItLeasing;
use App\Models\PullOutForm;
use Livewire\Component;

class GeneratePullOutModal extends Component
{
    public ItLeasing $itLeasing;

    public string $form_date;
    public string $type = 'laptop';
    public string $type_other = '';

    public string $brand = '';
    public string $serial_no = '';
    public string $inclusion = '';
    public string $employee_name = '';

    // Multiple reasons allowed now (e.g. "return" + "overissuance" together)
    public array $reasons = [];
    public string $reason_other = '';

    public string $condition_details = '';

    public bool $has_replacement = false;

    public string $replacement_brand = '';
    public string $replacement_serial_no = '';

    public string $issued_by_name = '';
    public string $issued_by_company = 'ACJ SUMMIT VENTURES CORP.';

    public string $received_by_name = '';
    public string $received_by_company = 'BTSMC MANAGING SOLUTIONS INC.';

    public string $returned_by_name = '';
    public string $returned_by_company = 'BTSMC MANAGING SOLUTIONS INC.';

    public string $return_received_by_name = '';
    public string $return_received_by_company = 'ACJ SUMMIT VENTURES CORP.';

    public function mount(ItLeasing $itLeasing)
    {
        $this->itLeasing = $itLeasing;
        $this->form_date = now()->format('Y-m-d');
        $this->brand = trim($itLeasing->brand.' '.$itLeasing->model);
        $this->serial_no = $itLeasing->serial_number ?? '';
        $this->inclusion = is_array($itLeasing->inclusions)
            ? implode(', ', $itLeasing->inclusions)
            : '';
        $this->employee_name = $itLeasing->assigned_employee ?? '';
    }

    protected function rules(): array
    {
        return [
            'form_date' => ['required', 'date'],
            'type' => ['required', 'in:laptop,printer,others'],
            'type_other' => ['nullable', 'required_if:type,others', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'serial_no' => ['nullable', 'string', 'max:255'],
            'inclusion' => ['nullable', 'string', 'max:255'],
            'employee_name' => ['nullable', 'string', 'max:255'],
            'reasons' => ['required', 'array', 'min:1'],
            'reasons.*' => ['in:assessment,return,overissuance,incompatible_specs,others'],
            'reason_other' => ['nullable', 'required_if:reasons.*,others', 'string', 'max:255'],
            'condition_details' => ['nullable', 'string'],
            'has_replacement' => ['boolean'],
            'replacement_brand' => ['nullable', 'required_if:has_replacement,true', 'string', 'max:255'],
            'replacement_serial_no' => ['nullable', 'required_if:has_replacement,true', 'string', 'max:255'],
            'issued_by_name' => ['nullable', 'string', 'max:255'],
            'issued_by_company' => ['nullable', 'string', 'max:255'],
            'received_by_name' => ['nullable', 'string', 'max:255'],
            'received_by_company' => ['nullable', 'string', 'max:255'],
            'returned_by_name' => ['nullable', 'string', 'max:255'],
            'returned_by_company' => ['nullable', 'string', 'max:255'],
            'return_received_by_name' => ['nullable', 'string', 'max:255'],
            'return_received_by_company' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function generate()
    {
        $data = $this->validate();
        $data['it_leasing_id'] = $this->itLeasing->id;

        if (! $this->has_replacement) {
            $data['replacement_brand'] = 'N/A';
            $data['replacement_serial_no'] = 'N/A';
            $data['issued_by_name'] = null;
            $data['received_by_name'] = null;
        }

        $pullOutForm = PullOutForm::create($data);

        $this->dispatch('pull-out-form-generated', url: route('it-leasing.pull-out.generate', $pullOutForm));

        $this->dispatch('close-pull-out-modal');
    }

    public function render()
    {
        return view('livewire.pages.it-leasing.generate-pull-out-modal');
    }
}
