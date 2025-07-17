<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Resources\LoginResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $json = LoginResource::make($user)->jsonSerialize();
            $response = response()->json($json);
        } else {
            $message = __('auth.failed');
            $response = response($message, 401);
        }
        return $response;
    }

    public function signup(SignupRequest $request)
    {
        $attributes = $request->validated();
        try {
            $user = User::query()->create($attributes);
            $json = LoginResource::make($user)->jsonSerialize();
            $response = response()->json($json);
        } catch (\Error|\Exception $e) {
            Log::error($e->getMessage());
            $message = __('auth.signup.failed');
            $response = response($message, 500);
        }
        return $response;
    }
}
