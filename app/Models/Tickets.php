<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tickets extends Model
{
    use HasFactory;
    protected $table = 'event_tickets';
    protected $prima_key = 'id';

    public static function getTicketsOfEvent($event_id)
    {
        try {
            //lấy ra danh sách các loại vé của sự kiện
            $ticket_list = DB::table('event_tickets')
                ->where('event_id', '=', $event_id)
                ->get();
            return $ticket_list;
        } catch (Exception $ex) {
        }
    }
}
