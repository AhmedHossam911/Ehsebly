<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name', 'creator_id', 'date', 'guest_token'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->guest_token)) {
                $event->guest_token = strtoupper(substr(uniqid(), -6));
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class , 'creator_id');
    }

    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function settlements()
    {
        return $this->hasMany(Settlement::class);
    }

    public function isLocked()
    {
        return $this->settlements()
            ->whereIn('status', [\App\Models\Settlement::STATUS_PAID, \App\Models\Settlement::STATUS_CONFIRMED])
            ->exists();
    }
}
