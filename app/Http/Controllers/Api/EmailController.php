<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\RegistrationEmail;

class EmailController extends Controller
{
    /**
     * Send registration email to user
     */
    public function sendRegistrationEmail(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
            'language' => 'nullable|string|in:en,ka',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $email = $request->input('email');
            $name = $request->input('name', 'User');
            $language = $request->input('language', 'en');

            // Send registration email
            Mail::to($email)->send(new RegistrationEmail($name, $language));

            return response()->json([
                'success' => true,
                'message' => 'Registration email sent successfully!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send registration email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send welcome email after successful registration
     */
    public function sendWelcomeEmail(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'name' => 'required|string|max:255',
            'language' => 'nullable|string|in:en,ka',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $email = $request->input('email');
            $name = $request->input('name');
            $language = $request->input('language', 'en');

            // Send welcome email
            Mail::to($email)->send(new \App\Mail\WelcomeEmail($name, $language));

            return response()->json([
                'success' => true,
                'message' => 'Welcome email sent successfully!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send welcome email: ' . $e->getMessage()
            ], 500);
        }
    }
}
