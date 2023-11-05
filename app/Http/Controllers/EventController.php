<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Channel;
use App\Models\Organizer;
use App\Models\Room;
use App\Models\Session;


class EventController extends Controller
{
    public function index(Request $request)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');

        //lấy ra danh sách các sự kiện của nhà tổ chức
        $events = Event::getEventsOfOrganizer($currentUser->id);

        return view('event.index', [
            'currentUser' => $currentUser,
            'events' => $events
        ]);
    }

    public function createEvent(Request $request)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');
        return view('event.create', [
            'currentUser' =>  $currentUser,
            'error' => [
                'name' => '',
                'slug' => '',
                'date' => '',
            ],
            'data' => [
                'name' => '',
                'slug' => '',
                'date' => '',
            ]
        ]);
    }

    public function handleCreateEvent(Request $request)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');

        $name = trim($request->input('name'));
        $slug = $request->input('slug');
        $date = $request->input('date');

        $error_name = '';
        $error_slug = '';
        $error_date = '';

        //Kiểm tra xem các trường có để trống không;
        if (!$name) $error_name = 'Tên không được để trống';
        if (!$slug) $error_slug = 'Slug không được để trống!';
        if (!$date) $error_date = 'Date không được để trông!';

        //kiểm tra xem slug có hợp lệ không;
        //Ngày diễn ra sự kiện có hợp lệ không;
        $currentDate = date('Y-m-d');
        $regx = '/^[a-z0-9-]+$/';
        $regx_date = '/^\d{4}-\d{1,2}-\d{1,2}$/';
        if (!preg_match($regx, $slug))
            $error_slug = "Slug không được để trống và chỉ chứa các ký tự a-z, 0-9 và '-'";
        if (!preg_match($regx_date, $date) || (strtotime($currentDate) > strtotime($date)))
            $error_date = "Ngày diễn ra sự kiện không hợp lệ!";

        //kiểm tra xem slug đã tồn tại chưa;
        $check_slug = Event::getInforEvent($currentUser->id, $slug);
        if ($check_slug) $error_slug = "Slug đã được sử dụng";
        if ($error_name || $error_slug || $error_date) {
            return view('event.create', [
                'currentUser' => $currentUser,
                'error' => [
                    'name' =>  $error_name,
                    'slug' =>  $error_slug,
                    'date' =>  $error_date,
                ],
                'data' => [
                    'name' => $name,
                    'slug' => $slug,
                    'date' => $date
                ]
            ]);
        }

        //save
        Event::createEvent($currentUser->id, $name, $slug, $date);
        return redirect('/event/detail/' . $slug);
    }

    public function detailEvent(Request $request, $slug)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');

        $infor_event = Event::getInforEvent($currentUser->id, $slug);
        $ticket_list = Ticket::getTicketsOfEvent($infor_event->id);

        $channel_list = Channel::getChannelsOfEvent($infor_event->id);
        $room_list = Room::getRoomsOfEvent($infor_event->id);
        $session_list = Session::getSessionsOfEvent($infor_event->id);
        $count_session_of_channels = Session::getCountSessionOfChannles($infor_event->id);

        return view('event.detail', [
            'currentUser' => $currentUser,
            'infor_event' => $infor_event,
            'channel_list' => $channel_list,
            'ticket_list' => $ticket_list,
            'room_list' => $room_list,
            'session_list' => $session_list,
            'count_session_of_rooms' => $count_session_of_channels
        ]);
    }

    public function editEvent(Request $request, $slug)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');

        $infor_event = Event::getInforEvent($currentUser->id, $slug);
        return view('event.edit', [
            'currentUser' => $currentUser,
            'infor_event' =>  $infor_event,
            'error' => [
                'name' => '',
                'slug' => '',
                'date' => '',
            ],
            'data' => [
                'name' => $infor_event->name,
                'slug' => $infor_event->slug,
                'date' => $infor_event->date,
            ]
        ]);
    }

    public function handleEditEvent(Request $request, $slug)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');

        $infor_event = Event::getInforEvent($currentUser->id, $slug);

        $name_update = trim($request->input('name'));
        $slug_update = $request->input('slug');
        $date_update = $request->input('date');

        $error_name = '';
        $error_slug = '';
        $error_date = '';

        if (!$name_update) $error_name = 'Tên không được để trống';
        if (!$slug_update) $error_slug = 'Slug không được để trống!';
        if (!$date_update) $error_date = 'Date không được để trông!';

        //kiểm tra xem slug có hợp lệ không;
        //Ngày diễn ra sự kiện có hợp lệ không;
        $regx = '/^[a-z0-9-]+$/';
        $regx_date = '/^\d{4}(-)\d{1,2}-\d{1,2}$/';

        if (!preg_match($regx, $slug_update))
            $error_slug = "Slug không được để trống và chỉ chứa các ký tự a-z, 0-9 và '-'";
        if (!preg_match($regx_date, $date_update))
            $error_date = "Ngày diễn ra sự kiện không hợp lệ!";

        //kiểm tra xem slug đã tồn tại chưa;
        $check_slug = Event::getInforEvent($currentUser->id, $slug_update);
        if ($check_slug && ($check_slug->slug !== $slug_update))
            $error_slug = "Slug đã tồn tại cho 1 events khác!";

        //Nếu có 1 lỗi reder lại trang edit event;
        if ($error_name || $error_slug || $error_date) {
            return view('event.edit', [
                'currentUser' => $currentUser,
                'infor_event' =>  $infor_event,
                'error' => [
                    'name' =>  $error_name,
                    'slug' =>  $error_slug,
                    'date' =>  $error_date,
                ],
                'data' => [
                    'name' => $name_update,
                    'slug' => $slug_update,
                    'date' => $date_update
                ]
            ]);
        }

        if (
            $name_update !== $infor_event->name ||
            $slug_update !== $infor_event->slug ||
            $date_update !== $infor_event->date
        ) {
            $infor_event_update = [
                'name' => $name_update,
                'slug' => $slug_update,
                'date' => $date_update
            ];
            Event::updateInforEvent($currentUser->id, $slug, $infor_event_update);
        }
        return redirect('/event/detail/' . $slug_update);
    }

    //Api 
    public function handleGetEvents()
    {
        $events = Event::getEvents();
        return response()->json(['events' => $events]);
    }

    public function handleGetInforDetailEvent($organizer_slug, $event_slug)
    {
        $infor_organizer = Organizer::getInforOrganizerBySlug($organizer_slug);
        if (!$infor_organizer) {
            return response()->json(['message' => 'Nhà tổ chức không tồn tại'], 404);
        }

        $infor_event = Event::getInforEvent($infor_organizer->id, $event_slug);
        if (!$infor_event) {
            return response()->json(['message' => 'Sự kiện không tồn tại'], 404);
        }

        $channel_list = Channel::getChannelsOfEvent($infor_event->id);
        $room_list = Room::getRoomsOfEvent($infor_event->id);
        $ticket_list = Ticket::getTicketsOfEvent($infor_event->id);
        $session_list = Session::getSessionsOfEvent($infor_event->id);
        $channelsAndRoomOfEvent = [];

        $inforchannel = [
            'id' => '',
            'name' => '',
            'rooms' => []
        ];

        $infor_room = [
            'id' => '',
            'name' => '',
            'capacity' => 0,
            'sessions' => []
        ];

        $infor_session = [
            'id' => '',
            'title' => '',
            'description' => '',
            'speaker' => '',
            'start' => '',
            'end' => '',
            'type' => '',
            'cost' => ''
        ];

        foreach ($channel_list as $channel) {
            $inforchannel['id'] = $channel->id;
            $inforchannel['name'] = $channel->name;
            foreach ($room_list as $room) {
                if ($room->channel_id == $channel->id) {
                    $infor_room['id'] = $room->id;
                    $infor_room['name'] = $room->name;
                    $infor_room['capacity'] = $room->capacity;
                    foreach ($session_list as $session) {
                        if ($session->room_id == $room->id) {
                            $infor_session['id'] = $session->id;
                            $infor_session['title'] = $session->title;
                            $infor_session['description'] = $session->description;
                            $infor_session['speaker'] = $session->speaker;
                            $infor_session['start'] = $session->start;
                            $infor_session['end'] = $session->end;
                            $infor_session['type'] = $session->type;
                            $infor_session['cost'] = $session->cost;
                            $infor_room['sessions'][] = $infor_session;
                        }
                    }
                    $inforchannel['rooms'][] = $infor_room;
                }
            }
            $channelsAndRoomOfEvent[] = $inforchannel;
        }


        return response()->json([
            'id' => $infor_event->id,
            'name' => $infor_event->name,
            'slug' => $infor_event->slug,
            'date' => $infor_event->date,
            'channels' => $channelsAndRoomOfEvent,
            'tickets' => $ticket_list
        ], 200);
    }
}
