<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'registrations';
    protected $fillable = ['attendee_id', 'ticket_id'];

    public function ticket()
    {
        return $this->belongsTo(EventTicket::class, 'ticket_id');
    }
    public function session_registrations()
    {
        return $this->hasManyThrough(SessionRegistration::class, 'registration_id');
    }
}
