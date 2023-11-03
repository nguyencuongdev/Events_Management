<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionRegistration extends Model
{
    use HasFactory;

    public static function getSessionRegisted($id_registed_list = [])
    {
        try {
            $session_registed_list = DB::table('session_registrations')
                ->whereIn('registration_id', $id_registed_list)
                ->get();
            return $session_registed_list;
        } catch (Exception $e) {
        }
    }
}
