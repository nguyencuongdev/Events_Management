<?php

namespace App\Http\Controllers\api\v1;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

use App\Models\Attendee;

class AuthController extends Controller
{
    public function handleLoginClient(Request $request)
    {
        try {
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
        } catch (Exception $ex) {
            Log::error('Api request error:' . $ex->getMessage());
            return response()->json([
                'message' => 'Server-side error 500!'
            ]);
        }
    }

    public function handleLogoutClient(Request $request)
    {
        try {
            $token = $request->input('token');
            $check = Attendee::verifyToken($token);
            if (!$check)
                return response()->json([
                    'message' => 'Token không hợp lệ'
                ], 401);

            return response()->json([
                'message' => 'Đăng xuất thành công'
            ], 200);
        } catch (Exception $ex) {
            Log::error('Api request error:' . $ex->getMessage());
            return response()->json([
                'message' => 'Server-side error 500!'
            ]);
        }
    }
}
