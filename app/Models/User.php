<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone_number',
        'uid',
        'instapay_link',
        'whatsapp',
        'theme_preference',
        'instagram',
        'facebook',
        'snapchat',
        'tiktok',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->uid)) {
                $user->uid = strtoupper(substr(uniqid(), -6)); // E.g: AB12CD
            }
        });
    }

    public function friends()
    {
        return $this->belongsToMany(User::class , 'friends', 'user_id', 'friend_id')
            ->wherePivot('status', 'accepted')
            ->withTimestamps();
    }

    public function friendRequestsSent()
    {
        return $this->hasMany(FriendRequest::class , 'sender_id');
    }

    public function friendRequestsReceived()
    {
        return $this->hasMany(FriendRequest::class , 'receiver_id');
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function recurringPayments()
    {
        return $this->hasMany(RecurringPayment::class);
    }

    public function getAvatarUrl()
    {
        if ($this->avatar) {
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }
            
            // Serve directly from storage route bypassing file system constraints
            return route('avatar.file', ['filename' => $this->avatar]);
        }
        
        if ($this->avatar_url) {
            return $this->avatar_url;
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random&color=fff';
    }
}
