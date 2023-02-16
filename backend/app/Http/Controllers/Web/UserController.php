<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showIcon(Request $request, int $userId)
    {
        $path = User::findOrFail($userId)->icon_path;

        return response()->file(\Storage::path($path));
    }
}
