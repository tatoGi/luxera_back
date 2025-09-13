<?php

namespace App\Http\Controllers\Website\Auth;

use App\Http\Controllers\Controller;
use App\Models\WebUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use GuzzleHttp\Client as GuzzleClient;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->with([
                'access_type' => 'offline',
                'prompt' => 'consent',
                'include_granted_scopes' => 'true',
            ])
            ->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Find or create user
            $user = $this->findOrCreateUser($googleUser);
            
            // Log the user in
            Auth::guard('webuser')->login($user);
            
            // Create API token
            $token = $user->createToken('google-auth')->plainTextToken;
            
            // Store tokens for API access
            $this->storeTokens($googleUser);
            
            // Redirect to frontend with token
            $frontendUrl = rtrim(env('FRONTEND_URL', 'https://luxeragift.netlify.app'), '/');
            return redirect(
                $frontendUrl . "/auth/callback?" . http_build_query([
                    'token' => $token,
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ])
            );
            
        } catch (\Exception $e) {
            Log::error('Google OAuth Error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect('/login')
                ->with('error', 'Failed to authenticate with Google. Please try again.');
        }
    }
    
    /**
     * Find or create user from Google data
     */
    protected function findOrCreateUser($googleUser)
    {
        // First try to find user by google_id
        $user = WebUser::where('google_id', $googleUser->id)->first();
        
        // If not found, try to find by email
        if (!$user && $googleUser->email) {
            $user = WebUser::where('email', $googleUser->email)->first();
            
            // If user exists but doesn't have google_id, update it
            if ($user) {
                $user->google_id = $googleUser->id;
                $user->save();
            }
        }
        
        if ($user) {
            // Update existing user
            $user->update([
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
            ]);
            return $user;
        }
        
        if (!$user) {
            $user = WebUser::create([
                'fullname' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => Hash::make(Str::random(24)),
                'email_verified_at' => now(),
            ]);
            
            Log::info('New user created via Google OAuth', [
                'id' => $user->id,
                'email' => $user->email,
                'google_id' => $user->google_id
            ]);
        }
        
        $user->update([
            'avatar' => $googleUser->avatar,
            'email_verified_at' => now(),
        ]);
        
        return $user;
    }
    
    /**
     * Store OAuth tokens for API access
     */
    protected function storeTokens($googleUser)
    {
        if (!Storage::exists('google')) {
            Storage::makeDirectory('google');
        }
        
        if ($googleUser->token) {
            Storage::put('google/oauth-token.json', $googleUser->token);
        }
        
        if (!empty($googleUser->refreshToken)) {
            Storage::put('google/oauth-refresh-token.json', $googleUser->refreshToken);
        }
    }
    
    /**
     * Refresh the access token
     */
    public function refreshToken(Request $request)
    {
        try {
            $refreshToken = Storage::get('google/oauth-refresh-token.json');
            
            if (!$refreshToken) {
                throw new \Exception('No refresh token available');
            }
            
            $http = new GuzzleClient();
            $response = $http->post('https://oauth2.googleapis.com/token', [
                'form_params' => [
                    'client_id' => config('services.google.client_id'),
                    'client_secret' => config('services.google.client_secret'),
                    'refresh_token' => $refreshToken,
                    'grant_type' => 'refresh_token',
                ],
            ]);
            
            $tokens = json_decode((string) $response->getBody(), true);
            
            if (isset($tokens['access_token'])) {
                Storage::put('google/oauth-token.json', $tokens['access_token']);
                
                if (isset($tokens['refresh_token'])) {
                    Storage::put('google/oauth-refresh-token.json', $tokens['refresh_token']);
                }
                
                return response()->json(['success' => true]);
            }
            
            throw new \Exception('Failed to refresh token');
            
        } catch (\Exception $e) {
            Log::error('Token refresh failed: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            return response()->json([
                'error' => 'Failed to refresh token',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
