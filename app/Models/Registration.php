<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'registrations';
    protected $primaryKey = 'id';
    protected $fillable = [
        'attendee_id',
        'ticket_id',
    ];

    public function attendee()
    {
        return $this->belongsTo(Attendee::class, 'attendee_id');
    }

    public function ticket()
    {
        return $this->belongsTo(EventTicket::class, 'ticket_id');
    }

    public function session_registrations()
    {
        return $this->hasMany(SessionRegistration::class, 'registration_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public static function registrationEvent($ticket_id, $attendee_id)
    {
        $id_registration = Registration::insertGetId([
            'ticket_id' => $ticket_id,
            'attendee_id' => $attendee_id,
            'registration_time' => date('Y-m-d')
        ]);
        return $id_registration;
    }
}
