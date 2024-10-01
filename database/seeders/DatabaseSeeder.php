<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Address;
use App\Models\Item;
use App\Models\Order;
use App\Models\Order_Item;
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
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Address::factory(100)->create();
        Item::factory(100)->create();
        Order::factory(100)->create();
        Order_Item::factory(50)->create();

    }
}
