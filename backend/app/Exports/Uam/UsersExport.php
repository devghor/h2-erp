<?php

namespace App\Exports\Uam;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        protected Builder $query
    ) {}

    public function collection()
    {
        return $this->query->get();
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Roles', 'Created At'];
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->roles->pluck('name')->join(', '),
            $user->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
