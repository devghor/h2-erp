<?php

use App\Helpers\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            MigrationHelper::ulidField($table);
            MigrationHelper::companyIdField($table);
            $table->string('name');
            $table->string('code')->nullable();
            $table->integer('leave_count');
            $table->integer('max_balance');
            $table->string('availability');
            $table->string('frequency');
            $table->string('eligibility');
            $table->json('employee_type');
            $table->boolean('advanced_leave')->default(false);
            $table->boolean('document_required')->default(false);
            $table->boolean('carry_forward')->default(false);
            $table->boolean('allow_pending_leave')->default(false);
            MigrationHelper::commonFields($table);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};