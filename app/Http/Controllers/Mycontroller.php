<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\Mymodel;
use Illuminate\Support\Facades\DB;

class MyController extends Controller
{
    //registration
    public function registration(Request $reg){
        $reg->validate([
            'name' => ['required', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'

        ]);
        $user = new MyModel;
        $user->name = $reg['name'];
        $user->email = $reg['email'];
        $user->password =  Hash::make($reg['password']);
        $user->save();
          //Login after registration
      if (Auth::attempt($reg->only('email', 'password'))) {
        // Authentication passed
        return redirect()->route('dashboard');
    }

}

//login
public function login(Request $request){
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8'
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        // Authentication passed
        return redirect()->route('dashboard');
    }

    // Authentication failed
    return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput();
}

//dashboard
public function dashboard(){
    $user = Auth::user();
        return view('dashboard', compact('user'));
}

//logout
public function logout(){
    Session::flush();
    Auth::logout();
    return redirect('login');
}
}
