<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class ShowLoginController extends Controller
{
    public function __invoke(): View
    {
        return view('auth.login');
    }
}
