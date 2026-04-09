<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'location', 'event_date', 'image_url', 'director', 'genre'];

    public function zones()
    {
        return $this->hasMany(EventZone::class);
    }
}