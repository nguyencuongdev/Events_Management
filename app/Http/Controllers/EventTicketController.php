<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Event;
use App\Models\EventTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventTicketController extends Controller
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
            return view('ticket.create', compact('organizer', 'event'));
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
                'name' => ['required', 'string', 'max:255'],
                'cost' => ['required', 'string'],
            ],
            [
                'required' => ':attribute không được để trống!',
                'string' => ':attribute không hợp lệ!',
                'max' => ':attribute không được vượt quá 255 ký tự',
            ],
            [
                'name' => 'Tên của vé phải là duy nhất',
                'cost' => 'Giá vé',
            ]
        );
        try {
            $organizer = AuthController::checkLoginted($request);
            $event = Event::getInfor($organizer->id, $slug);
            $type_speical = $request->input('special_validity');
            $special_validity = null;
            switch ($type_speical) {
                case 'date': {
                        $special_validity = json_encode([
                            'type' => 'date',
                            'date' => $request->input('date')
                        ]);
                        break;
                    }
                case 'amount': {
                        $special_validity = json_encode([
                            'type' => 'amount',
                            'amount' => $request->input('amount')
                        ]);
                        break;
                    }
                default:
                    $special_validity = null;
            }
            EventTicket::create([
                'event_id' => $event->id,
                'name' => $request->input('name'),
                'cost' => $request->input('cost'),
                'special_validity' => $special_validity
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
    public function show(Request $request, string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        try {
            $organizer = AuthController::checkLoginted($request);
            if (!$organizer) return redirect('/login');
            $ticket = EventTicket::getInfor($id);
            $special_validity = json_decode($ticket->special_validity);
            $event = $ticket->event;
            return view('ticket.edit', compact('organizer', 'event', 'ticket', 'special_validity'));
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return view('error.500');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $request->validate(
            [
                'name' => ['required', 'max:255'],
                'cost' => ['required', 'max:255'],
            ],
            [
                'required' => ':attribute không được để trống!',
                'max' => ':attribute không được vượt quá 255 ký tự',
            ],
            [
                'name' => 'Tên của vé phải là duy nhất',
                'cost' => 'Giá vé',
            ]
        );

        try {
            $organizer = AuthController::checkLoginted($request);
            $ticket = EventTicket::getInfor($id);
            $event = $ticket->event;
            $event = Event::getInfor($organizer->id, $event->slug);
            $type_speical = $request->input('special_validity');
            $special_validity = null;
            switch ($type_speical) {
                case 'date': {
                        $special_validity = json_encode([
                            'type' => 'date',
                            'date' => $request->input('date')
                        ]);
                        break;
                    }
                case 'amount': {
                        $special_validity = json_encode([
                            'type' => 'amount',
                            'amount' => $request->input('amount')
                        ]);
                        break;
                    }
                default:
                    $special_validity = null;
            }
            $ticket->name = $request->input('name');
            $ticket->cost = $request->input('cost');
            $ticket->special_validity = $special_validity;
            $ticket->save();
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
