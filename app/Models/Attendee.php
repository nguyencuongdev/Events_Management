<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  Illuminate\Support\Facades\DB;

class Attendee extends Model
{
    use HasFactory;
    protected $table = 'attendees';
    protected $prima_key = 'id';

    public static function getInfor($username)
    {
        try {
            $infor_attendees = DB::table('attendees')->where('username', $username)->first();
            return $infor_attendees;
        } catch (Exception $e) {
        }
    }

    public static function deleteLoginToken($login_token, $login_token_update)
    {
        try {
            DB::table('attendees')->where('login_token', $login_token)->update([
                'login_token' => $login_token_update
            ]);
        } catch (Exception $e) {
        }
    }

    public static function saveLoginToken($username, $token)
    {
        try {
            DB::table('attendees')->where('username', $username)->update([
                'login_token' => $token
            ]);
        } catch (Exception $e) {
        }
    }

    public static function getInforAttendeeByLoginToken($token)
    {
        try {
            $infor_attendee = DB::table('attendees')->where('login_token', $token)->first();
            return $infor_attendee;
        } catch (Exception $e) {
        }
    }
}
