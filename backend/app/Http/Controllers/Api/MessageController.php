<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index(Request $request, string $uuid)
    {
        /** @var \Illuminate\Pagination\CursorPaginator $messages */
        $messages = Message::with(['user', 'attachments'])
            ->whereHas('channel', function (Builder $builder) use ($uuid) {
                $builder->where('uuid', $uuid);
            })
            ->orderBy('id', 'desc')
            ->cursorPaginate(20);

        return response()->json($messages);
    }

    public function polling(Request $request, string $uuid)
    {
        $dateTimeString = Carbon::createFromTimestampMs(
            $request->input('ts')
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
            $message = Message::create([
                'channel_id' => Channel::where('uuid', $uuid)->first()->id,
                'user_id' => Auth::id(),
                'content' => $request->input('content'),
            ]);

            if ($attachmentId = $request->input('attachment_id')) {
                $message->attachments()->attach($attachmentId);
            }

            $message->load(['user', 'attachments']);

            return $message;
        });

        return response()->json($message);
    }

    public function destroy(Request $request, string $uuid, string $id)
    {
        DB::transaction(function () use ($id) {
            $message = Message::with('attachments')->find($id);
            $message->delete();
            if ($message->attachments->isNotEmpty()) {
                $message->attachments()->sync([]);
                foreach ($message->attachments as $attachment) {
                    $attachment->delete();
                }

                Storage::delete(
                    $message->attachments->pluck('path')->toArray()
                );
            }
        });

        return response()->noContent();
    }
}
