<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StoreLoginController extends Controller
{
    public function __invoke(LoginRequest $request): RedirectResponse
    {
        if (! Auth::attempt($request->credentials())) {
            throw ValidationException::withMessages([
                'email' => 'ایمیل یا رمز عبور درست نیست.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('leads.index'));
    }
}
