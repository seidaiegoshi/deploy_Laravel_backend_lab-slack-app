<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyResourceController extends Controller
{
    public function me(Request $request)
    {
        $me = Auth::user();
        return response()->json($me);
    }
}
