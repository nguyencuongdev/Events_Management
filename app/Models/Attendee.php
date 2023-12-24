<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'email',
        'registration_code',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'attendee_id');
    }

    public static function getInfor($last_name, $registration_code)
    {
        $attendee = Attendee::where([
            ['lastname', $last_name],
            ['registration_code', $registration_code]
        ])
            ->first();
        return $attendee;
    }

    public static function getInforByToken($token)
    {
        $attendee = Attendee::where('login_token', $token)->first();
        return $attendee;
    }

    public static function generateToken($attendee, $data_encode)
    {
        $token = md5($data_encode);
        $attendee->login_token = $token;
        $attendee->save();
        return $token;
    }

    public static function verifyToken($token)
    {
        $attendee = Attendee::where('login_token', $token)->first();
        if (!$attendee) return false;
        $attendee->login_token = '';
        $attendee->save();
        return true;
    }
}
