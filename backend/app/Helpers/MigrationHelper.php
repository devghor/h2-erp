<?php

namespace App\Helpers;

class MigrationHelper
{
    public static function companyIdField($table)
    {
        $table->uuid('company_id')->index();
    }

    public static function userTrackingFields($table)
    {
        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('updated_by')->nullable();
        $table->bigInteger('deleted_by')->nullable();
    }
}
