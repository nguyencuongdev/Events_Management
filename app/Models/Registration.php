<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Registration extends Model
{
    use HasFactory;
    protected $table = 'registrations';
    protected $primary_key = 'id';

    public static function registrationEvent($attendee_id, $id_ticket, $registration_time, $session_ids = [])
    {
        try {
            $id_registed = DB::table('registrations')->insertGetId([
                'attendee_id' => $attendee_id,
                'ticket_id' => $id_ticket,
                'registration_time' => $registration_time
            ]);

            if (count($session_ids) > 0) {
                foreach ($session_ids as $session_id) {
                    DB::table('session_registrations')->insert([
                        'registration_id' => $id_registed,
                        'session_id' => $session_id
                    ]);
                }
            }
        } catch (Exception $e) {
        }
    }

    public static function checkRegistratedEvent($tiket_id, $attendee_id)
    {
        try {
            $check_registed = DB::table('registrations')->where([
                ['registrations.ticket_id', $tiket_id],
                ['attendee_id', $attendee_id]
            ])->first();
            return $check_registed ? true : false;
        } catch (Exception $e) {
        }
    }
}
