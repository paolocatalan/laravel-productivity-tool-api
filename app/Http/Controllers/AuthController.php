<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Traits\HttpResponses;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request) {
        if (!Auth::attempt($request->all())) {
            return $this->error('', 'Credentials does not match', 401);
        }

        $user = User::where('email', $request->email)->first();

        return $this->success([
            'user' => $user,
            'token' => $user->createToken($request->ip())->plainTextToken
        ]);
    }

    public function store(StoreUserRequest $request) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password)
        ]);

        // $device = substr($request->userAgent() ?? '', 0, 255);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken($request->ip())->plainTextToken
        ]);
    }

    public function logout() {
        Auth::user()->currentAccessToken()->delete();

        return $this->success([
            'message' => 'You have been successfully logged out and your token has been deleted.'
        ], 200);
    }
}
