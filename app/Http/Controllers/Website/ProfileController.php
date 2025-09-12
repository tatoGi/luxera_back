<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\WebUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::guard('webuser')->user();
        if (!$user instanceof WebUser) {
            return redirect()->route('login');
        }

        return view('website.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::guard('webuser')->user();
        if (!$user instanceof WebUser) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'surname' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:web_users,email,' . ($user?->id ?? 'NULL'),
            'phone' => 'nullable|string|max:32',
            'personal_id' => 'nullable|string|max:50',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        /** @var WebUser $user */
        $user->first_name = $validated['first_name'];
        $user->surname = $validated['surname'] ?? null;
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->personal_id = $validated['personal_id'] ?? null;
        $user->birth_date = $validated['birth_date'] ?? null;
        $user->gender = $validated['gender'] ?? null;
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Profile updated successfully.',
                'data' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'surname' => $user->surname,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'personal_id' => $user->personal_id,
                    'birth_date' => $user->birth_date?->toDateString(),
                    'gender' => $user->gender,
                    'is_retailer' => $user->is_retailer,
                    'retailer_status' => $user->retailer_status,
                    'retailer_requested_at' => $user->retailer_requested_at,
                    'avatar' => $user->avatar,
                    'avatar_url' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                ],
            ]);
        }

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function me(Request $request)
    {
        // Get authenticated user via Sanctum (token-based)
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'user not found.',
            ], 401);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'email_verified' => $user->hasVerifiedEmail(),
                'phone' => $user->phone,
                'personal_id' => $user->personal_id,
                'birth_date' => $user->birth_date?->toDateString(),
                'gender' => $user->gender,
                'avatar' => $user->avatar,
                'avatar_url' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                'email_verified_at' => $user->email_verified_at?->toISOString(),
                'created_at' => $user->created_at?->toISOString(),
                'updated_at' => $user->updated_at?->toISOString(),
            ]
        ]);
    }
    /**
     * POST /profile/retailer-request
     * Marks the user's retailer status as pending and sets requested_at.
     */
    public function requestRetailer(Request $request)
    {
        $user = Auth::guard('webuser')->user();
        if (!$user instanceof WebUser) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user->retailer_status = 'pending';
        $user->retailer_requested_at = now();
        $user->save();

        return response()->json([
            'message' => 'Retailer request submitted.',
            'data' => [
                'retailer_status' => $user->retailer_status,
                'retailer_requested_at' => $user->retailer_requested_at,
            ],
        ]);
    }

    /**
     * POST /profile/avatar
     * Accepts multipart/form-data with 'avatar' image file and stores it to public disk.
     */
    public function uploadAvatar(Request $request)
    {
        $user = Auth::guard('webuser')->user();
        if (!$user instanceof WebUser) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $data = $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:4096',
        ]);

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $file = $request->file('avatar');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('avatars', $filename, 'public');

        $user->avatar = $path;
        $user->save();

        return response()->json([
            'message' => 'Avatar updated successfully.',
            'data' => [
                'avatar' => $user->avatar,
                'avatar_url' => asset('storage/' . $user->avatar),
            ],
        ], 200);
    }
}

