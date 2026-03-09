<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle avatar upload via base64
        if ($request->filled('avatar_base64')) {
            $base64 = $request->input('avatar_base64');

            // Extract the base64 data
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                $base64 = substr($base64, strpos($base64, ',') + 1);
                $type = strtolower($type[1]); // e.g., webp, png, jpeg

                // Allow only certain types just to be safe (already handled by cropper mostly)
                if (in_array($type, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $base64 = base64_decode($base64);

                    if ($base64 !== false) {
                        // Generate unique filename
                        $fileName = 'avatars/' . \Illuminate\Support\Str::random(40) . '.' . $type;

                        // Delete old avatar if exists
                        if ($request->user()->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($request->user()->avatar)) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($request->user()->avatar);
                        }

                        // Save the new image
                        \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $base64);

                        $data['avatar'] = $fileName;
                    }
                }
            }
        }

        $request->user()->fill($data);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
