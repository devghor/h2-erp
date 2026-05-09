<?php

use App\Helpers\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_products', function (Blueprint $table) {
            $table->id();
            MigrationHelper::ulidField($table);
            MigrationHelper::companyIdField($table);

            $table->string('name');
            $table->string('type');
            $table->string('code')->nullable();
            $table->string('barcode_symbology')->nullable();

            $table->foreignId('product_brand_id')->nullable()->constrained('product_brands')->nullOnDelete();
            $table->foreignId('product_category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->foreignId('product_unit_id')->nullable()->constrained('product_units')->nullOnDelete();
            $table->foreignId('product_sale_unit_id')->nullable()->constrained('product_units')->nullOnDelete();
            $table->foreignId('product_purchase_unit_id')->nullable()->constrained('product_units')->nullOnDelete();

            $table->decimal('product_cost', 15, 4)->nullable();
            $table->string('profit_margin_type')->nullable();
            $table->decimal('profit_margin', 15, 4)->nullable();
            $table->decimal('product_price', 15, 4)->nullable();
            $table->decimal('wholesale_price', 15, 4)->nullable();
            $table->decimal('daily_sale_objective', 15, 4)->nullable();

            $table->string('product_tax')->nullable();
            $table->string('tax_method')->nullable();

            $table->unsignedSmallInteger('warranty_value')->nullable();
            $table->string('warranty_duration_type')->nullable();
            $table->unsignedSmallInteger('guarantee_value')->nullable();
            $table->string('guarantee_duration_type')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->boolean('has_batch_and_expire_date')->default(false);
            $table->boolean('has_imei_or_serial_no')->default(false);
            $table->boolean('has_promotional_price')->default(false);

            $table->text('embedded_barcode')->nullable();
            $table->text('product_details')->nullable();

            MigrationHelper::commonFields($table);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_products');
    }
};
