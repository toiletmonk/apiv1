<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Services\PasswordResetService;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function __construct(protected PasswordResetService $passwordResetService) {}

    public function reset(ResetPasswordRequest $request)
    {
        $status = $this->passwordResetService->resetPassword($request->only('token', 'password', 'password_confirmation'));

        return response()->json([
            'message' => $status === Password::PASSWORD_RESET
            ? 'Password reset successfully'
            : 'Invalid token',
        ]);
    }
}
