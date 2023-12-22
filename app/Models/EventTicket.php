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

    public static function getInfor($id)
    {
        $ticket = EventTicket::find($id);
        return $ticket;
    }
}
