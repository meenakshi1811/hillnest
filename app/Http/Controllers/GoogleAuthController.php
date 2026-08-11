<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            Log::warning('Google sign-in failed', ['message' => $e->getMessage()]);

            return redirect()->route('login')->withErrors([
                'login' => 'Google sign-in was cancelled or failed. Please try again.',
            ]);
        }

        $email = strtolower(trim($googleUser->getEmail() ?? ''));

        if ($email === '') {
            return redirect()->route('login')->withErrors([
                'login' => 'Your Google account does not have an email address we can use.',
            ]);
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if (! $user) {
            $user = User::where('email', $email)->first();

            if ($user) {
                if ($user->google_id && $user->google_id !== $googleUser->getId()) {
                    return redirect()->route('login')->withErrors([
                        'login' => 'This email is already linked to a different Google account.',
                    ]);
                }

                $user->update([
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser->getName() ?: 'Hillnest Customer',
                    'email' => $email,
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(Str::random(32)),
                    'email_verified_at' => now(),
                ]);

                try {
                    Mail::to($user->email)->send(new WelcomeMail($user));
                } catch (\Throwable $e) {
                    Log::error('Welcome email failed', [
                        'user_id' => $user->id,
                        'message' => $e->getMessage(),
                    ]);
                }
            }
        }

        if ($user->isBlocked()) {
            return redirect()->route('login')->withErrors([
                'login' => 'Your account has been blocked. Please contact support.',
            ]);
        }

        Auth::login($user, remember: true);
        request()->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('account.orders'));
    }
}
