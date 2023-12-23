<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionRegistration extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'session_registrations';
    protected $primaryKey = 'id';
    protected $fillable = ['registration_id', 'session_id'];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }
}
