<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $user->load('customer');

        return view('admin.profile.edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (! empty($validated['password']) && ! empty($validated['current_password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        if ($user->customer) {
            $user->customer->update($request->only('phone', 'address', 'city'));
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => route('admin.profile.edit'),
                'message' => 'Perfil actualizado correctamente.',
            ]);
        }
        return redirect()->route('admin.profile.edit')->with('success', 'Perfil actualizado correctamente.');
    }
}
