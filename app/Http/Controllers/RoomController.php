<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Room;
use App\Http\Controllers\AuthController;


use App\Models\Event;


class RoomController extends Controller
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
            $channels = $event->channels;
            if (!$event) return redirect('/error-404');
            return view('room.create', compact('organizer', 'event', 'channels'));
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return view('error.500');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $slug)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'channel' => ['required', 'string', 'max:255'],
                'capacity' => ['required']
            ],
            [
                'required' => ':attribute không được để trống!',
                'string' => ':attribute không hợp lệ',
                'max:255' => ':attribute vượt quá kích thước cho phép'
            ],
            [
                'name' => 'Tên phòng',
                'channel' => 'Kênh',
                'capacity' => 'Công suất phòng'
            ]
        );
        try {
            $organizer = AuthController::checkLoginted($request);
            $event = Event::getInfor($organizer->id, $slug);
            Room::create([
                'channel_id' => $request->input('channel'),
                'name' => $request->input('name'),
                'capacity' => $request->input('capacity'),
            ]);
            return redirect('/events/' . $event->slug);
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
            $room = Room::find($id);
            $event = $room->channel->event;
            $channels = $event->channels;
            return view('room.edit', compact('organizer', 'event', 'channels', 'room'));
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
                'name' => ['required', 'string', 'max:255'],
                'channel' => ['required', 'string', 'max:255'],
                'capacity' => ['required']
            ],
            [
                'required' => ':attribute không được để trống!',
                'string' => ':attribute không hợp lệ',
                'max:255' => ':attribute vượt quá kích thước cho phép'
            ],
            [
                'name' => 'Tên phòng',
                'channel' => 'Kênh',
                'capacity' => 'Công suất phòng'
            ]
        );
        try {
            $room = Room::find($id);
            $event = $room->channel->event;
            $room->name = $request->input('name');
            $room->channel_id = $request->input('channel');
            $room->capacity = $request->input('capacity');
            $room->save();
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
