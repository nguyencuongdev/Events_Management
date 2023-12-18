<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Organizer;

class EventController extends Controller
{
    public function getEvents()
    {
        $events = Event::getEvents();
        return response()->json([
            'events' => $events
        ], 200);
    }

    public function getInforDetailEvent($organizer_slug, $event_slug)
    {
        $organizer = Organizer::getInforBySlug($organizer_slug);
        if (!$organizer)
            return response()->json([
                'message' => 'Không tìm thấy nhà tổ chức'
            ], 404);
        $infor_detail_event = Event::with([
            'channels.rooms.sessions',
            'tickets'
        ])->where([
            ['organizer_id', $organizer->id],
            ['slug', $event_slug]
        ])->first();
        if (!$infor_detail_event)
            return response()->json([
                'message' => 'Không tìm thấy sự kiện'
            ], 404);
        return response()->json($infor_detail_event, 200);
    }
}
