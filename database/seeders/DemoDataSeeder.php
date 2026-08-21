<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Request as RequestModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Seed 10 klien (beda pengembang) & 100 request per klien
     * dengan status & priority random.
     */
    public function run(): void
    {
        // Bikin sejumlah user acak (staff/developer) untuk jadi created_by & assigned_to.
        User::factory()->count(fake()->numberBetween(8, 15))->create();

        $userIds = User::pluck('id')->all();

        // 10 klien, masing-masing punya nama pengembang berbeda (unique() di factory).
        $clients = Client::factory()->count(10)->create();

        foreach ($clients as $client) {
            RequestModel::factory()
                ->count(100)
                ->for($client)
                ->state(new Sequence(fn () => [
                    'created_by'  => fake()->randomElement($userIds),
                    'assigned_to' => fake()->randomElement($userIds),
                ]))
                ->create();
        }
    }
}
