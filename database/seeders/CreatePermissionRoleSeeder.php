<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreatePermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($i = 1 ; $i <= 50 ; $i+=5){
            for ($j = $i+1 ; $j < $i+5 ; $j++){
                DB::table('permission_role')->insert([
                    [
                        'role_id' => 1,
                        'permission_id' => $j,
                    ],
                ]);
            }
        }

        for ($i = 42 ; $i <= 45 ; $i++){
            DB::table('permission_role')->insert([
                [
                    'role_id' => 2,
                    'permission_id' => $i                ],
            ]);
        }
    }
}
