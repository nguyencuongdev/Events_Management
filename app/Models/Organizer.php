<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Organizer extends Model
{
    use HasFactory;

    protected $table = 'organizers';
    protected $primary_key = 'id';

    public static function getInforOrganizer($email)
    {
        try {
            $infor = DB::table('organizers')
                ->where('email', '=', $email)
                ->first();
            return $infor;
        } catch (Exception) {
        }
    }

    public static function getInforOrganizerBySlug($slug)
    {
        try {
            $infor = DB::table('organizers')
                ->where('slug', '=', $slug)
                ->first();
            return $infor;
        } catch (Exception) {
        }
    }

    public static function getOrganizersByEventID($event_ids)
    {
        try {
            $organizer_list = DB::table('organizers')
                ->join('events', 'events.organizer_id', '=', 'organizers.id')
                ->whereIn('events.id', $event_ids)
                ->selectRaw(
                    'organizers.id as "organizer_id", organizers.name as "organizer_name", organizers.slug as "organizer_slug"'
                )
                ->distinct()
                ->get();
            return $organizer_list;
        } catch (Exception $e) {
        }
    }
}
