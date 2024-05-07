<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Zone;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
        ]);



        $zone = Zone::create([
            "name" => "Fec",
            "address" => "Faridpur Engineering College"
        ]);


        User::factory()->create([
            'name' => 'Collector',
            'email' => 'collector@admin.com',
            "zone" => "Fec"
        ]);

        User::factory()->create([
            'name' => 'User',
            'email' => 'user@admin.com',
            "zone" => "Fec",
            "adress" => "South hall"
        ]);

    }
}
