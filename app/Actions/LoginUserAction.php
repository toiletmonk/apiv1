<?php

namespace App\Actions;

use App\Exceptions\AuthException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginUserAction
{
    public function execute(array $data)
    {
        $user = User::where('email', $data['email'])->first();
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw new AuthException('Invalid credentials');
        }
        if (! $user->phone_verified_at && ! $user->hasVerifiedEmail()) {
            throw new AuthException('Email or phone is not verified');
        }

        $expiresAt = isset($data['remember']) && $data['remember']
            ? now()->addDays(30)
            : now()->addHours(3);
        $token = $user->createToken('api-token');
        $token->accessToken->expires_at = $expiresAt;

        return $token->plainTextToken;
    }
}
