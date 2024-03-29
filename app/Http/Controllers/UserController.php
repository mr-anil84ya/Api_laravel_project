<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required|confirmed',
            'tc'=>'required'
        ]);
        if(User::where('email', $request->email)->first()){
            return response([
                'message'=>'User Already exist',
                'status'=>'failed'
            ],200);
        }
        $user= User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'tc'=>json_decode($request->tc)
        ]);
        $token = $user->createToken($request->email)->plainTextToken;
        return response([
            'token'=>$token,
            'message'=>'Registration Successfully',
            'status'=>'Success'
        ],201);
    }

    public function login(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if($user && Hash::check($request->password, $user->password)){
            $token = $user->createToken($request->email)->plainTextToken;
            return response([
                'token'=>$token,
                'message'=>"login success",
                'status'=>"success"
            ],200);
        }
        return response([
            'message'=>"The Provide Crediantials is incorrect",
            'status'=>"failed"
        ],401);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            'message'=>'Logout Success',
            'status'=>'success'
        ], 200);
    }

    public function logged_User(){
        $userDetails= auth()->user();
        return response([
            'userDetails'=>$userDetails,
            'message'=>'LoggedIn Details Success',
            'status'=>'success'
        ], 200);
    }

    public function change_password(Request $request){
        $request->validate([
            'password'=>'required|confirmed',
        ]);
        $loggedUser= auth()->user();
        $loggedUser->password = Hash::make($request->password);
        $loggedUser->save();
        return response([
            'message'=>'Change Password Successfully',
            'status'=>'success'
        ], 200);

    }
}
