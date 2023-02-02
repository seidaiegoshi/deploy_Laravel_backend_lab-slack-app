<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Channel;

class LocalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 「Factoryの定義に合わせて、１０件のデータをつくってくれー」って感じの指定です
        Channel::factory()->count(10)->create();
    }
}
