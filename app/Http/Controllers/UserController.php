<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    //
    public function create()
    {
        return view('registration');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name'=>'required|string|max:20',
                'email'=>'required|string|unique:users,email',
                'phone'=>'required|numeric|digits:11',
                'password'=>'required|alpha_num|min:6',
                'confirm_password'=>'required|same:password'
            ]
        );

        $dataArray = array(
            "name"          =>          $request->name,
            "email"         =>          $request->email,
            "phone"         =>          $request->phone,
            "password"      =>          $request->password
        );

        $user = User::create($dataArray);
        Mail::to($dataArray['email'])->send(new WelcomeMail($user));
        if(!is_null($user)){
            return back()->with("success", "Success! Registration done, Check your mail");

        }
        else{
            return back()->with("failed", "Alert! Failed to register");
        }
    }
}
