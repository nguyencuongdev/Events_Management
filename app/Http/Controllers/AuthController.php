<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendees;
use Illuminate\Support\Facades\Cookie;
// use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{

    public function login(){
        return view('auth.login',[
            "error" => ""
        ]);
    }

    public function handleLogin(Request $request){
        try{
            $email = $request->input('email');
            $password = $request->input('password');
            if(!$email || !$password) {
                return view('auth.login',[
                    "error" => "Email hoặc mật khẩu không được để trống!"
                ]);
            }
            $check = \DB::table('organizers')
                        ->where('email','=',$email)
                        ->first();
            if(!$check){
                return view('auth.login',[
                    "error" => "Email không chính xác!"
                ]);
            }
            if($check->password_hash == $password){
                $currentUser = [
                    "id" => $check->id,
                    "name" => $check->name,
                    "slug" => $check->slug,
                    "email" => $check->email
                ];
                Cookie::queue('currentUser', \json_encode($currentUser));
                return redirect('/');
            }
            else{
                 return view('auth.login',[
                    'error'=> 'Mật khẩu không chính xác!'
                ]);
            }
        }
        catch(Exception $ex){
            echo 'Error: ' . $ex->getMessage;
        }
    }


    function handleLogout(Request $request){
        //xóa cookie và điều hường về trang login; 
        //xóa cookie currentUser và giữ nguyên các cookie khác;
        Cookie::queue(Cookie::forget('currentUser'));
        return redirect('/login');
    }
}
