<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Api\ChannelStoreRequest;
use App\Http\Resources\ChannelResource;


class ChannelController extends Controller
{

    public function __construct(protected Channel $channel)
    {
    }

    public function index(Request $request)
    // チャンネル一覧
    {
        $channels = Channel::with('users')
            // with('users')でリレーションの値をとってこれる
            ->orderBy('created_at', 'asc')
            // 1ページ20件
            ->paginate(20);


        return ChannelResource::collection($channels);
    }

    public function store(ChannelStoreRequest $request)
    {
        //途中でコケたらロールバックするようにトランザクションにする。
        $channel = \DB::transaction(
            function () use ($request) {
                $channel = $this->channel->store($request->validated('name'));

                $this->channel->addFirstMember($channel, Auth::id());
                return $channel;
            }
        );
        return new ChannelResource($channel);
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
