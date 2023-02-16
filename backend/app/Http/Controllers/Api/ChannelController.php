<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ChannelController extends Controller
{

    public function index(Request $request)
    // チャンネル一覧
    {
        $channels = Channel::with('users')
            // with('users')でリレーションの値をとってこれる
            ->orderBy('created_at', 'asc')
            // 1ページ20件
            ->paginate(20);


        return response()->json($channels);
    }

    public function store(Request $request)
    {
        $channel = Channel::create([
            'uuid' => Str::uuid(),
            'name' => $request->input('name'),
        ]);

        $channel->users()->sync([Auth::id()]);

        return response()->json($channel);
    }

    public function join(Request $request, string $uuid)
    {
        // uuidを1件だけとる
        $channel = Channel::where('uuid', $uuid)->first();
        if (!$channel) {
            // そのチャンネルがなかったら、エラー
            abort(404, 'Not Found.');
        }
        if ($channel->users()->find(Auth::id())) {
            // すでに参加しているかどうか・
            throw ValidationException::withMessages([
                'uuid' => 'Already Joined.',
            ]);
        }

        // チャンネルがあって、自分がすでに登録されていなかったら、join
        $channel->users()->attach(Auth::id());

        return response()->noContent();
    }

    public function leave(Request $request, string $uuid)
    {
        $channel = Channel::where('uuid', $uuid)->first();
        if (!$channel) {
            abort(404, 'Not Found.');
        }
        if (!$channel->users()->find(Auth::id())) {
            throw ValidationException::withMessages([
                'uuid' => 'Already Left.',
            ]);
        }

        // attach レコードを追加する
        // detach レコードを削除する
        // sync 値を入れ替える(更新する)
        $channel->users()->detach(Auth::id());

        return response()->noContent();
    }
}
