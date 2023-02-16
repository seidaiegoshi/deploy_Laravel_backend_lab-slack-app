<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MyResourceController extends Controller
{
    public function me(Request $request)
    {
        return response()->json(Auth::user());
    }

    public function channels(Request $request)
    {
        $channels = Channel::with('users')
            ->whereHas('users', function (Builder $query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($channels);
    }

    public function updateIcons(Request $request)
    {
        DB::transaction(function () use ($request) {
            $savedPath = $request->image->store('users/images');

            try {
                Auth::user()
                    ->fill([
                        'icon_path' => $savedPath,
                    ])
                    ->save();
            } catch (\Exception $e) {
                // DBでのエラーが起きた場合は、保存したファイルを削除
                Storage::delete($savedPath);
                throw $e;
            }
        });

        return response()->json(
            route('web.users.image', ['userId' => Auth::id()])
        );
    }
}
