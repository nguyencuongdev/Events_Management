<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Organizers;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{

    public function login()
    {
        return view('auth.login', [
            "error" => ""
        ]);
    }

    public function handleLogin(Request $request)
    {
        try {
            $email = $request->input('email');
            $password = $request->input('password');
            if (!$email || !$password) {
                return view('auth.login', [
                    "error" => "Email hoặc mật khẩu không được để trống!"
                ]);
            }
            $check_infor = Organizers::getInforOrganizer($email);
            if (!$check_infor) {
                return view('auth.login', [
                    "error" => "Tên đăng nhập hoặc mật khẩu không chính xác!"
                ]);
            }
            if ($check_infor->password_hash == $password) {
                $currentUser = [
                    "id" => $check_infor->id,
                    "name" => $check_infor->name,
                    "slug" => $check_infor->slug,
                    "email" => $check_infor->email
                ];
                Cookie::queue('currentUser', json_encode($currentUser));
                return redirect('/');
            }
            return view('auth.login', [
                'error' => 'Tên đăng nhập hoặc mật khẩu không chính xác!'
            ]);
        } catch (Exception $ex) {
        }
    }


    function handleLogout()
    {
        //xóa cookie và điều hường về trang login; 
        //xóa cookie currentUser và giữ nguyên các cookie khác;
        Cookie::queue(Cookie::forget('currentUser'));
        return redirect('/login');
    }
}
