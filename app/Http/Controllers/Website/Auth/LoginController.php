<?php

namespace App\Http\Controllers\Website\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WebUser;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('website.auth.login');
    }

    public function login(Request $request)
    {
   
        // Check if user is already authenticated via token
        if ($request->bearerToken()) {
            $tokenUser = $request->user('sanctum');
            if ($tokenUser) {
                // User is already authenticated via token, return current user data
                return response()->json([
                    'success' => true,
                    'message' => 'Already logged in',
                    'data' => [
                        'token' => $request->bearerToken(),
                        'token_type' => 'Bearer',
                        'user' => [
                            'id' => $tokenUser->id,
                            'fullname' => $tokenUser->fullname,
                            'email' => $tokenUser->email,
                            'email_verified' => true,
                           
                        ]
                    ]
                ]);
            }
        }

        // Check if this is a token-based login from registration
        if ($request->has('registration_token') && $request->has('user_id')) {
            $user = WebUser::find($request->input('user_id'));
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ], 404);
            }

            // Get the user's most recent token
            $token = $user->tokens()->latest()->first();
            
            if (!$token || !hash_equals($token->token, hash('sha256', $request->input('registration_token')))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired registration token.'
                ], 401);
            }
        } else {
            // Standard email/password login
            $data = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            $credentials = [
                'email' => $data['email'],
                'password' => $data['password']
            ];

            if (!Auth::guard('webuser')->attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid login credentials.'
                ], 401);
            }

            $user = Auth::guard('webuser')->user();
        }

        if (!$user instanceof WebUser) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }

        // Check if email is verified
        if (!$user->email_verified_at) {
            return response()->json([
                'message' => 'Please verify your email address before logging in.',
                'requires_verification' => true
            ], 403);
        }

        // Log the user in using the webuser guard (session-based)
        Auth::guard('webuser')->login($user, true);
        $request->session()->regenerate();

        // Create a new API token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'surname' => $user->surname,
                    'email' => $user->email,
                    'email_verified' => true
                ]
            ]
        ]);
    }

    public function logout()
    {
        Auth::guard('webuser')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ], 200);
    }
}
