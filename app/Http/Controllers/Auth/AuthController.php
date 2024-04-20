<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function registration()
    {
        return view('auth.registration');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();
            
            Session::put('user_name', $user->name);


            if ($user) {
                $user->status = 1;
                $user->login_time = Carbon::now();
                $user->save();
            }


            session()->flash('success', [
                'title' => 'Welcome Back!',
                'text' => 'You have successfully logged in!',
                'icon' => 'success',
            ]);
            return redirect()->intended('dashboard');
        }

        return redirect("login")->with('error', 'Oops! You have entered invalid credentials');
    }


    public function postRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = $this->createUser($request->all());

        Auth::login($user);

        return redirect("dashboard")->withSuccess('Great! You have Successfully registered and logged in');
    }

    public function dashboard()
    {
        if (Auth::check()) {
            return view('dashboard');
        }

        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();

        return redirect('login');
    }

    protected function createUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }
}
