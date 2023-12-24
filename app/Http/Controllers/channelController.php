<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Channel;
use App\Models\Event;


class ChannelController extends Controller
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
            if (!$event) return redirect('/error-404');
            return view('channel.create', compact('organizer', 'event'));
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
            ['name' => 'required'],
            ['required' => ':attribute không được để trống!'],
            ['name' => 'Tên']
        );

        try {
            $organizer = AuthController::checkLoginted($request);
            if (!$organizer) return redirect('/login');
            $event = Event::getInfor($organizer->id, $slug);
            if (!$event) return redirect('/error-404');
            $channel = new Channel();
            $channel->name = $request->input('name');
            $channel->event_id = $event->id;
            $channel->save();
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
            $channel = Channel::find($id);
            if (!$channel) return redirect('/error-404');
            $event = $channel->event;
            return view('channel.edit', compact('organizer', 'event', 'channel'));
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
            ['name' => 'required'],
            ['required' => ':attribute không được để trống!'],
            ['name' => 'Tên']
        );

        try {
            $channel = Channel::find($id);
            $channel->name = $request->input('name');
            $channel->save();
            $event = $channel->event;
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
