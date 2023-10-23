<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class channelController extends Controller
{
    public function createChannel(Request $request, $slug)
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
        $infor_event = DB::table('events')
            ->where([
                ['events.slug', '=', $slug],
                ['events.organizer_id', '=', $currentUser->id]
            ])
            ->first();
        $channel_name = trim($request->input('name'));
        if (!$channel_name) {
            return view('channel.create', [
                'currentUser' => $currentUser,
                'infor_event' =>  $infor_event,
                'error' => [
                    'name' => 'Tên không được để trống!',
                ],
                'data' => [
                    'name' => $channel_name,
                ]
            ]);
        }

        $check_channel = DB::table('channels')
            ->where([
                ['channels.event_id', '=', $infor_event->id],
                ['channels.name', '=', $channel_name]
            ])
            ->first();
        if ($check_channel) {
            return view('channel.create', [
                'currentUser' => $currentUser,
                'infor_event' =>  $infor_event,
                'error' => [
                    'name' => 'Kênh đã tồn tại!',
                ],
                'data' => [
                    'name' => $channel_name,
                ]
            ]);
        }

        $insert_channel = DB::table('channels')
            ->insert([
                'event_id' => $infor_event->id,
                'name' => $channel_name,
            ]);

        if ($insert_channel) {
            return redirect('/event/detail/' . $slug);
        } else {
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
    }
}
