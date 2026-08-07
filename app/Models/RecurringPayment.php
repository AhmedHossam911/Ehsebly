<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringPayment extends Model
{
    // TODO: Partially implemented — no controller, routes, or UI exist for users to
    // create/manage recurring payments, and the payments:process-recurring command
    // that consumes this model is not scheduled anywhere. Finish the feature or remove it.
    protected $fillable = ['user_id', 'title', 'amount', 'due_date', 'recurrence_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
