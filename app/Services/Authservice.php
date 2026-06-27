<?php

namespace App\Services;

use App\Http\Requests\Loginrqt;
use App\Http\Requests\Searchrqt;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Authservice
{

    public function loginauth(Loginrqt $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();
        $token = $user->createToken('token')->plainTextToken;
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }
        $user->update(['api_token' => $token]);

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function searchuser(Searchrqt $request)
    {
        $user = User::where('email', $request->email)->first();
        return response()->json($user);
    }
    public function logout(Searchrqt $request)
    {
        $user = User::where('email', $request->email)->first();
        $user->tokens()->delete();
        $user->update(['api_token' => null]);
        return response()->json(['ok' => true]);
    }
}
