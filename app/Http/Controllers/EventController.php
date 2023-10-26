<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Events;
use App\Models\Tickets;
use App\Models\Channels;
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
        $events = Events::getEventsOfOrganizer($currentUser->id);

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
        $regx_date = "/^\d{4}-\d{1,2}-\d{1,2}$/";
        if (!preg_match($regx, $slug))
            $error_slug = "Slug không được để trống và chỉ chứa các ký tự a-z, 0-9 và '-'";
        if (!preg_match($regx_date, $date) && (strtotime($currentDate) > strtotime($date)))
            $error_date = "Ngày diễn ra sự kiện không hợp lệ!";

        //kiểm tra xem slug đã tồn tại chưa;
        $check_slug = Events::getInforEvent($currentUser->id, $slug);
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
        Events::createEvent($currentUser->id, $name, $slug, $date);
        return redirect('/event/detail/' . $slug);
    }

    public function detailEvent(Request $request, $slug)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');

        $infor_event = Events::getInforEvent($currentUser->id, $slug);
        $ticket_list = Tickets::getTicketsOfEvent($infor_event->id);

        $list_id_channel = array(); // mảng lưu danh sách id các kênh để truy vấn;
        $channel_list = Channels::getChannelsOfEvent($infor_event->id);
        for ($i = 0; $i < count($channel_list); $i++) {
            array_push($list_id_channel, $channel_list[$i]->id);
        }

        $room_list = Room::getRoomsOfEvent($list_id_channel);
        $session_list = Session::getSessionsOfEvent($list_id_channel);
        $count_session_of_channels = Session::getCountSessionOfChannles($list_id_channel);

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

        $infor_event = Events::getInforEvent($currentUser->id, $slug);
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

        $infor_event = Events::getInforEvent($currentUser->id, $slug);

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
        $currentDate = date('Y-m-d');
        $regx = '/^[^a-z0-9-]+$/';
        $regx_date = "/^\d{4}(-)\d{2}-\d{2}$/";

        if (preg_match($regx, $slug))
            $error_slug = "Slug không được để trống và chỉ chứa các ký tự a-z, 0-9 và '-'";
        if (
            !preg_match($regx_date, $date_update) && strtotime($currentDate) > strtotime($date_update)
        )
            $error_date = "Ngày diễn ra sự kiện không hợp lệ!";

        //kiểm tra xem slug đã tồn tại chưa;
        $check_slug = Events::getInforEvent($currentUser->id, $slug_update);
        if ($check_slug && $check_slug->slug !== $slug_update)
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
            Events::updateInforEvent($currentUser->id, $slug, $infor_event_update);
        }
        return redirect('/event/detail/' . $slug_update);
    }
}
