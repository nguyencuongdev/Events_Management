<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Event extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'events';
    protected $primaryKey = 'id';
    protected $fillable = ['organizer_id', 'name', 'slug', 'date'];

    //xác định các trường sẽ bị ẩn đi khi chuyển record thành json hoặc mảng;
    protected $hidden = ['organizer_id'];

    public function registrations()
    {
        return $this->hasManyThrough(Registration::class, EventTicket::class, 'event_id', 'ticket_id');
    }

    public function channels()
    {
        return $this->hasMany(Channel::class, 'event_id');
    }

    public function tickets()
    {
        return $this->hasMany(EventTicket::class, 'event_id');
    }

    public function rooms()
    {
        return $this->hasManyThrough(Room::class, Channel::class, 'event_id', 'channel_id');
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class, 'organizer_id');
    }

    public static function getEventsOfOrganizer($organizer_id)
    {
        $events = Event::where('organizer_id', $organizer_id)->get();
        return $events;
    }

    public static function getInfor($organizer_id, $event_slug)
    {
        $event = Event::where([
            ['organizer_id', $organizer_id],
            ['slug', $event_slug]
        ])
            ->first();
        return $event;
    }

    public static function getEvents()
    {
        $events = Event::with('organizer')
            ->where('date', '>=', date('Y-m-d'))
            ->get();
        return $events;
    }
}
