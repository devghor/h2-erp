<?php

use App\Helpers\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_flow_group_items', function (Blueprint $table) {
            $table->id();
            MigrationHelper::ulidField($table);
            MigrationHelper::companyIdField($table);
            $table->unsignedBigInteger('approval_flow_group_id');
            $table->unsignedBigInteger('approval_level_id');
            $table->integer('sequence');

            $table->foreign('approval_flow_group_id')->references('id')->on('approval_flow_groups')->onDelete('cascade');
            $table->foreign('approval_level_id')->references('id')->on('approval_levels')->onDelete('cascade');

            MigrationHelper::commonFields($table);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_flow_group_items');
    }
};
