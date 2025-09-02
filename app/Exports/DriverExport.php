<?php

namespace App\Exports;

use App\Models\Admin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class DriverExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $employees = Admin::whereHas('roles', function ($query) {
                        $query->where('name', 'independent-contractors');
                    })
                    ->get(['id', 'first_name', 'last_name', 'email', 'phone_number', 'created_at']);

        return $employees;
    }

    public function headings(): array
    {
        return ['ID', 'First Name', 'Last Name', 'Email', 'Phone Number', 'Created At'];
    }
}
