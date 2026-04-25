<?php

namespace App\Exports\Product;

use App\Models\Product\Category;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoriesExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private readonly Collection $categories) {}

    public function collection(): Collection
    {
        return $this->categories;
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Parent Category', 'Created At'];
    }

    /** @param Category $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            $row->parent?->name ?? '',
            $row->created_at->toDateTimeString(),
        ];
    }
}
