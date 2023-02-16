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
    {
        $channels = Channel::with('users')
            ->orderBy('created_at', 'asc')
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
        $channel = Channel::where('uuid', $uuid)->first();
        if (!$channel) {
            abort(404, 'Not Found.');
        }
        if ($channel->users()->find(Auth::id())) {
            throw ValidationException::withMessages([
                'uuid' => 'Already Joined.',
            ]);
        }

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

        $channel->users()->detach(Auth::id());

        return response()->noContent();
    }
}
