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
        $admin = new \App\User(array(
            "name" => "admin",
            "password" => \Illuminate\Support\Facades\Hash::make("admin"),
            "user_type" => 1,
        ));

        $admin->save();
    }
}
