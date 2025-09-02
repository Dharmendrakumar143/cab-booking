<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::all(['id', 'first_name','last_name', 'email', 'created_at']);
    }

    public function headings(): array
    {
        return ['ID', 'First Name', 'Last Name', 'Email', 'Created At'];
    }
}
