<?php

use App\Helpers\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_product_items', function (Blueprint $table) {
            $table->id();
            MigrationHelper::ulidField($table);

            $table->foreignId('product_id')->constrained('product_products')->cascadeOnDelete();
            $table->foreignId('item_product_id')->constrained('product_products')->restrictOnDelete();

            $table->decimal('quantity', 15, 4)->default(1);
            $table->decimal('unit_cost', 15, 4)->nullable();
            $table->decimal('unit_price', 15, 4)->nullable();
            $table->decimal('wastage_percent', 8, 4)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_product_items');
    }
};
