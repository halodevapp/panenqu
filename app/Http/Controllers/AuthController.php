<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return view('auth.login');
    }

    public function authenticate(AuthenticateRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                throw new Exception("User tidak ditemukan");
            }

            if ($user->is_active == User::USER_INACTIVE) {
                throw new Exception("User sudah non aktif");
            }

            if (!Hash::check($request->password, $user->password)) {
                throw new Exception("Password anda salah");
            }

            $remember = $request->has('remember') ? true : false;

            Auth::login($user, $remember);

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        } catch (Exception $exception) {

            return redirect(route("login"))
                ->with("response-message", $exception->getMessage())
                ->with("response-status", "error");
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect(route('login'));
    }
}
