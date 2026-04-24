<?php

namespace App\Services;

class FormatService
{
    public function dateFormat(): string
    {
        return 'Y-m-d';
    }

    public function dateTimeFormat(): string
    {
        return 'Y-m-d H:i:s';
    }

    public function excelDateFormat(): string
    {
        return 'Y-m-d';
    }

    public function excelDateTimeFormat(): string
    {
        return 'Y-m-d H:i:s';
    }

    public function dbDateFormat(): string
    {
        return 'Y-m-d';
    }

    public function exportFileName(string $prefix, string $extension = 'xlsx'): string
    {
        return "{$prefix}_" . now()->format($this->dateFormat()) . ".{$extension}";
    }
}
