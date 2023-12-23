<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


use App\Models\Event;
use App\Models\Session;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, string $slug)
    {
        try {
            $organizer = AuthController::checkLoginted($request);
            if (!$organizer) return redirect('/login');
            $event = Event::getInfor($organizer->id, $slug);
            $rooms = $event->rooms;
            if (!$event) return redirect('/error-404');
            return view('session.create', compact('organizer', 'event', 'rooms'));
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return view('error.500');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $slug)
    {
        $request->validate(
            [
                'title' => ['required', 'string', 'max:255'],
                'type' => ['required', 'string', 'max:255'],
                'speaker' => ['required', 'string', 'max:255'],
                'room' => ['required', 'string', 'max:255'],
                'cost' => ['required'],
                'start' => ['required'],
                'end' => ['required'],
                'description' => ['required', 'string'],
            ],
            [
                'required' => ':attribute không được để trống!',
                'string' => ':attribute không hợp lệ!',
                'max' => ':attribute không được vượt quá 255 ký tự',
            ],
            [
                'title' => 'Tiêu đề',
                'type' => 'Loại phiên',
                'speaker' => 'Người trình bày',
                'room' => 'Phòng',
                'cost' => 'Chi phí phiên',
                'start' => 'Thời gian bắt đầu',
                'end' => 'Thời gian kết thúc',
                'description' => 'Mô tả không được để trống'
            ]
        );
        try {
            $organizer = AuthController::checkLoginted($request);
            $event = Event::getInfor($organizer->id, $slug);
            $time_event = $event->date;
            Session::create([
                'room_id' => $request->input('room'),
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'speaker' => $request->input('speaker'),
                'start' => $time_event . ' ' . $request->input('start'),
                'end' => $time_event . ' ' . $request->input('end'),
                'type' => $request->input('type'),
                'cost' => $request->input('cost')
            ]);
            return redirect('/events/' . $slug);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return view('error.500');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, int $id)
    {
        try {
            $organizer = AuthController::checkLoginted($request);
            if (!$organizer) return redirect('/login');
            $session = Session::find($id);
            $roomOfSession = $session->room;
            $event = Event::find($roomOfSession->channel->event->id);
            $rooms = $event->rooms;
            return view('session.edit', compact('organizer', 'event', 'rooms', 'session'));
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return view('error.500');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'title' => ['required', 'string', 'max:255'],
                'type' => ['required', 'string', 'max:255'],
                'speaker' => ['required', 'string', 'max:255'],
                'room' => ['required', 'string', 'max:255'],
                'cost' => ['required'],
                'start' => ['required'],
                'end' => ['required'],
                'description' => ['required', 'string']
            ],
            [
                'required' => ':attribute không được để trống!',
                'string' => ':attribute không hợp lệ!',
                'max' => ':attribute không được vượt quá 255 ký tự',
            ],
            [
                'title' => 'Tiêu đề',
                'type' => 'Loại phiên',
                'speaker' => 'Người trình bày',
                'room' => 'Phòng',
                'cost' => 'Chi phí phiên',
                'start' => 'Thời gian bắt đầu',
                'end' => 'Thời gian kết thúc',
                'description' => 'Mô tả không được để trống'
            ]
        );
        try {
            $session = Session::find($id);
            $roomOfSession = $session->room;
            $event = Event::find($roomOfSession->channel->event->id);
            $time_event = $event->date;
            $session->title = $request->input('title');
            $session->description = $request->input('description');
            $session->speaker = $request->input('speaker');
            $session->start = $time_event . ' ' . $request->input('start');
            $session->end  = $time_event . ' ' . $request->input('end');
            $session->type = $request->input('type');
            $session->cost = $request->input('cost');
            $session->save();
            return redirect('/events/' . $event->slug);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return view('error.500');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
