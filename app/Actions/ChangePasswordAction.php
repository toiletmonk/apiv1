<?php

namespace App\Actions;

use App\Exceptions\AuthException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ChangePasswordAction
{
    public function execute(User $user, array $data): bool
    {
        $authenticatedUser = auth()->user();
        if (! $authenticatedUser || $authenticatedUser->id !== $user->id) {
            throw new AuthException('Invalid user.');
        }
        if (! Hash::check($data['current_password'], $user->password)) {
            throw new AuthException('Invalid password.');
        }

        $user->password = Hash::make($data['new_password']);
        $saved = $user->save();
        if ($saved) {
            $user->tokens()->delete();
        }

        return $saved;
    }
}
