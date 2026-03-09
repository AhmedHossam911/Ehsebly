<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    protected $fillable = ['event_id', 'from_participant_id', 'to_participant_id', 'from_user_id', 'to_user_id', 'amount', 'status'];

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_CONFIRMED = 'confirmed';

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function fromParticipant()
    {
        return $this->belongsTo(EventParticipant::class , 'from_participant_id');
    }

    public function toParticipant()
    {
        return $this->belongsTo(EventParticipant::class , 'to_participant_id');
    }
}
