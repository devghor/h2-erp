<?php

use App\Helpers\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            MigrationHelper::ulidField($table);
            MigrationHelper::companyIdField($table);
            $table->string('name');
            $table->json('working_days')->nullable();
            $table->json('weekends')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->json('special_working_dates')->nullable();
            $table->json('special_weekend_dates')->nullable();
            MigrationHelper::commonFields($table);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
