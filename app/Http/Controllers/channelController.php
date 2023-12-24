<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\Channel;

class ChannelController extends Controller
{
    public function createChannel(Request $request, $slug)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');

        //thông tin sự kiện
        $infor_event = Event::getInforEvent($currentUser->id, $slug);
        return view('channel.create', [
            'currentUser' => $currentUser,
            'infor_event' =>  $infor_event,
            'error' => [
                'name' => '',
            ],
            'data' => [
                'name' => '',
            ]
        ]);
    }

    public function handleCreateChannel(Request $request, $slug)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');

        //thông tin sự kiện
        $infor_event = Event::getInforEvent($currentUser->id, $slug);
        $error_name = '';
        $channel_name = trim($request->input('name'));
        if (!$channel_name) $error_name = 'Tên channel không được để trống!';
        $check_channel = Channel::getInforChannel($infor_event->id, $channel_name);
        if ($check_channel) $error_name = 'Kênh đã tồn tại!';

        if ($error_name) {
            return view('channel.create', [
                'currentUser' => $currentUser,
                'infor_event' => $infor_event,
                'error' => [
                    'name' => $error_name,
                ],
                'data' => [
                    'name' => $channel_name
                ]
            ]);
        }
        Channel::createChannel($infor_event->id, $channel_name);
        return redirect('/event/detail/' . $slug);
    }
}
