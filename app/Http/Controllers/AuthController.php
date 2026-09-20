<?php

namespace App\Http\Controllers;

use App\Models\BusinessCategory;
use App\Models\User;
use App\Models\Business;
use App\Constants\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Pages
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        $categories = BusinessCategory::orderBy('name')->get();

        return view('auth.register', compact('categories'));
    }

    public function showLogin()
    {
        return view('auth.login');
    }


    /*
    |--------------------------------------------------------------------------
    | Register
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:user,business_owner',
            'business_name' => 'required_if:role,business_owner',
            'category_id' => 'exclude_unless:role,business_owner|required|exists:business_categories,id'

        ]);
        DB::beginTransaction();
        try {

            $user = User::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password_hash' =>  Hash::make($request->password),
                'role' => $request->role,
                'created_at' => now(),
            ]);
            if ($request->role === Role::BUSINESS_OWNER) {
                Business::create([
                    'owner_id' => $user->id,
                    'name' => $request->business_name,
                    'category_id' => $request->category_id,
                    'description' => null,
                    'address' => null,
                    'latitude' => null,
                    'longitude' => null,
                    'phone' => null,
                    'working_hours' => null,
                    'price_level' => null,
                    'status' => 'pending',
                    'created_at' => now(),
                ]);
            }
            DB::commit();
            Auth::login($user);
            return $request->role === Role::BUSINESS_OWNER
                ? redirect()->route('owner.dashboard')
                : redirect()->route('my_profile.show');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong']);
        }
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = auth()->user();

            // 🔥 Redirect based on role
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->isBusinessOwner()) {
                return redirect()->route('owner.dashboard');
            }

            return redirect()->route('my_profile.show');
        }

        return back()->withErrors([
            'email' => 'Email Or Password not Valid, please try again',
        ]);
    }
    public function editProfile()
    {
        return view('profile.edit');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,

            'password' => 'nullable|min:8|confirmed',
        ]);


        $user->full_name = $request->full_name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password_hash = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
