<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Room;

class ReportController extends Controller
{
    public function index(Request $request, $slug)
    {
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');

        $infor_event = Event::getInforEvent($currentUser->id, $slug);
        $capacity_rooms = Room::getCapacityRooms();
        return view('report.index', [
            'currentUser' => $currentUser,
            'infor_event' => $infor_event
        ]);
    }
}
