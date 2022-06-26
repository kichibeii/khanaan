<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userSuper = \App\User::create([
            'name' => 'Heri Herliana',
            'email' => 'heri@kohakuho.com',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'username' => 'heri1845',
            'status' => 1
        ]);

        $userSuper->syncRoles(['superadministrator']);
    }
}
