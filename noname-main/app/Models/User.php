<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'verified_via_discord',
        'discord_token',
        'wipeouts',
        'knockouts',
        'email',
        'password',
        'admin',
        'place_slots_left',
        'banned',
        'in_game',
        'last_seen',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_seen' => 'datetime',
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

    public function owned()
    {
        return $this->hasMany(Owned::class, 'userId');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'creator_id');
    }

    public function forums()
    {
        return $this->hasMany(Forum::class, 'posterId');
    }

    public function replies()
    {
        return $this->hasMany(Replies::class, 'posterId');
    }

    public function posts()
    {
        return $this->hasMany(Forum::class, 'posterId', 'id');
    }
    
    public function postCount()
    {
    return $this->posts()->count();
    }

    public function isActive()
    {
        return $this->last_seen && $this->last_seen->gt(now()->subMinutes(10));
    }

    public function friendsTo()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->withPivot('accepted')
            ->withTimestamps();
    }

    public function friendsFrom()
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id')
            ->withPivot('accepted')
            ->withTimestamps();
    }


    public function pendingFriendsTo()
    {
        return $this->friendsTo()->wherePivot('accepted', false);
    }

    public function pendingFriendsFrom()
    {
        return $this->friendsFrom()->wherePivot('accepted', false);
    }

    public function acceptedFriendsTo()
    {
        return $this->friendsTo()->wherePivot('accepted', true);
    }

    public function acceptedFriendsFrom()
    {
        return $this->friendsFrom()->wherePivot('accepted', true);
    }

    public function friends() {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->withPivot('accepted')
            ->withTimestamps();
    }

    public function pending() {
        return $this->pendingFriendsFrom->merge($this->pendingFriendsTo);
    }

}
