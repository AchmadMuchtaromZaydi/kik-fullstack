<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name'              => 'admin magang 2',
                'email'             => 'zaydiamuchtarom@gmail.com',
                'whatsapp'          => null,
                'code_verified'     => null,
                'email_verified_at' => null,
                'password'          => Hash::make('zaydiamuchtarom'), // password sesuai permintaan
                'role'              => 'admin',
                'isActive'          => null,
                'remember_token'    => null,
                'created_at'        => '2025-07-16 02:58:51',
                'updated_at'        => '2025-07-16 02:58:51',
            ],
        ]);
    }
}
