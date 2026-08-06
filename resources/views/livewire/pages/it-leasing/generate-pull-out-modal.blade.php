<div
    x-data
    x-on:pull-out-form-generated.window="window.open($event.detail.url, '_blank')"
    x-on:close-pull-out-modal.window="Flux.modal('pull-out-form').close()"
>
    <flux:modal name="pull-out-form" class="md:w-[640px]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Pull Out / Replacement Form</flux:heading>
                <flux:text class="mt-1 text-neutral-500">{{ $itLeasing->item_name }}</flux:text>
            </div>

            <flux:input type="date" label="Date" wire:model="form_date" />

            <div>
                <flux:radio.group label="Pull Out Type" wire:model.live="type">
                    <flux:radio value="laptop" label="Laptop" />
                    <flux:radio value="printer" label="Printer" />
                    <flux:radio value="others" label="Others" />
                </flux:radio.group>
                @if ($type === 'others')
                    <flux:input class="mt-2" placeholder="Specify type" wire:model="type_other" />
                @endif
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <flux:input label="Brand" wire:model="brand" />
                <flux:input label="Serial No." wire:model="serial_no" />
                <flux:input label="Inclusion" wire:model="inclusion" />
                <flux:input label="Employee Name" wire:model="employee_name" />
            </div>

            <div>
                <flux:checkbox.group label="Reason for Pull Out" wire:model.live="reasons">
                    <flux:checkbox value="assessment" label="For assessment of issues/damage" />
                    <flux:checkbox value="return" label="For return" />
                    <flux:checkbox value="overissuance" label="Overissuance" />
                    <flux:checkbox value="incompatible_specs" label="Incompatible specs" />
                    <flux:checkbox value="others" label="Others" />
                </flux:checkbox.group>
                @if (in_array('others', $reasons))
                    <flux:input class="mt-2" placeholder="Specify reason" wire:model="reason_other" />
                @endif
            </div>

            <flux:textarea label="Condition of Unit Details" rows="3" wire:model="condition_details"
                placeholder="e.g. Good condition&#10;Good keyboard&#10;One paint peel on top of P2 key" />

            <flux:separator />

            {{-- Always visible: yung unit na binalik --}}
            <flux:heading size="sm">Return Signatories</flux:heading>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <flux:input label="Returned By" wire:model="returned_by_name" />
                <flux:input label="Company" wire:model="returned_by_company" />

                <flux:input label="Return Unit Received By" wire:model="return_received_by_name" />
                <flux:input label="Company" wire:model="return_received_by_company" />
            </div>

            <flux:separator />

            {{-- Toggle: lalabas lang Replacement fields kapag naka-check --}}
            <flux:checkbox wire:model.live="has_replacement" label="Client also received a replacement unit" />

            @if ($has_replacement)
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <flux:input label="Replacement Brand" wire:model="replacement_brand" />
                    <flux:input label="Replacement Serial No." wire:model="replacement_serial_no" />
                </div>

                <flux:heading size="sm">Replacement Signatories</flux:heading>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <flux:input label="Replacement Issued By" wire:model="issued_by_name" />
                    <flux:input label="Company" wire:model="issued_by_company" />

                    <flux:input label="Replacement Received By" wire:model="received_by_name" />
                    <flux:input label="Company" wire:model="received_by_company" />
                </div>
            @endif

            <div class="flex justify-end gap-2">
                <flux:button variant="ghost" onclick="Flux.modal('pull-out-form').close()">
                    Cancel
                </flux:button>
                <flux:button variant="primary" wire:click="generate" wire:loading.attr="disabled">
                    Generate PDF
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
