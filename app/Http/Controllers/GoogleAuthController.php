<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        // Cek apakah user cancel di Google
        if (request()->has('error')) {
            return redirect('/login');
        }

        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::updateOrCreate(
                ['google_id' => $googleUser->getId()],
                [
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'avatar' => $googleUser->getAvatar(),
                ]
            );
            Auth::login($user, true);
            if (Auth::check()) {
                return redirect('/');
            } else {
                return redirect('/login')->with('error', 'Session gagal tersimpan');
            }
        } catch (\Exception $e) {
            return redirect('/login')->with('error', $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}