<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

         \App\Models\User::factory()->create([
             'name' => 'admin',
             'email' => 'admin@admin.com',
             'type' => 1,
         ]);

        \App\Models\Product::factory(10)->create();
        \App\Models\Purchase::factory(10)->create();
        \App\Models\Sale::factory(10)->create();
        \App\Models\SaleDetail::factory(10)->create();
        \App\Models\Setting::factory(10)->create();


    }
}
