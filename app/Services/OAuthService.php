<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Models\User;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Laravel\Socialite\Facades\Socialite;

class OAuthService
{
    protected $providers = ['facebook', 'google', 'github'];

    public function redirect(string $provider)
    {
        if (! in_array($provider, $this->providers)) {
            throw new CustomException('Invalid provider', 400);
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function callback(string $provider)
    {
        if (! in_array($provider, $this->providers)) {
            throw new CustomException('Invalid provider', 400);
        }

        $providerUser = Socialite::driver($provider)->stateless()->user();
        $user = $this->updateOrCreateUser($providerUser, $provider);
        $token = $user->createToken('api-token');

        return $token->plainTextToken;
    }

    protected function updateOrCreateUser(ProviderUser $providerUser, string $provider)
    {
        $providerIDField = $provider.'_id';
        $user = User::where($providerIDField, $providerUser->getId())->first();
        if ($user) {
            return $user;
        }

        $user = User::where('email', $providerUser->getEmail())->first();
        if ($user) {
            $user->update([$providerIDField => $providerUser->getId(), 'avatar' => $providerUser->getAvatar()]);

            return $user;
        }

        return User::updateOrCreate(
            ['email' => $providerUser->getEmail()],
            [
                'name' => $providerUser->getName(),
                $providerIDField => $providerUser->getId(),
                'avatar' => $providerUser->getAvatar(),
                'email_verified_at' => now(),
            ]
        );
    }
}
