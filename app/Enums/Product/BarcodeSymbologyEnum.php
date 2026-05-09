<?php

namespace App\Enums\Product;

enum BarcodeSymbologyEnum: string
{
    case EAN8       = 'EAN-8';
    case EAN13      = 'EAN-13';
    case UPCA       = 'UPC-A';
    case UPCE       = 'UPC-E';
    case Code39     = 'CODE-39';
    case Code128    = 'CODE-128';
    case ITF        = 'ITF';
    case Codabar    = 'CODABAR';
    case QRCode     = 'QR-CODE';
    case PDF417     = 'PDF417';
    case DataMatrix = 'DATA-MATRIX';

    public function label(): string
    {
        return match ($this) {
            self::EAN8       => 'EAN-8',
            self::EAN13      => 'EAN-13',
            self::UPCA       => 'UPC-A',
            self::UPCE       => 'UPC-E',
            self::Code39     => 'CODE-39',
            self::Code128    => 'CODE-128',
            self::ITF        => 'ITF',
            self::Codabar    => 'CODABAR',
            self::QRCode     => 'QR-CODE',
            self::PDF417     => 'PDF417',
            self::DataMatrix => 'DATA-MATRIX',
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
