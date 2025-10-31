<?php

namespace App\Services;

use app\Actions\ChangePasswordAction;
use app\Actions\LoginUserAction;
use app\Actions\RegisterUserAction;
use App\Models\User;

class AuthService
{
    protected LoginUserAction $loginAction;

    protected RegisterUserAction $registerAction;

    protected ChangePasswordAction $changePasswordAction;

    public function __construct(
        LoginUserAction $loginAction,
        RegisterUserAction $registerAction,
        ChangePasswordAction $changePasswordAction
    ) {}

    public function login(array $data): string
    {
        return $this->loginAction->execute($data);
    }

    public function register(array $data): User
    {
        return $this->registerAction->execute($data);
    }

    public function changePassword(User $user, array $data)
    {
        return $this->changePasswordAction->execute($user, $data);
    }
}
