<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(Request $request)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');

        //lấy ra danh sách các sự kiện của nhà tổ chức
        //đồng thời lấy ra số lượng người đăng ký sự kiện đó.
        $events = DB::table('events')
            ->leftJoin('event_tickets', 'events.id', '=', 'event_tickets.event_id')
            ->leftJoin('registrations', 'event_tickets.id', '=', 'registrations.ticket_id')
            ->selectRaw('events.*, COALESCE(count(registrations.ticket_id), 0) as registration_count')
            ->where('events.organizer_id', $currentUser->id)
            ->groupBy('events.id', 'events.organizer_id', 'events.name', 'events.slug', 'events.date')
            ->get();

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
            'currentUser' => $currentUser,
            'error' => [
                'name' => '',
                'slug' => '',
                'date' => '',
            ],
            'data' => [
                'name' => '',
                'slug' => '',
                'date' => ''
            ]
        ]);
    }

    public function handleCreateEvent(Request $request)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));

        $name = trim($request->input('name'));
        $slug = $request->input('slug');
        $date = $request->input('date');

        $error_name = '';
        $error_slug = '';
        $error_date = '';

        if (!$name) $error_name = 'Tên không được để trống';
        if (!$slug) $error_slug = 'Slug không được để trống!';
        if (!$date) $error_date = 'Date không được để trông!';

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

        //kiểm tra xem slug có hợp lệ không;
        //Ngày diễn ra sự kiện có hợp lệ không;
        $currentDate = date('Y-m-d');
        $regx = '/^[^a-z0-9-]+$/';
        $regx_date = "/^\d{4}(-)\d{2}-\d{2}$/";

        if (preg_match($regx, $slug)) $error_slug = "Slug chỉ được chứa các ký tự a-z, 0-9 và '-'";
        if (!preg_match($regx_date, $date) && strtotime($currentDate) > strtotime($date))
            $error_date = "Ngày diễn ra sự kiện không hợp lệ!";
        if ($error_slug || $error_date) {
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

        //kiểm tra xem slug đã tồn tại chưa;
        $check_slug = DB::table('events')
            ->where([
                ['events.slug', '=', $slug],
                ['events.organizer_id', $currentUser->id]
            ])
            ->first();
        if ($check_slug) {
            $error_slug = "Slug đã tồn tại cho 1 events khác!";
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

        //lưu lại dữ liệu trên db;
        DB::table('events')->insert([
            'organizer_id' => $currentUser->id,
            'name' => $name,
            'slug' => $slug,
            'date' => $date,
        ]);
        return redirect('/event/detail/' . $slug);
    }

    public function detailEvent(Request $request, $slug)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');
        //lấy thông tin sự kiện
        $infor_event = DB::table('events')
            ->where([
                ['slug', '=', $slug],
                ['organizer_id', '=', $currentUser->id]
            ])
            ->first();
        //lấy ra danh sách các loại vé của sự kiện
        $ticket_list = DB::table('event_tickets')
            ->where('event_id', '=', $infor_event->id)
            ->get();
        $list_id_channel = array(); // mảng lưu danh sách id của các kênh để truy vấn;
        //lấy ra danh sách channel và số lượng phòng của kênh đó;
        $channel_list = DB::table('channels')
            ->join('rooms', 'rooms.channel_id', '=', 'channels.id')
            ->where('channels.event_id', '=', $infor_event->id)
            ->selectRaw('channels.id,channels.name, count(rooms.id) as count_room')
            ->groupBy('channels.id', 'channels.name')
            ->get();
        for ($i = 0; $i < count($channel_list); $i++) {
            array_push($list_id_channel, $channel_list[$i]->id);
        }
        //lấy ra danh sách các room diễn ra các kênh của sự kiện
        $room_list = DB::table('rooms')
            ->whereIn('rooms.channel_id', $list_id_channel)
            ->select('rooms.name', 'rooms.capacity')
            ->get();
        //lấy ra danh sách các phiên của các phòng trong các kênh
        $session_list = DB::table('sessions')
            ->join('rooms', 'rooms.id', '=', 'sessions.room_id')
            ->join('channels', 'channels.id', '=', 'rooms.channel_id')
            ->whereIn('channels.id', $list_id_channel)
            ->select(
                'sessions.*',
                'channels.name as channel_name',
                'rooms.name as room_name',
            )
            ->get();
        //đếm số lượng phiên của từng kênh
        $count_session_of_channels = DB::table('sessions')
            ->join('rooms', 'rooms.id', '=', 'sessions.room_id')
            ->whereIn('rooms.channel_id', $list_id_channel)
            ->selectRaw('count(sessions.id) as count')
            ->groupBy('rooms.channel_id')
            ->get();
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
        $infor_event = DB::table('events')
            ->where([
                ['events.slug', '=', $slug],
                ['events.organizer_id', '=', $currentUser->id]
            ])
            ->first();

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

        $infor_event = DB::table('events')
            ->where([
                ['events.slug', '=', $slug],
                ['events.organizer_id', '=', $currentUser->id]
            ])
            ->first();

        $name_update = trim($request->input('name'));
        $slug_update = $request->input('slug');
        $date_update = $request->input('date');

        $error_name = '';
        $error_slug = '';
        $error_date = '';

        if (!$name_update) $error_name = 'Tên không được để trống';
        if (!$slug_update) $error_slug = 'Slug không được để trống!';
        if (!$date_update) $error_date = 'Date không được để trông!';

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

        //kiểm tra xem slug có hợp lệ không;
        //Ngày diễn ra sự kiện có hợp lệ không;
        $currentDate = date('Y-m-d');
        $regx = '/^[^a-z0-9-]+$/';
        $regx_date = "/^\d{4}(-)\d{2}-\d{2}$/";

        if (preg_match($regx, $slug)) $error_slug = "Slug chỉ được chứa các ký tự a-z, 0-9 và '-'";
        if (
            !preg_match($regx_date, $date_update) && strtotime($currentDate) > strtotime($date_update)
        )
            $error_date = "Ngày diễn ra sự kiện không hợp lệ!";
        if ($error_slug || $error_date) {
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

        //kiểm tra xem slug đã tồn tại chưa;
        $check_slug = DB::table('events')
            ->where([
                ['events.slug', '=', $slug_update],
                ['events.organizer_id', $currentUser->id]
            ])
            ->first();
        if ($check_slug && $check_slug->slug !== $slug_update) {
            $error_slug = "Slug đã tồn tại cho 1 events khác!";
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
            $update_event = DB::table('events')
                ->where([
                    ['events.slug', $slug],
                    ['events.organizer_id', $currentUser->id]
                ])
                ->update([
                    'name' => $name_update,
                    'slug' => $slug_update,
                    'date' => $date_update
                ]);
            if ($update_event)
                return redirect('/event/detail/' . $slug_update);
        } else {
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
    }
}
