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
        
        // Build the base URL
        $baseUrl = rtrim(env('APP_URL', 'http://localhost:8000'), '/');
        
        // Ensure the URL has a proper protocol (https in production)
        if (app()->environment('production')) {
            $baseUrl = 'https://' . ltrim(preg_replace('#^https?://#', '', $baseUrl), '/');
        } elseif (strpos($baseUrl, 'http') !== 0) {
            $baseUrl = 'http://' . ltrim($baseUrl, '/');
        }
        
        // Clean up the base URL and build the redirect URI
        $baseUrl = preg_replace('#/\w{2}(?=/|$)#', '', $baseUrl);
        $redirectUri = $baseUrl . '/' . $locale . '/auth/google/callback';
        
        // Store the redirect URI in the session
        session(['oauth_redirect_uri' => $redirectUri]);
        
        // Build the Google OAuth URL manually
        $query = http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'access_type' => 'offline',
            'prompt' => 'consent',
            'state' => $locale,
        ]);
        
        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            // Get the locale from state parameter or URL or default to 'ka'
            $locale = $request->state ?? request()->segment(1) ?? 'ka';
            
            // Set the application locale for this request
            app()->setLocale($locale);
            
            // Get the redirect URI from session or build it
            $redirectUri = session('oauth_redirect_uri') ?? 
                url('/' . $locale . '/auth/google/callback');
            
            // Configure the redirect URI for Socialite
            config(['services.google.redirect' => $redirectUri]);
            
            // Get the Google user
            $googleUser = Socialite::driver('google')->user();
            
            // Clear the session
            $request->session()->forget('oauth_redirect_uri');
            
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
