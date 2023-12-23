<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'sessions';
    protected $primaryKey = 'id';
    protected $fillable = ['room_id', 'title', 'speaker', 'start', 'end', 'type', 'cost', 'description'];
    protected $hidden = ['room_id'];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }

    public function session_registrations()
    {
        return $this->hasMany(SessionRegistration::class, 'session_id');
    }
}
