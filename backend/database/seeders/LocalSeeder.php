<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Channel;
use App\Models\Message;
use App\Models\Attachment;
use App\Models\User;

class LocalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $testUser = User::factory()->create([
            'email' => 'test@example.com',
            'nickname' => 'テストユーザー',
        ]);
        User::factory()->create([
            'email' => 'test2@example.com',
            'nickname' => 'テストユーザー2',
        ]);
        $users = User::factory()
            ->count(8)
            ->create();

        Channel::factory()
            ->hasAttached($users->random(3)->push($testUser))
            ->create([
                'name' => 'Quiet Room',
            ]);

        Channel::factory()
            ->hasAttached($users->push($testUser))
            ->has(
                Message::factory()
                    ->count(100)
                    ->recycle($users)
            )
            ->create([
                'name' => 'Noisy Room',
            ]);

        Channel::factory()
            ->hasAttached($randomUsers = $users->random(5)->push($testUser))
            ->has(
                Message::factory()
                    ->count(10)
                    ->recycle($randomUsers)
            )
            ->create([
                'name' => 'Normal Room',
            ]);
    }
}
