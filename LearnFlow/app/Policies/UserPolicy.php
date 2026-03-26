<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}



?>


<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    // -------------------------------------------------------------------------
    // REGISTER
    // -------------------------------------------------------------------------

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration form submission.
     * New users are assigned the 'abonne' role by default.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'abonne', // default role
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Bienvenue ! Votre compte a été créé avec succès.');
    }

    // -------------------------------------------------------------------------
    // LOGIN
    // -------------------------------------------------------------------------

    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login form submission.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->redirectByRole(Auth::user());
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Les identifiants fournis sont incorrects.']);
    }

    // -------------------------------------------------------------------------
    // LOGOUT
    // -------------------------------------------------------------------------

    /**
     * Log the user out and invalidate the session.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Vous avez été déconnecté avec succès.');
    }

    // -------------------------------------------------------------------------
    // DASHBOARD  (role-aware redirect after login)
    // -------------------------------------------------------------------------

    /**
     * Show the appropriate dashboard based on the authenticated user's role.
     */
    public function dashboard()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin'      => view('admin.dashboard', compact('user')),
            'formateur'  => view('formateur.dashboard', compact('user')),
            'abonne'     => view('abonne.dashboard', compact('user')),
            default      => abort(403, 'Rôle non reconnu.'),
        };
    }

    // -------------------------------------------------------------------------
    // PROFILE
    // -------------------------------------------------------------------------

    /**
     * Show the authenticated user's profile page.
     */
    public function showProfile()
    {
        $user = Auth::user();

        return view('auth.profile', compact('user'));
    }

    /**
     * Update the authenticated user's profile information.
     */
    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'bio'   => ['nullable', 'string', 'max:1000'],
            'avatar'=> ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = $request->only('name', 'email', 'bio');

        // Handle avatar upload (stored locally — no external packages)
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    // -------------------------------------------------------------------------
    // CHANGE PASSWORD
    // -------------------------------------------------------------------------

    /**
     * Show the change-password form.
     */
    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    /**
     * Update the authenticated user's password.
     */
    public function changePassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Log the user out of all other sessions (requires database sessions driver)
        Auth::logoutOtherDevices($request->password);

        return back()->with('success', 'Mot de passe modifié avec succès.');
    }

    // -------------------------------------------------------------------------
    // ADMIN — User management
    // -------------------------------------------------------------------------

    /**
     * List all users (admin only — protect via middleware in routes).
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Optional search
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        // Optional role filter
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show a single user's details (admin only).
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show form to edit a user's role / status (admin only).
     */
    public function edit(User $user)
    {
        $roles = ['visiteur', 'abonne', 'formateur', 'admin'];

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update a user's role or status (admin only).
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role'      => ['required', 'in:visiteur,abonne,formateur,admin'],
            'is_active' => ['boolean'],
        ]);

        $user->update($request->only('role', 'is_active'));

        return redirect()->route('admin.users.index')
                         ->with('success', "L'utilisateur {$user->name} a été mis à jour.");
    }

    /**
     * Delete a user (admin only).
     * Prevents self-deletion.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Utilisateur supprimé avec succès.');
    }

    // -------------------------------------------------------------------------
    // PRIVATE HELPERS
    // -------------------------------------------------------------------------

    /**
     * Redirect the user to their role-specific dashboard after login.
     */
    private function redirectByRole(User $user)
    {
        return match ($user->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'formateur' => redirect()->route('formateur.dashboard'),
            'abonne'    => redirect()->route('abonne.dashboard'),
            default     => redirect()->route('home'),
        };
    }
}