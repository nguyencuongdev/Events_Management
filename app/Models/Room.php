<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'rooms';
    protected $primaryKey = 'id';
    protected $hidden = ['channel_id'];
    protected $fillable = ['channel_id', 'name', 'capacity'];

    public function sessions()
    {
        return $this->hasMany(Session::class, 'room_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }
}
