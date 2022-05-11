<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
      /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $authOK = Auth::guard('admin')->attempt($credentials, $request->remember);

        if($authOK)
            return redirect()->intended(route('admin'));

        return back()->withInputs($request->only('email', 'remember'))
        ->withErrors([
            'email' => 'O e-mail informado pode estar incorreto.',
            'password' => 'A senha informada pode estar incorreta.',
        ]);

    }

    public function index()
    {
        return view("auth.admin-login");
    }

}
