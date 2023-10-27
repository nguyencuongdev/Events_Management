<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Room;
use App\Models\Session;

class ReportController extends Controller
{
    public function index(Request $request, $slug)
    {
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');

        $infor_event = Event::getInforEvent($currentUser->id, $slug);
        $list_sessions = Session::getSessionsOfEvent($infor_event->id);
        $list_id_room = array();
        foreach ($list_sessions as $session) {
            array_push($list_id_room, $session->room_id);
        }
        $list_id_room = array_unique($list_id_room);
        $capacity_rooms = Session::getCapacityRoomOfSession($list_id_room);
        $list_count_attendee = Session::getCountAttendeeSession($list_id_room);
        return view('report.index', [
            'currentUser' => $currentUser,
            'infor_event' => $infor_event,
            'capacity_rooms' => $capacity_rooms,
            'list_count_attendee' => $list_count_attendee,
            'list_sessions' => $list_sessions
        ]);
    }
}
