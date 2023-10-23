<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RoomController extends Controller
{
    public function createRoom(Request $request, $slug)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');

        //thông tin sự kiện
        $infor_event = DB::table('events')
            ->where([
                ['events.slug', '=', $slug],
                ['events.organizer_id', '=', $currentUser->id]
            ])
            ->first();

        //Danh sách kênh của sự kiện
        $channel_list = DB::table('channels')
            ->where('channels.event_id', '=', $infor_event->id)
            ->get();

        return view('room.create', [
            'currentUser' => $currentUser,
            'infor_event' =>  $infor_event,
            'error' => [
                'name' => '',
                'capacity' => '',
            ],
            'channel_list' => $channel_list,
            'data' => [
                'name' => '',
                'channel' =>  $channel_list[0]->id,
                'capacity' => '',
            ]
        ]);
    }

    public function handleCreateRoom(Request $request, $slug)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');

        //thông tin sự kiện
        $infor_event = DB::table('events')
            ->where([
                ['events.slug', '=', $slug],
                ['events.organizer_id', '=', $currentUser->id]
            ])
            ->first();

        //Danh sách kênh của sự kiện
        $channel_list = DB::table('channels')
            ->where(
                'channels.event_id',
                '=',
                $infor_event->id
            )
            ->get();

        $room_name = trim($request->input('name'));
        $id_channel = $request->input('channel');
        $room_capacity = $request->input('capacity');

        $error_room_name = '';
        $error_room_capacity = '';

        if (!$room_name)  $error_room_name = 'Tên phòng không được để trống!';
        if (!$room_capacity) $error_room_capacity = 'Công suất phòng không được để trống!';

        //Kiêm tra xem người dùng có để trống trg name và capacity không;
        if ($error_room_name || $error_room_capacity) {
            return view('room.create', [
                'currentUser' => $currentUser,
                'infor_event' =>  $infor_event,
                'error' => [
                    'name' => $error_room_name,
                    'capacity' => $error_room_capacity
                ],
                'channel_list' => $channel_list,
                'data' => [
                    'name' => $room_name,
                    'channel' => $id_channel,
                    'capacity' => $room_capacity,
                ]
            ]);
        }

        //kiểm tra giá trị capacity có hợp lệ không;
        if ($room_capacity <= 0) {
            $error_room_capacity = 'Công suất phòng phải lớn hơn 0';
        }
        //kiểm tra xem phòng đã tồn tại trong kênh chưa;
        $check_room = DB::table('rooms')
            ->where([
                ['rooms.channel_id', '=', $id_channel],
                ['rooms.name', '=', $room_name]
            ])
            ->first();
        if ($check_room) $error_room_name = 'Phòng đã tồn tại trong kênh!';

        if ($error_room_name || $error_room_capacity) {
            return view('room.create', [
                'currentUser' => $currentUser,
                'infor_event' =>  $infor_event,
                'error' => [
                    'name' => $error_room_name,
                    'capacity' => $error_room_capacity
                ],
                'channel_list' => $channel_list,
                'data' => [
                    'name' => $room_name,
                    'channel' => $id_channel,
                    'capacity' => $room_capacity,
                ]
            ]);
        }

        $insert_room = DB::table('rooms')
            ->insert([
                'name' => $room_name,
                'channel_id' => $id_channel,
                'capacity' => $room_capacity
            ]);
        return redirect('/event/detail/' . $infor_event->slug);
    }
}
