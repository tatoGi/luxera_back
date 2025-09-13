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
        // Get current locale or default to 'ka' (Georgian)
        $locale = app()->getLocale() ?? 'ka';
        
        // Build the redirect URL manually to ensure proper formatting
        $baseUrl = rtrim(env('APP_URL', 'http://localhost:8000'), '/');
        
        // Ensure the URL has a protocol but not duplicated
        if (strpos($baseUrl, 'http') !== 0) {
            $baseUrl = 'https://' . ltrim($baseUrl, '/');
        }
        
        // Remove any existing locale from the URL to prevent duplication
        $baseUrl = preg_replace('#/\w{2}(?=/|$)#', '', $baseUrl);
        
        // Set the redirect URL with the correct locale
        config(['services.google.redirect' => $baseUrl . '/' . $locale . '/auth/google/callback']);
        
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            // Get the locale from the URL or default to 'ka'
            $locale = request()->segment(1) ?? 'ka';
            
            // Set the application locale for this request
            app()->setLocale($locale);
            
            // Get the Google user
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
                
                // Create Sanctum token
                $token = $existingUser->createToken('google-auth')->plainTextToken;
                
                // Return JSON response with token for API usage
                if (request()->expectsJson()) {
                    return response()->json([
                        'user' => $existingUser,
                        'token' => $token,
                        'message' => 'Successfully logged in with Google!'
                    ]);
                }
                
                // For web usage, log the user in
                Auth::guard('webuser')->login($existingUser);
                
                // Redirect to dashboard with the same locale
                $locale = app()->getLocale();
                return redirect()->intended("/{$locale}/dashboard")
                    ->with('success', __('Successfully logged in with Google!'));
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
            
            // Create Sanctum token
            $token = $newUser->createToken('google-auth')->plainTextToken;
            
            // Return JSON response with token for API usage
            if (request()->expectsJson()) {
                return response()->json([
                    'user' => $newUser,
                    'token' => $token,
                    'message' => 'Account created and logged in successfully!'
                ]);
            }
            
            // For web usage, log the user in
            Auth::guard('webuser')->login($newUser);
            return redirect()->intended('/dashboard')->with('success', 'Account created and logged in successfully!');
            
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Something went wrong with Google authentication. Please try again.');
        }
    }
}
