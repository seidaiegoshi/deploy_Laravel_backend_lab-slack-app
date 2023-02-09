<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChannelController extends Controller
{
    public function store(Request $request)
    {
        // こんな風にリクエストパラメータを受け取ります。
        $name = $request->name; // $request->input('name') でもOK

        // Eloquentを使ってDBに保存します
        $storedChannel = Channel::create([
            'name' => $name,
            'uuid' => \Str::uuid(),
        ]);

        // チャンネルを作った人はそのままチャンネルに参加している状態を作りたい
        // つまり、channel_userテーブルの中間テーブルに紐付けデータを作成
        $storedChannel->users()->sync([Auth::id()]);

        return response()->json($storedChannel);
    }
}
