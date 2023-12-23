<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Room;

use App\Models\Event;

use function PHPSTORM_META\map;

class ReportController extends Controller
{
    public function reportCapacityRoom(Request $request, $slug)
    {
        $organizer = AuthController::checkLoginted($request);
        if (!$organizer) return redirect('/login');

        $event = Event::getInfor($organizer->id, $slug);
        $roomsOfEvent = $event->rooms;
        $sessionsOfEvent = $roomsOfEvent->flatMap->sessions;

        $capacityOfRooms = []; //công suất của các phòng
        $titleOfSessions = []; //title của mỗi session;
        $amountAttendeeRegistrated = []; //stored số lượng người đã đăng ký trên mỗi phiên

        foreach ($sessionsOfEvent as $session) {
            $capacityOfRooms[] = $session->room->capacity;
            $titleOfSessions[] = $session->title;
            $amountAttendeeRegistrated[] = count($session->session_registrations);
        }

        return view('report.report', [
            'organizer' => $organizer,
            'event' => $event,
            'capacityOfRooms' => $capacityOfRooms,
            'titleOfSessions' => $titleOfSessions,
            'amountAttendeeRegisted' => $amountAttendeeRegistrated
        ]);
    }
}
