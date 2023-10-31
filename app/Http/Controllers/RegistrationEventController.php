<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

use App\Models\Registration;
use App\Models\Ticket;
use App\Models\Attendee;


class RegistrationEventController extends Controller
{
    public static function getRegistedEvents(Request $request)
    {
    }
    public static function handleRegistrationEvent(Request $request)
    {
        $token = $request->input('token');
        $data = $request->json()->all();
        $username = $data['username'] ?? "";
        $check_token = AuthController::verifyToken($username, $token);

        if (!$check_token) {
            return response()->json([
                'message' => 'Người dùng chưa đăng nhập'
            ], 401);
        }
        $ticket_id = $data['ticket_id'] ?? null;
        $attendee_id = Attendee::getInfor($username)->id;
        $session_ids = $data['session_ids'] ?? [];
        // $registration_time = $data['registration_time'];
        $check_registed = Registration::checkRegistratedEvent($ticket_id, $attendee_id);
        if ($check_registed)
            return response()->json([
                'message' => 'Người dùng đã đăng ký',
            ], 401);

        $check_ticket = Ticket::verifyTicket($data['ticket_id'], date('Y-m-d'));
        if (!$check_ticket)
            return response()->json([
                'message' => 'Vé không sẵn có'
            ], 401);

        Registration::registrationEvent($attendee_id, $ticket_id, date('Y-m-d'), $session_ids);
        return response()->json([
            'message' => 'Đăng ký thành công'
        ], 200);
    }
}
