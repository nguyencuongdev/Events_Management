<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Channels extends Model
{
    use HasFactory;

    protected $table = 'channels';
    protected $prima_key = 'id';

    public static function getChannelsOfEvent($event_id)
    {
        try {
            $channel_list = DB::table('channels')
                ->leftJoin('rooms', 'rooms.channel_id', '=', 'channels.id')
                ->where('channels.event_id', '=', $event_id)
                ->selectRaw('channels.id,channels.name, count(rooms.id) as count_room')
                ->groupBy('channels.id', 'channels.name')
                ->get();
            return $channel_list;
        } catch (Exception $e) {
        }
    }

    public static function getInforChannel($event_id, $channel_name)
    {
        try {
            $infor_channel = DB::table('channels')
                ->where([
                    ['channels.event_id', '=', $event_id],
                    ['channels.name', '=', $channel_name]
                ])
                ->first();
            return $infor_channel;
        } catch (Exception $ex) {
        }
    }

    public static function createChannel($event_id, $name)
    {
        try {
            $status = DB::table('channels')
                ->insert([
                    'event_id' => $event_id,
                    'name' =>  $name,
                ]);
            return $status;
        } catch (Exception $ex) {
        }
    }

    public static function getChannelsAndRoomOfChannel($event_id)
    {
        try {
            $channel_and_room_list = DB::table('channels')
                ->leftJoin('rooms', 'rooms.channel_id', '=', 'channels.id')
                ->where('channels.event_id', '=', $event_id)
                ->selectRaw('channels.name as channel_name, rooms.id, rooms.name')
                ->get();
            return $channel_and_room_list;
        } catch (Exception $ex) {
        }
    }
}
