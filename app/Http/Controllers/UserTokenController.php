<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Hash;

class UserTokenController extends Controller
{
    public function __invoke(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'El email no existe o no coincide con nuestros datos.'
            ]);
        } else if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'La contraseña es inválida.'
            ]);
        }

        return response()->json([
            'token' => $user->createToken($request->input('device_name'))->plainTextToken
        ]);
    }
}
