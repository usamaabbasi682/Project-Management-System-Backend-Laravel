<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(6)->create();
        // $tags = Tag::factory(15)->create();
        
        $this->call([
            UserSeeder::class,
            StatusSeeder::class,
        ]);

        $users->each(function ($user) {
            $user->assignRole('user');
        });
    }
}
