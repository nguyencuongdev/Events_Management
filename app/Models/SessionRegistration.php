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

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public static function registrationSession($registration_id, $session_ids = [])
    {
        $listInforSessionRegisted = [];
        foreach ($session_ids as $session) {
            $listInforSessionRegisted[] = [
                'registration_id' => $registration_id,
                'session_id' => $session
            ];
        }
        SessionRegistration::insert($listInforSessionRegisted);
    }
}
