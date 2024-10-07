<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    public function index(){
        $title = 'Login';
        return view('user.login', compact('title'));
    }

    public function form(){
        return view ('user.login');
    }

    public function login(Request $request){
        // validate request
        $request->validate([
            'email' =>'required|email',
            'password' => 'required|min:8',
        ]);

        // get user data from database
        $user = User::where('email', request('email'))->first();

        // check if user exists and password is correct
        if($user && Hash::check(request('password'), $user->password)){
            // create a new session
            Auth::login($user);

            // redirect to home page
            return redirect()->route('home');
        } else {
            // if login fails, redirect back to login page with error message
            return redirect()->route('login.form')->with('error', 'Invalid email or password');
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('home');
    }
}
