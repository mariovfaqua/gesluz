<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Address;
use App\Models\Item;
use App\Models\Order;
use App\Models\Order_Item;
use App\Models\Tag;
use App\Models\Item_Tag;
use App\Models\Brand;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gesluz.es',
            'password' => Hash::make('gesluz.admin'),
            'role' => 'admin'
        ]);

        Tag::factory()->create([
            'id' => 1,
            'nombre' => 'ofertas'
        ]);

        Address::factory(100)->create();
        Brand::factory(50)->create();
        Item::factory(100)->create();
        Order::factory(100)->create();
        Order_Item::factory(50)->create();
        Tag::factory(100)->create();
        Item_Tag::factory(50)->create();

    }
}
