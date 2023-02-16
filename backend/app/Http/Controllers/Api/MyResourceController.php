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
        // 自分の情報をjsonで返す
        return response()->json(Auth::user());
    }

    public function channels(Request $request)
    {
        //チャンネルの一覧を取る。
        $channels = Channel::with('users')
            //whereHasはリレーション先のテーブルの条件で検索したいときに使う。
            // Channelのusersが、functionの条件のやつ
            ->whereHas('users', function (Builder $query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($channels);
    }

    public function updateIcons(Request $request)
    {
        //自分の画像を登録する。

        DB::transaction(function () use ($request) {
            // requestのimageというキーの値を、users/imagesというフォルダにアップロードする。
            // users/imagesは、storage/users/imagesにある。
            // savepathには画像のパスが入っている。
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
                // エラーが起きたらロールバックして、tryの処理は取り消される。
            }
        });

        return response()->json(
            route('web.users.image', ['userId' => Auth::id()])
        );
    }
}
