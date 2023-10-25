<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request, $slug)
    {
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');

        $infor_event = DB::table('events')
            ->where([
                ['events.organizer_id', '=', $currentUser->id],
                ['events.slug', '=', $slug]
            ])
            ->first();

        $capacity_rooms = DB::table('rooms')->orderBy('rooms.id', 'asc')->get();
        return view('report.index', [
            'currentUser' => $currentUser,
            'infor_event' => $infor_event
        ]);
    }
}
