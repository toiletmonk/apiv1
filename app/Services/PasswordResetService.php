<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetService
{
    public function sendResetLink(array $data): bool
    {
        if (! User::where('email', $data['email'])->exists()) {
            throw new CustomException('User not found', 404);
        }
        $status = Password::sendResetLink(['email' => $data['email']]);

        return match ($status) {
            Password::RESET_LINK_SENT => __('Password reset link has been sent'),
            Password::INVALID_USER => __('Invalid user'),
        };
    }

    public function resetPassword(array $data)
    {
        $status = Password::reset(
            $data,
            function (User $user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
                if (method_exists($user, 'token')) {
                    $user->token()->delete();
                }
                event(new PasswordReset($user));
            }
        );

        return match ($status) {
            Password::RESET_LINK_SENT => __('Password reset link has been sent'),
            Password::INVALID_USER => __('Invalid user'),
            Password::INVALID_TOKEN => __('Invalid token'),
            default => throw new CustomException('Password reset failed'),
        };
    }
}
