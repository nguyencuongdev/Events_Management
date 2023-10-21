<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    public function createSession(Request $request, $slug)
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

        return view('session.create', [
            'currentUser' => $currentUser,
            'infor_event' => $infor_event,
        ]);
    }

    public function handleCreateSession(Request $request, $slug)
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
    }

    public function editSession(Request $request, $slug)
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

        return view('session.edit', [
            'currentUser' => $currentUser,
            'infor_event' => $infor_event,
        ]);
    }

    public function handleEditSession(Request $request, $slug)
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
    }
}
