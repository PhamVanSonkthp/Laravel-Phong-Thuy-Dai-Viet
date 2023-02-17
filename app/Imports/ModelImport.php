<?php

namespace App\Imports;

use App\Models\Helper;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ModelImport implements ToModel, WithHeadings
{

    public function model(array $row)
    {
        return new User([
            "first_name" => $row['first_name'],
            "last_name" => $row['last_name'],
            "email" => $row['email'],
            "mobile_number" => $row['mobile_number'],
            "role_id" => 2, // User Type User
            "status" => 1,
        ]);
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
    }
}
