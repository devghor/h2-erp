<?php

use App\Helpers\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_brands', function (Blueprint $table) {
            $table->id();
            MigrationHelper::ulidField($table);
            MigrationHelper::companyIdField($table);
            $table->string('name');
            MigrationHelper::commonFields($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_brands');
    }
};
