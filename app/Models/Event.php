<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
    use HasFactory;
    protected $table = 'events';
    protected $prima_key = 'id';

    public function organizer()
    {
        return $this->belongsTo(Organizer::class)->select('id', 'name', 'slug');
    }

    public static function getEventsOfOrganizer($organzer_id)
    {
        try {
            //lấy ra danh sách các sự kiện của nhà tổ chức
            //đồng thời lấy ra số lượng người đăng ký sự kiện đó.
            $events = DB::table('events')
                ->leftJoin('event_tickets', 'events.id', '=', 'event_tickets.event_id')
                ->leftJoin('registrations', 'event_tickets.id', '=', 'registrations.ticket_id')
                ->selectRaw('events.*, COALESCE(count(registrations.ticket_id), 0) as registration_count')
                ->where('events.organizer_id', $organzer_id)
                ->groupBy('events.id', 'events.organizer_id', 'events.name', 'events.slug', 'events.date')
                ->orderBy('events.date', 'asc')
                ->get();
            return $events;
        } catch (Exception $ex) {
        }
    }

    public static function createEvent($organizer_id, $event_name, $event_slug, $event_date)
    {
        try {
            $status = DB::table('events')->insert([
                'organizer_id' => $organizer_id,
                'name' => $event_name,
                'slug' => $event_slug,
                'date' => $event_date,
            ]);
            return $status;
        } catch (Exception $ex) {
        }
    }

    public static function getInforEvent($organize_id, $slug)
    {
        try {
            $infor_event = DB::table('events')
                ->where([
                    ['events.slug', '=', $slug],
                    ['events.organizer_id', $organize_id]
                ])
                ->first();
            return $infor_event;
        } catch (Exception $ex) {
        }
    }

    public static function updateInforEvent($organizer_id, $slug, $infor_event_update)
    {
        try {
            DB::table('events')
                ->where([
                    ['events.slug', $slug],
                    ['events.organizer_id', $organizer_id]
                ])
                ->update([
                    'name' => $infor_event_update['name'],
                    'slug' => $infor_event_update['slug'],
                    'date' => $infor_event_update['date']
                ]);
        } catch (Exception $ex) {
        }
    }


    //Function handler controller for api routes;
    public static function getEvents()
    {
        try {
            $events = Event::with('organizer')->where('events.date', '>=', date('Y/m/d'))->get();
            return $events;
        } catch (Exception $e) {
        }
    }


    // public static function getInforDetailEvent($organizer_slug, $event_slug)
    // {
    //     $infor_organizer = Organizer::where('organizers.slug', $organizer_slug)->first();
    //     $infor_event = Event::getInforEvent($infor_organizer->id, $event_slug);
    //     $event = [
    //         'id' => $infor_event->id,
    //         'name' => $infor_event->name,
    //         'slug' => $infor_event->slug,
    //         'date' => $infor_event->date,
    //         'channels' => [],
    //         'tickets' => [],
    //     ];
    //     $channel_list = DB::table('channels')
    //         ->where('channels.event_id', '=', $infor_event->id)
    //         ->select('channels.id', 'channels.name')
    //         ->get();
    //     for ($i = 0; $i < count($channel_list); $i++) {
    //         $infor_channel = [
    //             'id' => $channel_list[$i]->id,
    //             'name' => $channel_list[$i]->name,
    //             'rooms' => []
    //         ];
    //         $room_list = DB::table('rooms')
    //             ->where('rooms.channel_id', $channel_list[$i]->id)
    //             ->select('rooms.id', 'rooms.name', 'rooms.capacity')
    //             ->get();
    //         for ($i = 0; $i < count($room_list); $i++) {
    //             $infor_room = [
    //                 'id' => $room_list[$i]->id,
    //                 'name' => $room_list[$i]->name,
    //                 'capacity' => $room_list[$i]->capacity,
    //                 'sessions' => []
    //             ];
    //             $session_list = DB::table('sessions')
    //                 ->where('sessions.room_id', '=', $room_list[$i]->id)
    //                 ->select('sessions.id', 'sessions.title', 'description', 'sessions.speaker', 'start', 'end', 'type', 'cost')
    //                 ->get();
    //             $infor_room['sessions'] = $session_list;
    //             array_push($infor_channel['rooms'], $infor_room);
    //         }
    //         array_push($event['channels'], $infor_channel);
    //     }
    //     $ticket_list = DB::table('event_tickets')
    //         ->where('event_tickets.event_id', '=', $infor_event->id)
    //         ->select('event_tickets.id', 'event_tickets.name', 'event_tickets.special_validity')
    //         ->get();
    //     $event['tickets'] = $ticket_list;
    //     dd($event);
    //     return $event;
    // }
}
