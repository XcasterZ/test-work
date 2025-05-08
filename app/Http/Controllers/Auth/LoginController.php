<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $credentials['username'])->first();

        if ($user && $user->password === $credentials['password']) {
            $token = $user->createToken('pornsawan')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'password' => $user->password, 
                ],
                'token' => $token, 
            ]);
        }

        return response()->json([
            'message' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง',
        ], 401);
    }


    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();
        
        return response()->json(['message' => 'Logged out successfully']);
    }
}
