<?php

use App\Helpers\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrbps', function (Blueprint $table) {
            $table->id();
            MigrationHelper::ulidField($table);
            $table->string('name')->nullable();
            $table->unsignedBigInteger('user_id');
            MigrationHelper::companyIdField($table);
            MigrationHelper::commonFields($table);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrbps');
    }
};
