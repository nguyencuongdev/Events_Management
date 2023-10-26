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

    public static function getSessionsOfEvent($list_channel_id = [])
    {
        try {
            //lấy ra danh sách các phiên của các phòng trong các kênh
            $session_list = DB::table('sessions')
                ->join('rooms', 'rooms.id', '=', 'sessions.room_id')
                ->join('channels', 'channels.id', '=', 'rooms.channel_id')
                ->whereIn('channels.id', $list_channel_id)
                ->select(
                    'sessions.*',
                    'channels.name as channel_name',
                    'rooms.name as room_name',
                )
                ->get();

            return $session_list;
        } catch (Exception $e) {
        }
    }

    public static function getCountSessionOfChannles($list_channel_id = [])
    {
        try {
            //đếm số lượng phiên của từng kênh
            $count_session_of_channels = DB::table('sessions')
                ->join('rooms', 'rooms.id', '=', 'sessions.room_id')
                ->whereIn('rooms.channel_id', $list_channel_id)
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
}
