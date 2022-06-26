<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrRoles = ['superadministrator'=>'Super Administrator', 'admin'=>'Admin', 'member'=>'Member'];

        foreach ($arrRoles as $k => $v){
            Role::create([
                'name' => $k,
                'display_name' => $v,
                'guard_name' => 'web'
            ]);
        }
    }
}
