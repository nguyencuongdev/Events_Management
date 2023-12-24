<?php

namespace App\Http\Controllers\api\v1;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

use App\Models\Attendee;
use App\Models\EventTicket;
use App\Models\Registration;
use App\Models\Organizer;
use App\Models\Event;
use App\Models\SessionRegistration;

class RegistrationController extends Controller
{
    //

    public function registrationEvent(Request $request, $onganiser_slug, $event_slug)
    {
        try {
            $token = $request->input('token') ?? '';
            $ticket_id = $request->input('ticket_id');
            $session_ids = $request->input('session_ids') ?? [];

            $attendee = Attendee::getInforByToken($token);
            if (!$attendee)
                return response()->json([
                    'message' => 'Người dùng chưa đăng nhập'
                ], 401);

            //check nhà tổ chức và sự kiện có tồn tại không ?
            //từ đó sẽ check được vé;
            $infor_organizer = Organizer::where('slug', $onganiser_slug)->first();
            $organizer_id = $infor_organizer->id ?? -1;
            $infor_event = Event::getInfor($organizer_id, $event_slug);
            $event_id = $infor_event->id ?? -1;
            $check_ticket = EventTicket::verifyTicket($ticket_id, $event_id);

            if (!$check_ticket)
                return response()->json([
                    'message' => 'Vé không có sẵn'
                ], 401);

            $check_registed = Registration::where([
                ['ticket_id', '=', $ticket_id],
                ['attendee_id', '=', $attendee->id]
            ])->first();

            if ($check_registed)
                return response()->json([
                    'message' => 'Người dùng đã đăng ký'
                ], 401);

            $id_registration = Registration::registrationEvent($ticket_id, $attendee->id);
            SessionRegistration::registrationSession($id_registration, $session_ids);
            return response()->json([
                'message' => 'Đăng ký thành công'
            ], 200);
        } catch (Exception $ex) {
            Log::error('Api request error:' . $ex->getMessage());
            return response()->json([
                'message' => 'Server-side error 500!'
            ]);
        }
    }

    public function getSesionIDsRegisted($session_registrations)
    {
        $session_ids = [];
        foreach ($session_registrations as $session_registed) {
            $session_ids[] = $session_registed->session_id;
        }
        return $session_ids;
    }

    public function getRegistedEvents(Request $request)
    {
        try {
            $token = $request->input('token');
            $attendee = Attendee::getInforByToken($token);
            if (!$attendee)
                return response()->json([
                    'message' => 'Người dùng chưa đăng nhập'
                ], 401);

            $registeds = Registration::with('ticket.event.organizer', 'session_registrations')
                ->where('attendee_id', $attendee->id)
                ->get();
            $infor_events_registed = [];
            foreach ($registeds as $registed) {
                $session_ids = $this->getSesionIDsRegisted($registed->session_registrations);
                $infor_events_registed[] = [
                    'event' => $registed->ticket->event,
                    'session_ids' => $session_ids,
                ];
            }
            return response()->json([
                'registrations' => $infor_events_registed
            ], 200);
        } catch (Exception $ex) {
            Log::error('Api request error:' . $ex->getMessage());
            return response()->json([
                'message' => 'Server-side error 500!'
            ]);
        }
    }
}
