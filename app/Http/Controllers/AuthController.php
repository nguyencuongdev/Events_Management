<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\Organizer;
use App\Models\Attendee;

class AuthController extends Controller
{

    //admin
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
            $check_infor = Organizer::getInforOrganizer($email);
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


    //Client
    function handleLoginClient(Request $request)
    {
        $username = $request->input('username');
        $registration_code = $request->input('registration_code');

        $user_infor = Attendee::getInfor($username);
        if (!$user_infor)
            return response()->json([
                'message' => 'Đăng nhập không hợp lê!'
            ], 401);

        if ($user_infor &&  $user_infor->registration_code != $registration_code) {
            return response()->json([
                'message' => 'Đăng nhập không hợp lê!'
            ], 401);
        }

        $login_token = md5($user_infor->username);
        Attendee::updateInforLoginToken($username, $login_token);
        return response()->json([
            'firstname' => $user_infor->firstname,
            'lastname' => $user_infor->lastname,
            'username' => $user_infor->username,
            'email' => $user_infor->email,
            'token' => $login_token
        ], 200)
            ->withCookie('login_token', $login_token);
    }

    public static function verifyToken($username, $token)
    {
        $data = Attendee::getLoginTokenAttendee($username);
        if ($data->login_token === $token)
            return true;
        return false;
    }

    function handleLogoutClient(Request $request)
    {
        $jsonData = $request->json()->all();
        $token = $request->input('token');

        $username = $jsonData['username'];
        $checkToken = $this->verifyToken($username, $token);
        if ($checkToken) {
            Attendee::updateInforLoginToken($username, '');
            return response()->json([
                'message' => 'Đăng xuất thành công'
            ], 200)
                ->withCookie(cookie('login_token', null, -1));
        }

        return response()->json([
            'message' => 'Token không hợp lệ'
        ], 401);
    }
}
