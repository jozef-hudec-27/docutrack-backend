<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => [
                'required', 'email', 'unique:users,email'
            ],
            'password' => ['required', 'min:8', 'confirmed'],
            'device_name' => ['required']
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return $user->createToken($request->device_name)->plainTextToken;
    }
}
