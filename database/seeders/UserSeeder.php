<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Elang Putra Adam',
                'email' => 'pelang@gmail.com',
                'password' => Hash::make('password'),
                'level' => 'pelanggan',
            ],
            [
                'name' => 'Adam',
                'email' => 'adamin@gmail.com',
                'password' => Hash::make('password'),
                'level' => 'admin',
            ],
            [
                'name' => 'Putra',
                'email' => 'Putown@gmail.com',
                'password' => Hash::make('password'),
                'level' => 'owner',
            ],
        ];

        DB::table('users')->insert($users);
    }
}
