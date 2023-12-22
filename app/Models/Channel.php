<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'channels';
    protected $primaryKey = 'id';
    protected $fillable = ['event_id', 'name'];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'channel_id');
    }

    public function sessions()
    {
        return $this->hasManyThrough(Session::class, Room::class, 'channel_id', 'room_id');
    }
}
