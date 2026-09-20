<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OwnerProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();

        return view('owner.profile.edit', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->full_name = $validated['full_name'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password_hash = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
