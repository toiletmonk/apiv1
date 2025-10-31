<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $data = $this->authService->login($request->validated());

        return response()->json($data);
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());

        return response()->json(['user' => $user]);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = auth()->user();

        /** @var \App\Models\User $user */
        $this->authService->changePassword($user, $request->validated());

        return response()->json(['message' => 'Password changed']);
    }
}
