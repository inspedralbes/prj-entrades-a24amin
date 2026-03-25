<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = ['event_zone_id', 'identifier', 'status', 'reserved_by', 'reserved_until'];

    public function zone()
    {
        return $this->belongsTo(EventZone::class , 'event_zone_id');
    }

    public function reserver()
    {
        return $this->belongsTo(User::class , 'reserved_by');
    }
}