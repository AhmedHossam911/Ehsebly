<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    protected $fillable = ['event_id', 'user_id', 'guest_name', 'role'];

    public const ROLE_ORGANIZER = 'organizer';
    public const ROLE_MEMBER = 'member';

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isOrganizer()
    {
        return $this->role === self::ROLE_ORGANIZER;
    }
}
