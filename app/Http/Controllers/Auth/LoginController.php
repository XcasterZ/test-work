<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * แสดงฟอร์มล็อกอิน
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * ประมวลผลการล็อกอิน (ใช้รหัสผ่าน plain text)
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // ดึง user จากฐานข้อมูลตาม username
        $user = User::where('username', $credentials['username'])->first();

        // ตรวจสอบว่ามี user และรหัสผ่านตรงกัน (plain text)
        if ($user && $user->password === $credentials['password']) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'username' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง',
        ])->onlyInput('username');
    }

    /**
     * ออกจากระบบ
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
