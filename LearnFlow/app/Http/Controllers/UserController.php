<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
 

class UserController extends Controller
{

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request) 
    {
        $validated = $request->validate([
            "email" => 'required|string|email|max:50',
            "password" => 'required|string'
        ]);

        if (Auth::attempt($validated, $request->boolean('remember'))){
            $request->session()->regenerate();

            return $this->redirectByRole(Auth::user());
        }

        return back()->withInput($request->only('email'))->withErrors(['email' => 'Invalid email or password']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }


    public function register(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'visitor',
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return $this->redirectByRole($user)->with('success', 'welcome to our website!');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'you have logged out of your account');
    }

    public function dashboard()
    {
        $user = Auth::user();
 
        return match ($user->role) {
            'admin'      => view('admin.dashboard', compact('user')),
            'host'       => view('host.dashboard', compact('user')),
            'student'    => view('student.dashboard', compact('user')),
            'visitor'    => view('visitor.dashboard', compact('user')),
            default      => abort(403, 'Rôle non reconnu.'),
        };
    }
 

public function showProfile()
    {
        $user = Auth::user();
        $this->authorize('view', $user);
 
        return view('auth.profile', compact('user'));
    }
 
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $this->authorize('update', $user);
 
        $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'bio'    => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ]);
 
        $data = $request->only('name', 'email', 'bio');
 
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }
 
        $user->update($data);
 
        return back()->with('success', 'Profile updated successfully.');
    }
 
    public function showChangePassword()
    {
        return view('auth.change-password');
    }
 
    public function changePassword(Request $request)
    {
        $user = Auth::user();
 
        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);
 
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }
 
        $user->update([
            'password' => Hash::make($request->password),
        ]);
 
        Auth::logoutOtherDevices($request->password);
 
        return back()->with('success', 'Password changed successfully.');
    }
 
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::query();
 
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }
 
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }
 
        $users = $query->latest()->paginate(20)->withQueryString();
 
        return view('admin.users.index', compact('users'));
    }
 
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return view('admin.users.show', compact('user'));
    }
 
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $roles = ['visitor', 'student', 'host', 'admin'];
 
        return view('admin.users.edit', compact('user', 'roles'));
    }
 
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $user->update($request->validated());
 
        return redirect()->route('admin.users.index')
                         ->with('success', "User {$user->name} has been updated.");
    }
 
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
 
        $user->delete();
 
        return redirect()->route('admin.users.index')
                         ->with('success', 'User deleted successfully.');
    }
 
    private function redirectByRole(User $user)
    {
        return match ($user->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'host'      => redirect()->route('host.dashboard'),
            'student'   => redirect()->route('student.dashboard'),
            'visitor'   => redirect()->route('visitor.dashboard'),
            default     => redirect()->route('dashboard'),
        };
    }
}
