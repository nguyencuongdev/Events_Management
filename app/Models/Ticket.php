<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ticket extends Model
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

    public static function createTicket($event_id, $infor_ticket)
    {
        try {
            $status = DB::table('event_tickets')
                ->insert([
                    'event_id' => $event_id,
                    'name' => $infor_ticket['name'],
                    'cost' => $infor_ticket['cost'],
                    'special_validity' => $infor_ticket['special_validity']
                ]);
            return $status;
        } catch (Exception $ex) {
        }
    }

    public static function verifyTicket($ticket_id, $registration_time)
    {
        try {
            $infor_ticket = DB::table('event_tickets')->where('event_tickets.id', $ticket_id)->first();
            $check_ticket = true;
            if ($infor_ticket) {
                $special_validity = $infor_ticket->special_validity ?? null;
                if ($special_validity) {
                    $value_special_validity = json_decode($special_validity);
                    switch ($value_special_validity->type) {
                        case 'amount':
                            $check_ticket = $value_special_validity->amount > 0 ? true : false;
                            break;
                        case 'date':
                            $check_ticket =  strtotime($value_special_validity->date) >
                                strtotime($registration_time) ? true : false;
                            break;
                    }
                } else $check_ticket = true;
            } else $check_ticket = false;
            return $check_ticket;
        } catch (Exception $ex) {
        }
    }
}
