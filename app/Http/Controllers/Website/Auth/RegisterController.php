<?php

namespace App\Http\Controllers\Website\Auth;

use App\Http\Controllers\Controller;
use App\Models\WebUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{

    public function register(Request $request)
    {
        
        try {
            $request->validate([
                'fullname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:web_users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Split fullname into first name and surname
            $nameParts = explode(' ', trim($request->fullname), 2);
            $firstName = $nameParts[0];
            $surname = $nameParts[1] ?? ''; // In case only one name is provided

            $user = WebUser::create([
                'first_name' => $firstName,
                'surname' => $surname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Auth::guard('webuser')->login($user);

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'user' => $user,
                'redirect' => '/'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
