<?php

use App\Helpers\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('function_assignments', function (Blueprint $table) {
            $table->id();
            MigrationHelper::ulidField($table);
            MigrationHelper::companyIdField($table);
            $table->string('name');
            $table->string('code')->nullable();
            $table->json('user_ids');
            $table->text('description')->nullable();
            $table->tinyInteger('type');
            $table->unique(['company_id', 'type'], 'function_assignments_company_type_unique');
            MigrationHelper::commonFields($table);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('function_assignments');
    }
};
