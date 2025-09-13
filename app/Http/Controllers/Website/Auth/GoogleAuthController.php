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
        // Get current locale or default to 'en'
        $locale = app()->getLocale() ?? 'en';
        
        // Configure the redirect with locale
        $config = config('services.google');
        
        // Add locale parameters to the redirect URL
        $query = http_build_query([
            'client_id' => $config['client_id'],
            'redirect_uri' => $config['redirect'],
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'access_type' => 'offline',
            'prompt' => 'consent',
            'hl' => $locale,
            'locale' => $locale
        ]);
        
        return redirect()->to('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            if (!$request->has('code')) {
                return response()->json(['error' => 'Authorization code not found'], 400);
            }
            
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists with this email
            $existingUser = WebUser::where('email', $googleUser->getEmail())->first();
            
            if ($existingUser) {
                // Update Google ID if not set
                if (!$existingUser->google_id) {
                    $existingUser->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'email_verified_at' => now(), // Mark email as verified
                    ]);
                }
                
                // Create Sanctum token
                $token = $existingUser->createToken('google-auth')->plainTextToken;
                
                // Return JSON response for API usage
                if ($request->expectsJson()) {
                    return response()->json([
                        'user' => $existingUser,
                        'token' => $token,
                    ]);
                }
                
                // For web, log in the user
                Auth::guard('web')->login($existingUser);
                return redirect()->intended('/');
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
