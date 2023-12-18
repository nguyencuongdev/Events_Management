<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'events';
    protected $primaryKey = 'id';
    protected $fillable = [
        'organizer_id',
        'name',
        'slug',
        'date'
    ];

    public function tickets()
    {
        return $this->hasMany(EventTicket::class, 'event_id');
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class, 'organizer_id')->select('id', 'name', 'slug');
    }

    public function channels()
    {
        return $this->hasMany(Channel::class, 'event_id');
    }

    public function registrations()
    {
        return $this->hasManyThrough(Registration::class, EventTicket::class, 'event_id', 'ticket_id');
    }

    public static function getInforEvent($organizer_id, $event_slug)
    {
        try {
            $infor_evnet = Event::where([
                ['slug', $event_slug],
                ['organizer_id', $organizer_id]
            ])->first();
            return $infor_evnet;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public static function getEvents()
    {
        try {
            $events = Event::with('organizer')
                ->where('date', '>=', date('Y-m-d'))
                ->get();
            return $events;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
