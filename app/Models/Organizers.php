<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Organizers extends Model
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
}
