<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('desks', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->after('company_id');
            $table->unsignedBigInteger('division_id')->nullable()->after('branch_id');
            $table->unsignedBigInteger('department_id')->nullable()->after('division_id');
            $table->tinyInteger('desk_group')->nullable()->after('department_id');

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('set null');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('desks', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['division_id']);
            $table->dropForeign(['department_id']);
            $table->dropColumn(['branch_id', 'division_id', 'department_id', 'desk_group']);
        });
    }
};
