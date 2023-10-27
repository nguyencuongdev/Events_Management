<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\Channel;
use App\Models\Session;

class SessionController extends Controller
{
    public function createSession(Request $request, $slug)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');
        //thông tin sự kiện
        $infor_event = Event::getInforEvent($currentUser->id, $slug);
        $room_list = Channel::getChannelsAndRoomOfChannel($infor_event->id);
        return view('session.create', [
            'currentUser' => $currentUser,
            'infor_event' => $infor_event,
            'room_list' => $room_list,
            'error' => [
                'title' => '',
                'speaker' => '',
                'room' => '',
                'cost' => '',
                'start' => '',
                'end' => '',
                'description' => '',
            ],
            'data' => [
                'title' => '',
                'speaker' => '',
                'room' => 0,
                'cost' => 0,
                'start' => '',
                'end' => '',
                'description' => '',
            ]
        ]);
    }

    public function handleCreateSession(Request $request, $slug)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');

        $infor_event = Event::getInforEvent($currentUser->id, $slug);
        $room_list = Channel::getChannelsAndRoomOfChannel($infor_event->id);

        $title_session = trim($request->input('title'));
        $type_session = $request->input('type');
        $speaker_session = trim($request->input('speaker'));
        $id_room = $request->input('room');
        $cost_session = $request->input('cost');
        $time_start_session = $request->input('start');
        $time_end_session = $request->input('end');
        $description_session = $request->input('description');

        $error_title_session = '';
        $error_speaker_session = '';
        $error_room = '';
        $error_cost_session = '';
        $error_time_start_session = '';
        $error_time_end_session = '';
        $error_description_session = '';

        if (!$title_session) $error_title_session =  'Không được để trống trường này!';
        if (!$speaker_session) $error_speaker_session = 'Không được để trống trường này!';
        if ($cost_session < 0) $error_cost_session = 'Giá phiên không được nhỏ hơn 0';
        if (!$time_start_session) $error_time_start_session = 'Không được để trống trường này!';
        if (!$time_end_session) $error_time_end_session = 'Không được để trống trường này!';
        if (!$description_session) $error_description_session = 'Không được để trống trường này!';

        $reg_time = '/^\d{4}-\d{1,2}-\d{1,2} \d{1,2}:\d{1,2}/';
        if ($time_start_session && !preg_match($reg_time, $time_start_session))
            $error_time_start_session = 'Thời gian không hợp lệ!';
        if (
            $time_end_session && !preg_match($reg_time, $time_end_session) ||
            (strtotime($time_end_session) < strtotime($time_start_session))
        )
            $error_time_end_session = 'Thời gian không hợp lệ!';

        $check_room = DB::table('sessions')
            ->where([
                ['room_id', '=', $id_room],
                ['start', '>', strtotime($time_start_session)],
                ['end', '<', strtotime($time_end_session)]
            ])
            ->get();
        if (count($check_room) > 0) $error_room = 'Phòng đang diễn ra 1 phiên khác!';
        if (
            $error_title_session || $error_speaker_session || $error_cost_session || $error_time_start_session || $error_time_end_session || $error_description_session
        ) {
            return view('session.create', [
                'currentUser' => $currentUser,
                'infor_event' => $infor_event,
                'room_list' => $room_list,
                'error' => [
                    'title' => $error_title_session,
                    'speaker' => $error_speaker_session,
                    'room' => $error_room,
                    'cost' => $error_cost_session,
                    'start' => $error_time_start_session,
                    'end' => $error_time_end_session,
                    'description' => $error_description_session,
                ],
                'data' => [
                    'title' => $title_session,
                    'speaker' => $speaker_session,
                    'room' => $id_room,
                    'cost' => $cost_session,
                    'start' => $time_start_session,
                    'end' => $time_end_session,
                    'description' => $description_session,
                ]
            ]);
        }
        $infor_session = [
            'title' => $title_session,
            'description' => $description_session,
            'speaker' => $speaker_session,
            'start' => $time_start_session,
            'end' => $time_end_session,
            'type' => $type_session,
            'cost' => $cost_session,
        ];
        Session::createSession($id_room, $infor_session);
        return redirect('/event/detail/' . $slug);
    }

    public function editSession(Request $request, $slug, $session_id)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');

        $infor_event = Event::getInforEvent($currentUser->id, $slug);
        $room_list = Channel::getChannelsAndRoomOfChannel($infor_event->id);
        $infor_session = Session::getInforSession($session_id);

        return view('session.edit', [
            'currentUser' => $currentUser,
            'infor_event' => $infor_event,
            'room_list' => $room_list,
            'error' => [
                'title' => '',
                'speaker' => '',
                'room' => '',
                'cost' => '',
                'start' => '',
                'end' => '',
                'description' => '',
            ],
            'data' => [
                'id' => $infor_session->id,
                'title' => $infor_session->title,
                'room' => $infor_session->room_id,
                'speaker' => $infor_session->speaker,
                'cost' => $infor_session->cost,
                'start' => $infor_session->start,
                'end' => $infor_session->end,
                'description' => $infor_session->description,
            ]
        ]);
    }

    public function handleEditSession(Request $request, $slug, $session_id)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');

        $infor_event = Event::getInforEvent($currentUser->id, $slug);
        $room_list = Channel::getChannelsAndRoomOfChannel($infor_event->id);

        $title_session = trim($request->input('title'));
        $type_session = $request->input('type');
        $speaker_session = trim($request->input('speaker'));
        $id_room = $request->input('room');
        $cost_session = $request->input('cost');
        $time_start_session = $request->input('start');
        $time_end_session = $request->input('end');
        $description_session = $request->input('description');

        $error_title_session = '';
        $error_speaker_session = '';
        $error_room = '';
        $error_cost_session = '';
        $error_time_start_session = '';
        $error_time_end_session = '';
        $error_description_session = '';

        if (!$title_session) $error_title_session =  'Không được để trống trường này!';
        if (!$speaker_session) $error_speaker_session = 'Không được để trống trường này!';
        if ($cost_session < 0) $error_cost_session = 'Giá phiên không được nhỏ hơn 0';
        if (!$time_start_session) $error_time_start_session = 'Không được để trống trường này!';
        if (!$time_end_session) $error_time_end_session = 'Không được để trống trường này!';
        if (!$description_session) $error_description_session = 'Không được để trống trường này!';

        $reg_time = '/^\d{4}-\d{1,2}-\d{1,2} \d{1,2}:\d{1,2}/';
        if ($time_start_session && !preg_match($reg_time, $time_start_session))
            $error_time_start_session = 'Thời gian không hợp lệ!';
        if (
            $time_end_session && !preg_match($reg_time, $time_end_session) ||
            (strtotime($time_end_session) < strtotime($time_start_session))
        )
            $error_time_end_session = 'Thời gian không hợp lệ!';

        $check_room = DB::table('sessions')
            ->where([
                ['room_id', '=', $id_room],
                ['start', '>', strtotime($time_start_session)],
                ['end', '<', strtotime($time_end_session)]
            ])
            ->get();
        if (count($check_room) > 0) $error_room = 'Phòng đang diễn ra 1 phiên khác!';
        if (
            $error_title_session || $error_speaker_session || $error_cost_session || $error_time_start_session || $error_time_end_session || $error_description_session
        ) {
            return view('session.create', [
                'currentUser' => $currentUser,
                'infor_event' => $infor_event,
                'room_list' => $room_list,
                'error' => [
                    'title' => $error_title_session,
                    'speaker' => $error_speaker_session,
                    'room' => $error_room,
                    'cost' => $error_cost_session,
                    'start' => $error_time_start_session,
                    'end' => $error_time_end_session,
                    'description' => $error_description_session,
                ],
                'data' => [
                    'title' => $title_session,
                    'speaker' => $speaker_session,
                    'room' => $id_room,
                    'cost' => $cost_session,
                    'start' => $time_start_session,
                    'end' => $time_end_session,
                    'description' => $description_session,
                ]
            ]);
        }
        $infor_session = [
            'title' => $title_session,
            'description' => $description_session,
            'speaker' => $speaker_session,
            'start' => $time_start_session,
            'end' => $time_end_session,
            'type' => $type_session,
            'cost' => $cost_session,
        ];
        Session::updateSession($id_room, $session_id, $infor_session);
        return redirect('/event/detail/' . $slug);
    }
}
