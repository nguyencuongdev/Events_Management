<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

use App\Models\Organizer;

class AuthController extends Controller
{

    public static function checkLoginted($request)
    {
        $organizer = json_decode($request->cookie('currentUser'));
        return $organizer;
    }

    public function showLogin(Request $request)
    {
        try {
            $checkLogin = AuthController::checkLoginted($request);
            if ($checkLogin) return redirect('/');
            return view('auth.login');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return view('error.500');
        }
    }

    public function handleLogin(Request $request)
    {
        $request->validate(
            [
                'email' => ['required', 'string', 'email', 'max:255'],
                'password' => ['required', 'string', 'min:6', 'max:255'],
            ],
            [
                'required' => ':attribute không được để trống!',
                'string' => ':attribute không hợp lệ!',
                'email' => ':attribute không hợp lệ!',
                'max' => ':attribute vượt quá kích thước cho phép!',
                'min' => ':attribute phải tối thiểu 6 ký trở lên!',
            ],
            [
                'email' => 'Email',
                'password' => 'Mật khẩu'
            ]
        );
        try {
            $email = $request->input('email');
            $password = $request->input('password');
            $organizer = Organizer::getInfor($email, $password);
            if (!$organizer)
                return redirect()->back()->withErrors(['message' => 'Email hoặc mật khẩu không hợp lệ!']);

            Cookie::queue('currentUser', json_encode($organizer));
            return redirect('/');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return view('error.500');
        }
    }

    public function handleLogout()
    {
        try {
            Cookie::queue(Cookie::forget('currentUser'));
            return redirect('/login');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return view('error.500');
        }
    }
}
