<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Mail\Message;
use Illuminate\Support\Str;
use Carbon\Carbon;
class PasswordResetController extends Controller
{
    public function send_reset_password_email(Request $request){
        $request->validate([
            'email'=>'required|email',
        ]);
        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response([
                'message'=>"User Dosn't Exist",
                'status'=>"Failed"
            ], 404);
        }
        //Generate Token
        $token = Str::random(60);
        //saving data to password reset.
        PasswordReset::create([
            'email'=> $request->email,
            'token'=> $token,
            'created_at'=> Carbon::now()
        ]);
        dump("http://127.0.0.1:8000/api/user/reset" . $token);
        //sending email with password View;
        return response([
            'message'=>'Reset Link send your mail successfully',
            'status'=>'success'
        ], 200);
    }
}
