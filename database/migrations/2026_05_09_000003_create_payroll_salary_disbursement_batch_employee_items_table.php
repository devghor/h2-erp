<?php

use App\Helpers\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_salary_disbursement_batch_employee_items', function (Blueprint $table) {
            $table->id();
            MigrationHelper::ulidField($table);
            $table->bigInteger('company_id')->index('psdb_emp_items_company_id_idx');
            $table->foreignId('payroll_salary_disbursement_batch_employee_id')
                ->constrained('payroll_salary_disbursement_batch_employees', 'id', 'psdb_emp_item_emp_fk')
                ->cascadeOnDelete();
            $table->unsignedBigInteger('payroll_salary_head_id')->nullable();
            $table->string('head_name');
            $table->string('head_category');
            $table->decimal('amount', 15, 2)->default(0);
            $table->boolean('is_adjustment')->default(false);
            MigrationHelper::commonFields($table);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_salary_disbursement_batch_employee_items');
    }
};
