<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Demo Manager',
            'email' => 'demomanager@kamrankhan.dev',
            'password' => Hash::make('demomanagerpassword'),
            'is_admin' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
