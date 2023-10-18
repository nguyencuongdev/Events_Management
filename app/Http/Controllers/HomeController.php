<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request){
        //lấy thông tin nhà tổ chức;
       $currentUser = json_decode($request->cookie('currentUser'));
       if(!$currentUser) return redirect('/login');

       $events = \DB::table('event_tickets')
                    ->join('events','event_tickets.event_id','=','events.id')
                    ->join('registrations','registrations.ticket_id','=','event_tickets.id')
                    ->selectRaw('events.*, count(registrations.id) as count_registration')
                    ->groupBy('events.id','events.organizer_id','events.name','events.slug','events.date')
                    ->having('organizer_id',$currentUser->id)
                    ->get();
       return view('index',[
            'currentUser' => $currentUser,
            'events' => $events
        ]);
    }

}
