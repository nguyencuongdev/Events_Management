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
        try {
            $attendee = Attendee::where([
                ['lastname', $last_name],
                ['registration_code', $registration_code]
            ])
                ->first();
            return $attendee;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function getInforByToken($token)
    {
        try {
            $attendee = Attendee::where('login_token', $token)->first();
            return $attendee;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function generateToken($attendee, $data_encode)
    {
        try {
            $token = md5($data_encode);
            $attendee->login_token = $token;
            $attendee->save();
            return $token;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function verifyToken($token)
    {
        try {
            $attendee = Attendee::where('login_token', $token)->first();
            if (!$attendee) return false;
            $attendee->login_token = '';
            $attendee->save();
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
