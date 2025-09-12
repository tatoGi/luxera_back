<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\WebUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * POST /login
     * Body: { email?: string, phone_number?: string (not supported yet), password: string }
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'nullable|email',
            'password' => 'required|string|min:6',
        ]);
    
        if (empty($data['email'])) {
            return response()->json([
                'message' => 'Email or phone_number is required.',
            ], 422);
        }
    
        $user = WebUser::where('email', $data['email'] ?? '')->first();
    
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }
    
        // Log the user in using the webuser guard (session-based)
        Auth::guard('webuser')->login($user, true);
        $request->session()->regenerate();
    
        // Create Sanctum token for API authentication
        $token = $user->createToken('auth-token')->plainTextToken;
    
        return response()->json([
            'message' => 'Logged in successfully.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'fullname' => $user->fullname,
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * POST /logout
     */
    public function logout(Request $request)
    {
        // For API routes with Sanctum tokens
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'message' => 'Logged out successfully.',
            ]);
        }

        // For web routes with sessions
        Auth::guard('webuser')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

}
