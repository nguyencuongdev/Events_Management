<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'organizers';
    protected $hidden = ['password_hash'];
    protected $fillable = ['name', 'slug', 'email', 'password_hash'];

    public function events()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    public static function getInfor($email, $password)
    {
        $organizer = Organizer::where([
            ['email', $email],
            ['password_hash', $password]
        ])
            ->first();
        return $organizer;
    }
}
