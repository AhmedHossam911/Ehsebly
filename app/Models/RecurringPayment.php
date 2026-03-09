<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringPayment extends Model
{
    protected $fillable = ['user_id', 'title', 'amount', 'due_date', 'recurrence_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
