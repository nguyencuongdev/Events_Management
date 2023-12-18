<?php

namespace App\Http\Controllers\api\v1;

use App\Models\Attendee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function handleLoginClient(Request $request)
    {
        $lastname = $request->input('lastname');
        $registration_code  = $request->input('registration_code');
        $attendee = Attendee::getInfor($lastname, $registration_code);
        if (!$attendee)
            return response()->json([
                'message' => 'Đăng nhập không hợp lệ'
            ], 401);
        $token = Attendee::generateToken($attendee, $attendee->username);
        return response()->json([
            'firstname' => $attendee->firstname,
            'lastname' => $attendee->lastname,
            'username' => $attendee->username,
            'email' => $attendee->email,
            'token' => $token
        ], 200);
    }

    public function handleLogoutClient(Request $request)
    {
        $token = $request->input('token');
        $check = Attendee::verifyToken($token);
        if (!$check)
            return response()->json([
                'message' => 'Token không hợp lệ'
            ], 401);

        return response()->json([
            'message' => 'Đăng xuất thành công'
        ], 200);
    }
}
