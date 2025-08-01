<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Task;
use App\Models\Group;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->count(20)->create();
        Category::factory()->count(10)->create();
        Task::factory()->count(10)->create();
        Group::factory()->count(10)->create();
        $this->call(ManagerSeeder::class);
        $this->call(DemoUserSeeder::class);
    }
}
