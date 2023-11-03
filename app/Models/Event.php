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
}
