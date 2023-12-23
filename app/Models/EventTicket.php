<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTicket extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'event_tickets';
    protected $fillable = ['event_id', 'name', 'cost', 'special_validity'];


    public function registrations()
    {
        return $this->hasMany(Registration::class, 'ticket_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public static function verifyTicket($ticket_id, $event_id)
    {
        $infor_ticket = EventTicket::where([
            ['id', $ticket_id],
            ['event_id', $event_id]
        ])->first();
        if (!$infor_ticket) return false;
        $special_validity = $infor_ticket->special_validity ?? null;
        if ($special_validity) {
            $value = json_decode($special_validity);
            $type =  $value->type;
            switch ($type) {
                case 'date':
                    return strtotime($value->date) < strtotime(date('Y-m-d'));
                case 'amount':
                    return $value->amount > 0;
                default:
                    return true;
            }
        }
        return true;
    }
    public static function getInfor($id)
    {
        $ticket = EventTicket::find($id);
        return $ticket;
    }
}
