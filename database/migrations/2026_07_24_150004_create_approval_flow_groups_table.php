<?php

use App\Helpers\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_flow_groups', function (Blueprint $table) {
            $table->id();
            MigrationHelper::ulidField($table);
            MigrationHelper::companyIdField($table);
            $table->unsignedBigInteger('approval_flow_id');
            $table->unsignedBigInteger('position_group_id');

            $table->foreign('approval_flow_id')->references('id')->on('approval_flows')->onDelete('cascade');
            $table->foreign('position_group_id')->references('id')->on('position_groups')->onDelete('cascade');
            $table->unique(['approval_flow_id', 'position_group_id']);

            MigrationHelper::commonFields($table);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_flow_groups');
    }
};
