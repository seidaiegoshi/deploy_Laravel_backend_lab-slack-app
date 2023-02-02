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
        User::factory()->create([
            'email' => 'test@example.com',
            'nickname' => 'テストユーザー',
        ]);
        User::factory()->create([
            'email' => 'test2@example.com',
            'nickname' => 'テストユーザー2',
        ]);
        User::factory()
            ->count(8)
            ->create();
        $channels = Channel::factory()
            ->count(10)
            ->create();
        foreach ($channels as $channel) {
            Message::factory()
                ->count(10)
                ->create([
                    'channel_id' => $channel->id,
                ]);
        }
        Attachment::factory()
            ->count(10)
            ->create();
    }
}
