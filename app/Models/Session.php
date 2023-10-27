<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Session extends Model
{
    use HasFactory;
    protected $table = 'sessions';
    protected $primary_key = 'id';

    public static function getSessionsOfEvent($event_id)
    {
        try {
            //lấy ra danh sách các phiên của các phòng trong các kênh
            $session_list = DB::table('sessions')
                ->join('rooms', 'rooms.id', '=', 'sessions.room_id')
                ->join('channels', 'channels.id', '=', 'rooms.channel_id')
                ->where('channels.event_id', $event_id)
                ->select(
                    'sessions.*',
                    'channels.name as channel_name',
                    'rooms.name as room_name',
                    'rooms.id as room_id'
                )
                ->orderBy('sessions.start', 'asc')
                ->get();

            return $session_list;
        } catch (Exception $e) {
        }
    }

    public static function getCountSessionOfChannles($event_id)
    {
        try {
            //đếm số lượng phiên của từng kênh
            $count_session_of_channels = DB::table('sessions')
                ->join('rooms', 'rooms.id', '=', 'sessions.room_id')
                ->join('channels', 'channels.id', '=', 'rooms.channel_id')
                ->where('channels.event_id', $event_id)
                ->selectRaw('count(sessions.id) as count')
                ->groupBy('rooms.channel_id')
                ->get();
            return $count_session_of_channels;
        } catch (Exception $ex) {
        }
    }

    public static function createSession($room_id, $infor_session)
    {
        try {
            $status = DB::table('sessions')->insert([
                'room_id' => $room_id,
                'title' => $infor_session['title'],
                'description' => $infor_session['description'],
                'speaker' => $infor_session['speaker'],
                'start' => $infor_session['start'],
                'end' => $infor_session['end'],
                'type' => $infor_session['type'],
                'cost' => $infor_session['cost']
            ]);
            return $status;
        } catch (Exception $ex) {
        }
    }

    public static function getInforSession($session_id)
    {
        try {
            $infor_session =  DB::table('sessions')
                ->where('sessions.id', '=', $session_id)
                ->first();
            return $infor_session;
        } catch (Exception $ex) {
        }
    }


    public static function updateSession($room_id, $session_id, $infor_session_update)
    {
        try {
            $status = DB::table('sessions')
                ->where('id', $session_id)
                ->update([
                    'room_id' => $room_id,
                    'title' => $infor_session_update['title'],
                    'description' => $infor_session_update['description'],
                    'speaker' => $infor_session_update['speaker'],
                    'start' => $infor_session_update['start'],
                    'end' => $infor_session_update['end'],
                    'type' => $infor_session_update['type'],
                    'cost' => $infor_session_update['cost'],
                ]);
            return $status;
        } catch (Exception $ex) {
        }
    }

    public static function getCapacityRoomOfSession($list_id_room)
    {
        try {
            $list_capacity_room = DB::table('sessions')
                ->join('rooms', 'rooms.id', '=', 'sessions.room_id')
                ->whereIn('sessions.room_id', $list_id_room)
                ->get();
            return $list_capacity_room;
        } catch (Exception $ex) {
        }
    }

    public static function getCountAttendeeSession($list_id_room)
    {
        try {
            $list_count_attendee = DB::table('sessions')
                ->leftJoin('session_registrations', 'session_registrations.session_id', '=', 'sessions.id')
                ->whereIn('sessions.room_id', $list_id_room)
                ->selectRaw("count(session_registrations.id) as 'count_attendess'")
                ->groupBy('sessions.id')
                ->get();
            return $list_count_attendee;
        } catch (Exception $ex) {
        }
    }
}
