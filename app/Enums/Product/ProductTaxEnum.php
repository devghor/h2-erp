<?php

namespace App\Enums\Product;

enum ProductTaxEnum: string
{
    case CGST10 = 'CGST@10';
    case SGST8  = 'SGST@8';
    case VAT15  = 'VAT@15%';
    case GST15  = 'GST@15';
    case VAT    = 'VAT';

    public function label(): string
    {
        return match ($this) {
            self::CGST10 => 'CGST@10',
            self::SGST8  => 'SGST@8',
            self::VAT15  => 'VAT@15%',
            self::GST15  => 'GST@15',
            self::VAT    => 'VAT',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn (self $case) => ['value' => $case->value, 'label' => $case->label()],
            self::cases(),
        );
    }
}
