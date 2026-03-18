<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::orderBy('name');
        $search = $request->filled('search') ? trim($request->input('search')) : null;
        if ($search !== null && $search !== '') {
            $term = '%'.$search.'%';
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(name) LIKE LOWER(?)', [$term])
                    ->orWhereRaw('LOWER(email) LIKE LOWER(?)', [$term]);
            });
        }
        $users = $query->paginate(10)->withQueryString();
        $users->setPath(route('admin.users.index'));

        return view('admin.users.index', compact('users', 'search'));
    }

    public function editForm(User $user)
    {
        $this->authorize('update', $user);

        return view('admin.users.partials.form-edit', compact('user'));
    }

    public function create()
    {
        $this->authorize('create', User::class);

        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => $request->boolean('is_admin'),
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => route('admin.users.index'),
                'message' => 'Usuario creado correctamente.',
            ]);
        }
        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_admin' => $request->boolean('is_admin'),
        ]);

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => route('admin.users.index'),
                'message' => 'Usuario actualizado correctamente.',
            ]);
        }
        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(Request $request, User $user)
    {
        $this->authorize('delete', $user);

        if ($user->id === 1) {
            $msg = 'No se puede eliminar el usuario con id 1.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->route('admin.users.index')->with('error', $msg);
        }

        $user->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => route('admin.users.index'),
                'message' => 'Usuario eliminado correctamente.',
            ]);
        }
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
