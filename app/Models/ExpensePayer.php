<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensePayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'participant_id',
        'amount',
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function participant()
    {
        return $this->belongsTo(EventParticipant::class , 'participant_id');
    }
}
