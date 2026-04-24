<?php

namespace App\Exports\Uam;

use App\Models\Uam\Role;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RolesExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private readonly Collection $roles) {}

    public function collection(): Collection
    {
        return $this->roles;
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Description', 'Permissions', 'Created At'];
    }

    /** @param Role $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            $row->description ?? '',
            $row->permissions->pluck('name')->join(', '),
            $row->created_at->toDateTimeString(),
        ];
    }
}
