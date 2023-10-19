<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request){
        //lấy thông tin nhà tổ chức;
       $currentUser = json_decode($request->cookie('currentUser'));
       if(!$currentUser) return redirect('/login');

       //lấy ra danh sách các sự kiện của nhà tổ chức
       //đồng thời lấy ra số lượng người đăng ký sự kiện đó.
        $events = \DB::table('events')
            ->leftJoin('event_tickets', 'events.id', '=', 'event_tickets.event_id')
            ->leftJoin('registrations', 'event_tickets.id', '=', 'registrations.ticket_id')
            ->selectRaw('events.*, COALESCE(count(registrations.ticket_id), 0) as registration_count')
            ->where('events.organizer_id', $currentUser->id)
            ->groupBy('events.id', 'events.organizer_id', 'events.name', 'events.slug', 'events.date')
            ->get();

       return view('event.index',[
            'currentUser' => $currentUser,
            'events' => $events
        ]);
    }

    public function create(Request $request){
        //lấy thông tin nhà tổ chức;
       $currentUser = json_decode($request->cookie('currentUser'));
       if(!$currentUser) return redirect('/login');
        return view('event.create',[
            'currentUser' => $currentUser,
            'error' => [
                'name' => '',
                'slug' => '',
                'date' => '',
            ],
            'data' => [
                'name' => '',
                'slug' =>'',
                'date' => ''
            ]
        ]);
    }

    public function handleCreateEvent(Request $request){
        //lấy thông tin nhà tổ chức;
       $currentUser = json_decode($request->cookie('currentUser'));

        $name = $request->input('name');
        $slug = $request->input('slug');
        $date = $request->input('date');

        $error_name = '';
        $error_slug = '';
        $error_date = '';

        if(!$name)  $error_name = 'Tên không được để trống';
        if(!$slug) $error_slug = 'Slug không được để trống!';
        if(!$date) $error_date = 'Date không được để trông!';

        if($error_name || $error_slug || $error_date){
            return view('event.create',[
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

        if(preg_match($regx,$slug)) $error_slug = "Slug chỉ được chứa các ký tự a-z, 0-9 và '-'";
        if(preg_match( $regx_date,$date) && strtotime($currentDate) > strtotime($date))
            $error_slug = "Ngày diễn ra sự kiện không hợp lệ!";
        if($error_slug || $error_date)
            return view('event.create',[
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

        //kiểm tra xem slug đã tồn tại chưa;
        $check_slug = \DB::table('events')
                        ->where([
                            ['events.slug','=',$slug],
                            ['events.organizer_id', $currentUser->id]
                        ])
                        ->first();
        if($check_slug) {
        $error_slug = "Slug đã tồn tại cho 1 events khác!";
            return view('event.create',[
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
        \DB::table('events')->insert([
            'organizer_id' => $currentUser->id,
            'name' => $name,
            'slug' => $slug,
            'date' => $date,
        ]);
       return redirect('/event/detail/'.$slug);
    }

    public function detail(Request $request){
        //lấy thông tin nhà tổ chức;
       $currentUser = json_decode($request->cookie('currentUser'));
       if(!$currentUser) return redirect('/login');


       
        return view('event.detail',[
            'currentUser' => $currentUser,
        ]);
    }
}
