<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['event_id', 'description', 'total_amount', 'date'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function payers()
    {
        return $this->hasMany(ExpensePayer::class);
    }

    public function splits()
    {
        return $this->hasMany(ExpenseSplit::class);
    }
}
