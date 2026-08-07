<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendTransaction extends Model
{
    protected $fillable = ['lender_id', 'borrower_id', 'amount', 'description', 'date'];

    public function lender()
    {
        return $this->belongsTo(User::class, 'lender_id');
    }

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function scopeBetween($query, $userAId, $userBId)
    {
        return $query->where(function ($q) use ($userAId, $userBId) {
            $q->where(['lender_id' => $userAId, 'borrower_id' => $userBId])
                ->orWhere(['lender_id' => $userBId, 'borrower_id' => $userAId]);
        });
    }
}
