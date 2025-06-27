<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PasswordForgotRequest;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // POST /api/auth/login
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Optionally revoke previous tokens
        $user->tokens()->delete();

        $token = $user->createToken(
            'api-token',
            $user->permissions ? json_decode($user->permissions, true) : []
        )->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data'    => [
                'token' => $token,
                'user'  => $user,
            ],
        ]);
    }

    // POST /api/auth/logout
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out',
        ], 204);
    }

    // POST /api/auth/refresh
    public function refresh(Request $request)
    {
        $request->user()->tokens()->delete();

        $newToken = $request->user()->createToken(
            'api-token',
            $request->user()->permissions ? json_decode($request->user()->permissions, true) : []
        )->plainTextToken;

        return response()->json([
            'success' => true,
            'data'    => ['token' => $newToken],
        ]);
    }

    // GET /api/auth/user
    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'data'    => $request->user(),
        ]);
    }

    // POST /api/auth/password/forgot
    public function forgot(PasswordForgotRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['success' => true, 'message' => __($status)])
            : response()->json(['success' => false, 'message' => __($status)], 400);
    }

    // POST /api/auth/password/reset
    public function reset(PasswordResetRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill(['password' => Hash::make($request->password)])->save();
                $user->tokens()->delete();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['success' => true, 'message' => __($status)])
            : response()->json(['success' => false, 'message' => __($status)], 400);
    }
}
