<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class OAuthController extends Controller
{

    public function redirect()
    {
        return Socialite::driver('github')->with(['prompt' => 'select_account'])->scopes(['repo', 'read:user', 'user:email'])->redirect();
    }


    public function callback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();
        } catch (\Exception $e) {
            return redirect()->route('home')->withErrors(['error' => 'Hubo un problema al autenticarte con GitHub.']);
        }

        $userProvider = UserProvider::where('provider', 'github')
            ->where('provider_id', $githubUser->getId())
            ->first();

        if ($userProvider) {
            $userProvider->update([
                'token' => $githubUser->token,
            ]);

            Auth::login($userProvider->user);
            return redirect()->route('dashboard');
        }

        $user = User::where('email', $githubUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $githubUser->getName() ?? $githubUser->getNickname(),
                'email' => $githubUser->getEmail(),
                'password' => bcrypt(Str::random(24)),
                'email_verified_at' => now(),
            ]);
        }

        UserProvider::create([
            'user_id' => $user->id,
            'provider' => 'github',
            'provider_id' => $githubUser->getId(),
            'username' => $githubUser->getNickname(),
            'token' => $githubUser->token,
        ]);

        Auth::login($user);
        return redirect()->route('dashboard');
    }
}
