<?php

namespace App\Exports\Uam;

use App\Models\Uam\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private readonly Collection $users) {}

    public function collection(): Collection
    {
        return $this->users;
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Username', 'Roles', 'Created At'];
    }

    /** @param User $row */
    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            $row->email,
            $row->username ?? '',
            $row->roles->pluck('name')->join(', '),
            $row->created_at->toDateTimeString(),
        ];
    }
}
