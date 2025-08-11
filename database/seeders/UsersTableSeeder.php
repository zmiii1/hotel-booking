<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([

            //Admin
            [
                'name' => 'Admin2',
                'email' => 'admin2@gmail.com',
                'password' => Hash::make('admin1234'),
                'role' => 'admin',
                'status' => 'active', 
            ],

            //Receptionist
            [
                'name' => 'receptionist',
                'email' => 're@gmail.com',
                'password' => Hash::make('reception1234'),
                'role' => 'receptionist',
                'status' => 'active', 
            ],
    
        ]);
    }
}
