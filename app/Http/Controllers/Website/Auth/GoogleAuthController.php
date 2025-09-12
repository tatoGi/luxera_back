<?php

namespace App\Http\Controllers\Website\Auth;

use App\Http\Controllers\Controller;
use App\Models\WebUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists with this email
            $existingUser = WebUser::where('email', $googleUser->getEmail())->first();
            
            if ($existingUser) {
                // Update Google ID if not set
                if (!$existingUser->google_id) {
                    $existingUser->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                }
                
                // Log the user in
                Auth::guard('webuser')->login($existingUser);
                
                return redirect()->intended('/dashboard')->with('success', 'Successfully logged in with Google!');
            }
            
            // Create new user
            $newUser = WebUser::create([
                'fullname' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(24)), // Random password since they'll use Google
                'email_verified_at' => now(), // Google emails are pre-verified
            ]);
            
            // Log the new user in
            Auth::guard('webuser')->login($newUser);
            
            return redirect()->intended('/dashboard')->with('success', 'Account created and logged in successfully!');
            
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Something went wrong with Google authentication. Please try again.');
        }
    }
}
