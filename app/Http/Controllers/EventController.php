<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $organizer = AuthController::checkLoginted($request);
            if (!$organizer) return redirect('/login');
            $events = Event::getEventsOfOrganizer($organizer->id);
            return view('event.index', compact('organizer', 'events'));
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return view('error.500');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $organizer = AuthController::checkLoginted($request);
            if (!$organizer) return redirect('/login');
            return view('event.create', compact('organizer'));
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return view('error.500');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $organizer = AuthController::checkLoginted($request);
        if (!$organizer) return redirect('/login');
        $request->validate(
            [
                'name' => ['required', 'max:255'],
                'slug' => ['required', 'max:255', 'unique:events', 'regex:/^[a-z0-9-]+$/'],
                'date' => ['required', 'date_format:Y-m-d'],
            ],
            [
                'max' => ':attribute vượt quá kích thước cho phép!',
                'required' => ':attribute không được để trống!',
                'unique' => ':attribute đã tồn tại cho một sự kiện khác',
                'regex' => ':attribute chỉ được chứa các ký tự từ a-z, 0-9 hoặc "-"',
                'date_format' => ':attribute không hợp lệ',

            ],
            [
                'name' => 'Tên',
                'slug' => 'Slug',
                'date' => 'Ngày'
            ]
        );
        try {
            $event = new Event();
            $event->organizer_id = $organizer->id;
            $event->name = $request->input('name');
            $event->slug = $request->input('slug');
            $event->date = $request->input('date');
            $event->save();
            return redirect('/events/' . $event->slug);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return view('error.500');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $slug)
    {
        try {
            $organizer = AuthController::checkLoginted($request);
            if (!$organizer) return redirect('/login');

            $event = Event::getInfor($organizer->id, $slug);
            if (!$event) return redirect('/404-error');

            $tickets = $event->tickets;
            $channels = $event->channels;
            $rooms = $channels->flatMap->rooms;
            $sessions = $rooms->flatMap->sessions;
            return view(
                'event.detail',
                compact('organizer', 'event', 'tickets', 'channels', 'rooms', 'sessions')
            );
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return view('error.500');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $slug)
    {
        try {
            $organizer = AuthController::checkLoginted($request);
            if (!$organizer) return redirect('/login');

            $event = Event::getInfor($organizer->id, $slug);
            if (!$event) return redirect('/error-404');
            return view('event.edit', compact('organizer', 'event'));
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return view('error.500');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        $organizer = AuthController::checkLoginted($request);
        if (!$organizer) return redirect('/login');
        $event = Event::getInfor($organizer->id, $slug);
        $request->validate(
            [
                'name' => ['required', 'max:255'],
                'slug' => [
                    'required', 'max:255', "unique:events,slug," . $event->id . ",id",
                    'regex:/^[a-z0-9-]+$/'
                ],
                'date' => ['required', 'date_format:Y-m-d'],
            ],
            [
                'required' => ':attribute không được để trống!',
                'max' => ':attribute đã vượt quá kích thước cho phép',
                'unique' => ':attribute đã tồn tại',
                'regex' => ':attribute chỉ được chứa các ký tự a-z, 0-9 hoặc "-"',
                'date_format' => ':attribute không hợp lệ!',
            ],
            [
                'name' => 'Tên',
                'slug' => 'Slug',
                'date' => 'Ngày'
            ]
        );
        $event->name = $request->input('name');
        $event->slug = $request->input('slug');
        $event->date = $request->input('date');
        $event->save();
        return redirect('/events/' . $event->slug);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        //
    }
}
