<?php

use App\Helpers\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            MigrationHelper::ulidField($table);
            MigrationHelper::companyIdField($table);
            $table->string('name');
            $table->string('code')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('division_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('position_group_id')->nullable();

            $table->foreign('parent_id')->references('id')->on('positions')->onDelete('set null');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('set null');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('position_group_id')->references('id')->on('position_groups')->onDelete('set null');

            MigrationHelper::commonFields($table);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
