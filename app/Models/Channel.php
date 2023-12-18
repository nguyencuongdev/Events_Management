<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id',
        'name',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'channel_id');
    }
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
