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
            'phone_number' => 'nullable|string',
            'password' => 'required|string|min:6',
        ]);

        if (empty($data['email']) && empty($data['phone_number'])) {
            return response()->json([
                'message' => 'Email or phone_number is required.',
            ], 422);
        }

        // For now, support email login (WebUser has no phone_number field yet)
        if (!empty($data['phone_number'])) {
            return response()->json([
                'message' => 'Phone login not supported yet. Please use email.',
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

        // Optional token for frontend localStorage (not used by backend auth here)
        $token = Str::random(60);

        return response()->json([
            'message' => 'Logged in successfully.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'surname' => $user->surname,
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * POST /logout
     */
    public function logout(Request $request)
    {
        Auth::guard('webuser')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    /**
     * GET /me - returns the authenticated web user
     */
    public function me(Request $request)
    {
        $user = Auth::guard('webuser')->user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        return response()->json([
            'data' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'surname' => $user->surname,
                'email' => $user->email,
            ],
        ]);
    }
}
