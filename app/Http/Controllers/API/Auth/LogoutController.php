<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function delete(Request $request)
    {
        return request()->user()->currentAccessToken()->delete();
    }

    public function deleteAll(Request $request)
    {
        return request()->user()->tokens()->delete();
    }
}
