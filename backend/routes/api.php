<?php

use App\Http\Controllers\Api\AttachmentController;
use App\Http\Controllers\Api\ChannelController;
use App\Http\Controllers\Api\MessageController;
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

Route::middleware(['auth:sanctum'])
    ->name('api.')
    ->group(function () {
        Route::get('/me', [MyResourceController::class, 'me'])->name('me');

        Route::prefix('/my')
            ->name('my.')
            ->group(function () {
                Route::post('/icons', [MyResourceController::class, 'updateIcons'])
                    ->name('icons');

                Route::get('/channels', [MyResourceController::class, 'channels'])
                    ->name('channels');
            });

        Route::prefix('/channels')
            ->name('channels.')
            ->group(function () {
                Route::get('', [ChannelController::class, 'index'])->name('index');

                Route::post('', [ChannelController::class, 'store'])->name('store');

                Route::prefix('/{uuid}')->group(function () {
                    Route::prefix('/messages')
                        ->name('messages.')
                        ->group(function () {
                            Route::get('', [MessageController::class, 'index'])
                                ->name('index');

                            Route::get('/polling', [MessageController::class, 'polling'])
                                ->name('polling');

                            Route::post('', [MessageController::class, 'store'])
                                ->name('store');

                            Route::delete('/{id}', [MessageController::class, 'destroy'])
                                ->name('destroy');
                        });

                    Route::post('/attachments', [AttachmentController::class, 'storeAttachmentFile'])
                        ->name('attachments');

                    Route::post('/join', [ChannelController::class, 'join'])
                        ->name('join');

                    Route::post('/leave', [ChannelController::class, 'leave'])
                        ->name('leave');
                });
            });
    });
