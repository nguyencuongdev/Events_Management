<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

use App\Models\Attendee;
use App\Models\Organizer;
use App\Models\Ticket;
use App\Models\Registration;
use App\Models\SessionRegistration;

class RegistrationEventController extends Controller
{
    public static function getRegistedEvents(Request $request)
    {
        $token = $request->input('token');
        $check_token = AuthController::verifyToken($token);
        if (!$check_token) {
            return response()->json([
                'message' => 'Người dùng chưa đăng nhập'
            ], 401);
        }
        $attendee_id = Attendee::getInforAttendeeByLoginToken($token)->id;
        $registed_ticket_list = Registration::getRegistedOfAttendee($attendee_id);
        $registed_ids = [];
        foreach ($registed_ticket_list as $registed) {
            $registed_ids[] = $registed->id;
        }

        $events_list = Registration::getEventRegistredOfAttendee($registed_ids);
        $event_ids = [];
        foreach ($events_list as $event) {
            $event_ids[] = $event->id;
        }
        $organizer_list = Organizer::getOrganizersByEventID($event_ids);
        $session_registed_list = SessionRegistration::getSessionRegisted($registed_ids);
        $infor_event = [
            'id' => '',
            'name' => '',
            'slug' => '',
            'date' => '',
            'organizer' => [],
            'session_ids' => [],
        ];

        $infor_organizer = [
            'name' => '',
            'slug' => ''
        ];

        $event_registed_list = [];
        foreach ($events_list as $event) {
            $infor_event['id'] = $event->id;
            $infor_event['name'] = $event->name;
            $infor_event['slug'] = $event->slug;
            $infor_event['date'] = $event->date;
            foreach ($organizer_list as $organizer) {
                if ($organizer->organizer_id == $event->organizer_id) {
                    $infor_organizer['name'] = $organizer->organizer_name;
                    $infor_organizer['slug'] = $organizer->organizer_slug;
                    $infor_event['organizer'] = $infor_organizer;
                }
            }

            foreach ($session_registed_list as $session) {
                if ($event->registration_id == $session->registration_id) {
                    $infor_event['session_ids'][] = $session->session_id;
                }
            }
            $event_registed_list[] = $infor_event;
        }

        return response()->json($event_registed_list, 200);
    }


    public static function handleRegistrationEvent(Request $request)
    {
        $token = $request->input('token');
        $data = $request->json()->all();
        $check_token = AuthController::verifyToken($token);

        if (!$check_token) {
            return response()->json([
                'message' => 'Người dùng chưa đăng nhập'
            ], 401);
        }

        $ticket_id = $data['ticket_id'] ?? null;
        $attendee_id = Attendee::getInforAttendeeByLoginToken($token)->id;
        $session_ids = $data['session_ids'] ?? [];
        $check_ticket = Ticket::verifyTicket($ticket_id, date('Y-m-d'));

        if (!$check_ticket)
            return response()->json([
                'message' => 'Vé không sẵn có'
            ], 401);

        $check_registed = Registration::checkRegistratedEvent($ticket_id, $attendee_id);
        if ($check_registed)
            return response()->json([
                'message' => 'Người dùng đã đăng ký',
            ], 401);

        Registration::registrationEvent($attendee_id, $ticket_id, date('Y-m-d'), $session_ids);
        return response()->json([
            'message' => 'Đăng ký thành công'
        ], 200);
    }
}
