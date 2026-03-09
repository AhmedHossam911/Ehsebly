<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseSplit extends Model
{
    protected $fillable = ['expense_id', 'expense_item_id', 'participant_id', 'amount'];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function participant()
    {
        return $this->belongsTo(EventParticipant::class , 'participant_id');
    }
}
