<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(5)->create([
            'type' => 'admin',
        ]);

        User::factory(50)->create([
            'type' => 'driver',
        ]);
    }
}
