<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UserExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // app/Exports/UsersExport.php

    return User::select('name', 'email', 'role', 'created_at')->get();

    }

    public function headings(): array
    {
        return [
            'Nom',
            'Email', 
            'Rôle',
            'Date création'
        ];
    }
}


