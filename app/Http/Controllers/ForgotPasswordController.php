<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordResetRequest;
use App\Services\PasswordResetService;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function __construct(
        protected PasswordResetService $passwordResetService
    ) {}

    public function sendEmail(PasswordResetRequest $request)
    {
        $request->validated();

        $status = $this->passwordResetService->sendResetLink($request->only('email'));

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successfully.']);
        }

        return response()->json([
            'message' => 'Unable to send link.',
        ]);
    }
}
