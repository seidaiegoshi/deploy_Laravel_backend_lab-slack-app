<?php

use App\Http\Controllers\Api\MyResourceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:sanctum'])
    ->name("api.")
    ->group(function () {

        Route::post('/channels', function (Request $request) {
            // とりあえず、ベタ書きでレスポンスする
            // レスポンスの形をswaggerと合わせる
            return response()->json([
                'id' => 1,
                'uuid' => \Str::uuid(),
                'name' => 'テストチャンネルの名前',
                'joined' => true,
            ]);
        });


        Route::get('/me', [MyResourceController::class, "me"])->name("me");


        Route::post("/my/icons", function (Request $request) {
            return response()->json(
                "http://localhost/users/image/1"
            );
        });

        Route::get('/my/channels', function () {
            // とりあえず、ベタ書きでレスポンスする
            // レスポンスの形をswaggerと合わせる
            return response()->json([
                'id' => 1,
                'uuid' => "abcdef12345",
                'name' => "チャンネル",
                'joined' => true,
            ]);
        });

        Route::delete("/channels/{uuid}/messages/{id}", function ($uuid, $id) {
            return response()->noContent();
        });
    });
