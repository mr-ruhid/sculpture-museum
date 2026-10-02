<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile.index', ['user' => auth()->user()]);
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
        ]);

        $user = auth()->user();

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Cari şifrə yanlışdır.']);
        }

        $user->password = $data['password'];
        $user->save();

        return back()->with('success', 'Şifrə yeniləndi.');
    }

    public function updateTwoFactor(Request $request)
    {
        $user = auth()->user();
        $user->two_factor_enabled = $request->boolean('two_factor_enabled');
        $user->save();

        return back()->with('success', '2FA ayarı yeniləndi.');
    }
}
