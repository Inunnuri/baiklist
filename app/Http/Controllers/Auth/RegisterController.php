<?php

namespace App\Http\Controllers\Auth;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function form(){
        $title = 'Register';
        return view('user.register', compact('title'));
    }

    public function register(Request $request){
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());
       /** @var \Illuminate\Auth\AuthManager $auth */
        $auth = auth();
        $auth->login($user);
        return redirect()->route('home');
    }

    protected function validator(array $data){
        return Validator::make($data, [
            'username' => ['required','max:255', 'regex:/^[a-zA-Z0-9]+$/'],
            'name' => ['required','string','max:255'],
            'email' => ['required','string', 'email','max:255', 'unique:users'],
            'password' => ['required','string','min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data){
        return User::create([
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    //done
}
