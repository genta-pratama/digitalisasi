public function callback()
{
    try {
        $googleUser = Socialite::driver('google')->user();
        
        $user = User::updateOrCreate(
            ['google_id' => $googleUser->getId()],
            [
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'avatar'  => $googleUser->getAvatar(),
            ]
        );
        
        Auth::login($user, true); // tambah "true" untuk remember
        
        // Debug session
        if (Auth::check()) {
            return redirect('/');
        } else {
            return redirect('/login')->with('error', 'Auth::check() gagal setelah login');
        }
        
    } catch (\Exception $e) {
        return redirect('/login')->with('error', $e->getMessage());
    }
}