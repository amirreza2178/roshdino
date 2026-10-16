<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class ShowPasswordController extends Controller
{
    public function __invoke(): View
    {
        return view('auth.password');
    }
}
