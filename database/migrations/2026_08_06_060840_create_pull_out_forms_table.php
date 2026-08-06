<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pull_out_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('it_leasing_id')->nullable()->constrained()->nullOnDelete();

            $table->date('form_date');

            // Pull out type
            $table->enum('type', ['laptop', 'printer', 'others']);
            $table->string('type_other')->nullable();

            $table->string('brand')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('inclusion')->nullable();
            $table->string('employee_name')->nullable();

            // Reason for pull out
            $table->enum('reason', ['assessment', 'return', 'overissuance', 'incompatible_specs', 'others']);
            $table->string('reason_other')->nullable();

            $table->text('condition_details')->nullable();

            // Replacement issuance
            $table->string('replacement_brand')->default('N/A');
            $table->string('replacement_serial_no')->default('N/A');

            // Signatories
            $table->string('issued_by_name')->nullable();
            $table->string('issued_by_company')->default('ACJ SUMMIT VENTURES CORP.');

            $table->string('received_by_name')->nullable();
            $table->string('received_by_company')->default('BTSMC MANAGING SOLUTIONS INC.');

            $table->string('returned_by_name')->nullable();
            $table->string('returned_by_company')->default('BTSMC MANAGING SOLUTIONS INC.');

            $table->string('return_received_by_name')->nullable();
            $table->string('return_received_by_company')->default('ACJ SUMMIT VENTURES CORP.');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pull_out_forms');
    }
};
