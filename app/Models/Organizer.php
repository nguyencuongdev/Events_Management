<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'organizers';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'slug',
        'email',
        'password_hash'
    ];

    public function events()
    {
        $this->hasMany(Event::class);
    }

    public static function getInforBySlug($slug)
    {
        try {
            $organizer = Organizer::query()->where('slug', $slug)->first();
            return $organizer;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
