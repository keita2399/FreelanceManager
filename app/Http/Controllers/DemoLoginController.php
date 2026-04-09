<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DemoLoginController extends Controller
{
    public function login(): RedirectResponse
    {
        $demo = User::where('email', 'demo@example.com')->first();

        if (!$demo) {
            return redirect()->route('login')->withErrors(['email' => 'デモアカウントが見つかりません。']);
        }

        Auth::login($demo);
        request()->session()->regenerate();

        return redirect()->route('dashboard');
    }
}
