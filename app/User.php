<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Friendship;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'email', 'password', 'profile_picture'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function profileRouteSlug()
    {
        return $this->id . '-' . $this->name . '-' . $this->surname;
    }

    public function ifFollowing(string $userId)
    {
        $ifFollowing = DB::table('followers')
            ->where([['user_id', '=', $userId],['follower_id', '=', $this->id]])
            ->get();

        return ($ifFollowing->count() == 0) ? false : true;
    }

    public function friendStatus(string $userId)
    {
        $ifFriends = DB::table('friendships')
            ->where([
                ['first_user', '=', $this->id],
                ['second_user', '=', $userId]
            ])
            ->orWhere([
                ['first_user', '=', $userId],
                ['second_user', '=', $this->id]
            ])
            ->get();

        if ($ifFriends->count() == 0)
        {
            return 'not friends';
        }
        elseif ($ifFriends[0]->status == 'confirmed')
        {
            return 'confirmed';
        }
        else
        {
            return 'pending';
        }
    }

    //======================== functions to get friends attribute =========================

    // friendship that this user started
    protected function friendsOfThisUser()
    {
        return $this->belongsToMany(User::class, 'friendships', 'first_user', 'second_user')
            ->withPivot('status')
            ->wherePivot('status', 'confirmed');
    }

    // friendship that this user was asked for
    protected function thisUserFriendOf()
    {
        return $this->belongsToMany(User::class, 'friendships', 'second_user', 'first_user')
            ->withPivot('status')
            ->wherePivot('status', 'confirmed');
    }

    // accessor allowing you call $user->friends
    public function getFriendsAttribute()
    {
        if ( ! array_key_exists('friends', $this->relations)) $this->loadFriends();
        return $this->getRelation('friends');
    }

    protected function loadFriends()
    {
        if ( ! array_key_exists('friends', $this->relations))
        {
            $friends = $this->mergeFriends();
            $this->setRelation('friends', $friends);
        }
    }

    protected function mergeFriends()
    {
        if($temp = $this->friendsOfThisUser)
            return $temp->merge($this->thisUserFriendOf);
        else
            return $this->thisUserFriendOf;
    }

    public function friendRequests()
    {
        return $this->hasMany(Friendship::class, 'second_user')
            ->where('status', 'pending');
    }

    public function pendingFriends()
    {
        $users = [];

        foreach ($this->friendRequests as $request)
        {
            $users[] = User::where('id', $request->first_user)->first();
        }

        return $users;
    }
}
