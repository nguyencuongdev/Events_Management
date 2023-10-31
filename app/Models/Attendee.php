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

    public static function updateInforLoginToken($username, $login_token)
    {
        try {
            DB::table('attendees')->where('username', $username)->update([
                'login_token' => $login_token
            ]);
        } catch (Exception $e) {
        }
    }

    public static function getLoginTokenAttendee($username)
    {
        try {
            $login_token = DB::table('attendees')->where('username', $username)->select('login_token')->first();
            return $login_token;
        } catch (Exception $e) {
        }
    }
}
