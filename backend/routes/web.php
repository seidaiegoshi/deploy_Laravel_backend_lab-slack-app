<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return abort(403);
});

Route::get('/users/image/{userId}', [UserController::class, 'showIcon'])
    ->name('web.users.image');
Route::get('/attachments/{attachmentId}', [AttachmentController::class, 'download'])
    ->name('web.attachments');

require __DIR__ . '/auth.php';
