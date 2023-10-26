<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Room extends Model
{
    use HasFactory;
    protected $table = 'rooms';
    protected $prima_key = 'id';

    public static function getInforRoom($channel_id, $room_name)
    {
        try {
            $infor_room = DB::table('rooms')
                ->where([
                    ['rooms.channel_id', '=', $channel_id],
                    ['rooms.name', '=', $room_name]
                ])
                ->first();
            return $infor_room;
        } catch (Exception $ex) {
        }
    }
    public static function getRoomsOfEvent($list_channel_id = [])
    {
        try {
            //lấy ra danh sách các room diễn ra các kênh của sự kiện
            $room_list = DB::table('rooms')
                ->whereIn('rooms.channel_id', $list_channel_id)
                ->select('rooms.name', 'rooms.capacity')
                ->get();
            return $room_list;
        } catch (Exception $e) {
        }
    }

    public static function getCapacityRooms()
    {
        try {
            $capacity_rooms = DB::table('rooms')->orderBy('rooms.name', 'asc')->get();
            return $capacity_rooms;
        } catch (Exception $ex) {
        }
    }

    public static function createRoom($infor_room)
    {
        try {
            $status = DB::table('rooms')
                ->insert([
                    'name' => $infor_room['name'],
                    'channel_id' => $infor_room['channel_id'],
                    'capacity' => $infor_room['capacity'],
                ]);
            return $status;
        } catch (Exception $ex) {
        }
    }
}
