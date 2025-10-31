<?php

namespace App\Actions;

use App\Exceptions\AuthException;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    public function execute(array $data): User
    {
        $cachedCode = Cache::get("verify_{$data['phone']}");
        if (! $cachedCode || $cachedCode != $data['phone_code']) {
            throw new AuthException('Phone not verified');
        }

        $user = User::create([
            'email' => $data['email'],
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'email_verified_at' => null,
            'phone_verified_at' => now(),
        ]);

        $user->sendEmailVerificationNotification();

        return $user;
    }
}
