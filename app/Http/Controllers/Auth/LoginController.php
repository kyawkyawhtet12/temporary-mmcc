<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
        $this->middleware('guest:agent')->except('logout');
    }

    // Admin

    public function showAdminLoginForm()
    {
        return view('auth.login');
    }

    public function adminLogin(Request $request)
    {
        // return "Admin Login";
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            return redirect()->intended(route('dashboard.index'))->with('status', 'You are Logged in as Admin!');
        }
        return back()->withInput($request->only('email', 'remember'));
    }

    // Agent

    public function showAgentLoginForm()
    {
        return view('auth.login', ['url' => 'agent']);
    }

    public function agentLogin(Request $request)
    {
        // return "Agent Login";
        $this->validate($request, [
            // 'phone'   => 'required|phone:MM',
            'password' => 'required'
        ]);

        if (Auth::guard('agent')->attempt(['phone' => $request->phone, 'password' => $request->password], $request->get('remember'))) {
            // return "agent";
            return redirect()->intended(route('agent.dashboard'))->with('status', 'You are Logged in as Agent!');
            ;
        }
        return back()->withInput($request->only('phone', 'remember'));
    }
}
