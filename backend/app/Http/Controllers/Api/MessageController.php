<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MessagePollingRequest;
use App\Http\Requests\Api\MessageStoreRequest;
use App\Models\Channel;
use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Api\MessageIndexRequest;
use App\Http\Requests\Api\MessageDestroyRequest;

class MessageController extends Controller
{
    public function index(MessageIndexRequest $request, string $uuid)
    {
        /** @var \Illuminate\Pagination\CursorPaginator $messages */
        $messages = Message::with(['user', 'attachments'])
            // メッセージの情報で、チャンネルのuuidが一致するものを取得
            ->whereHas('channel', function (Builder $builder) use ($uuid) {
                $builder->where('uuid', $uuid);
            })
            ->orderBy('id', 'desc')
            // 20件部に読み込む。スクロールしたら更に20件読み込む。
            ->cursorPaginate(20);

        return response()->json($messages);
    }

    public function polling(MessagePollingRequest $request, string $uuid)
    {
        // ホストコンピュータに複数の端末が接続されているネットワークシステムにおいて、端末に対して、送信したいデータがあるかどうかを問い合わせること
        // タイムスタンプを付けて、
        $dateTimeString = Carbon::createFromTimestampMs(
            $request->validated('ts')
        )->format('Y-m-d H:i:s.v');

        $messages = Message::with(['user', 'attachments'])
            ->whereHas('channel', function (Builder $builder) use ($uuid) {
                $builder->where('uuid', $uuid);
            })
            ->where('created_at', '>', $dateTimeString)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($messages);
    }

    public function store(Request $request, string $uuid)
    {
        $message = DB::transaction(function () use ($request, $uuid) {
            // 複数のクエリを発行するので、トランザクションにする。
            $message = Message::create([
                'channel_id' => Channel::where('uuid', $uuid)->first()->id,
                'user_id' => Auth::id(),
                'content' => $request->validated('content'),
            ]);

            // 添付ファイルのIDがあれば、アタッチメントの中間テーブルにレコードを追加
            if ($attachmentId = $request->validated('attachment_id')) {
                $message->attachments()->attach($attachmentId);
            }

            $message->load(['user', 'attachments']);

            return $message;
        });

        return response()->json($message);
    }

    public function destroy(MessageDestroyRequest $request, string $uuid, string $id)
    {
        DB::transaction(function () use ($id) {
            $message = Message::with('attachments')->find($id);
            // メッセージを消す
            $message->delete();
            // 中間テーブルのアタッチメントをすべて消す。
            if ($message->attachments->isNotEmpty()) {
                $message->attachments()->sync([]);
                foreach ($message->attachments as $attachment) {
                    $attachment->delete();
                }

                // アタッチメントを消す(ファイル)
                Storage::delete(
                    $message->attachments->pluck('path')->toArray()
                );
            }
        });

        return response()->noContent();
    }
}
