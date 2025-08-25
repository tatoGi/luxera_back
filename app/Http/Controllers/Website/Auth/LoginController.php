<?php

namespace App\Http\Controllers\Website\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('website.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('webuser')->attempt($credentials)) {
            // Authentication passed...
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => Auth::guard('webuser')->user()
            ], 200);
        }

        // Authentication failed...
        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password. Please try again.',
            'errors' => ['email' => ['Invalid email or password. Please try again.']]
        ], 401);
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
