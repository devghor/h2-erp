<?php

namespace App\Services;

use Carbon\Carbon;

class FormatService
{
    public static function date(Carbon|string|null $date): ?string
    {
        return $date ? Carbon::parse($date)->format(config('core.date.format')) : null;
    }

    public static function dateTime(Carbon|string|null $date): ?string
    {
        return $date ? Carbon::parse($date)->format(config('core.date.datetime_format')) : null;
    }

    public static function pdfFileName(string $prefix): string
    {
        return $prefix . '_' . now()->format('Y_m_d_H_i_s') . '.pdf';
    }

    public static function excelFileName(string $prefix): string
    {
        return $prefix . '_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
    }
}
