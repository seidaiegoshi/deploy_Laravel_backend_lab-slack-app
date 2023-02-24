<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChannelStoreTest extends TestCase
{

    // このトレイトはテスト実行中にトランザクション貼ってくれ、テスト後にロールバックしてくれるものです。テスト後もキレイなDBを保ってくれます。
    use RefreshDatabase;

    /**    
     * @test  //@testでメソッド名を日本語にできる。
     */
    public function チャンネルにデータを登録するテスト()
    {


        // APIを実行するユーザーを作成
        $user = \App\Models\User::factory()->create();
        // 送信データを定義
        $postData = [
            'name' => 'チャンネル名です',
        ];

        // API実行
        $response = $this->actingAs($user)->json(
            'POST',
            route('api.channels.store'),
            $postData
        );

        // レスポンスをアサート
        $response->assertStatus(201)->assertJson([
            // サーバサイドで振られるIDやUUIDは確認しようがないため除外
            'name' => $postData['name'],
            "joined" => true,
        ]);

        // DBアサート
        // channelsテーブルにデータ登録ができていることを確認
        $this->assertDatabaseHas('channels', [
            'id' => $response['id'],
            'name' => $postData['name'],
        ]);
        // channel_userテーブルに紐づきができている（チャンネルに参加状態になっている）ことを確認
        $this->assertDatabaseHas('channel_user', [
            'channel_id' => $response['id'],
            'user_id' => $user->id,
        ]);
    }
}
