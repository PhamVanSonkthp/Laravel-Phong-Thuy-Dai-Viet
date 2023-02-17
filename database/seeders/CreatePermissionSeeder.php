<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreatePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach(config('permissions.table_module') as $index => $module_item){
            $permision = Permission::firstOrCreate([
                'name' => $module_item,
                'display_name' => config('permissions.table_module_name')[$index],
                'parent_id' => 0,
            ]);

            Permission::firstOrCreate([
                'name' => 'list',
                'display_name' => 'list',
                'parent_id' => $permision->id,
                'key_code' => $module_item . '_' . 'list',
            ]);

            Permission::firstOrCreate([
                'name' => 'add',
                'display_name' => 'add',
                'parent_id' => $permision->id,
                'key_code' => $module_item . '_' . 'add',
            ]);

            Permission::firstOrCreate([
                'name' => 'edit',
                'display_name' => 'edit',
                'parent_id' => $permision->id,
                'key_code' => $module_item . '_' . 'edit',
            ]);

            Permission::firstOrCreate([
                'name' => 'delete',
                'display_name' => 'delete',
                'parent_id' => $permision->id,
                'key_code' => $module_item . '_' . 'delete',
            ]);
        }
    }
}
