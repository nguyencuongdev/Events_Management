<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Events;
use App\Models\Channels;
use App\Models\Room;


class RoomController extends Controller
{
    public function createRoom(Request $request, $slug)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');

        $infor_event = Events::getInforEvent($currentUser->id, $slug);
        $channel_list = Channels::getChannelsOfEvent($infor_event->id);
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

        $infor_event = Events::getInforEvent($currentUser->id, $slug);
        $channel_list = Channels::getChannelsOfEvent($infor_event->id);

        $room_name = trim($request->input('name'));
        $id_channel = $request->input('channel');
        $room_capacity = $request->input('capacity');

        $error_room_name = '';
        $error_room_capacity = '';

        if (!$room_name)  $error_room_name = 'Tên phòng không được để trống!';
        if (!$room_capacity || $room_capacity <= 0)
            $error_room_capacity = 'Công suất phòng không được để trống! và phải lớn hơn 0';

        //kiểm tra xem phòng đã tồn tại trong kênh chưa;
        $check_room = Room::getInforRoom($id_channel, $room_name);
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
        $infor_room = [
            'name' => $room_name,
            'channel_id' => $id_channel,
            'capacity' => $room_capacity
        ];
        Room::createRoom($infor_room);
        return redirect('/event/detail/' . $infor_event->slug);
    }
}
