<?php

namespace App\Http\Controllers;

use App\Friendship;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function friendRequest(string $userId)
    {
        $auth = Auth::user();

        if ($auth->friendStatus($userId) == 'not friends') {
            if ($auth->id !== $userId) {
                $newFriendship = new Friendship();
                $newFriendship->first_user = $auth->id;
                $newFriendship->second_user = $userId;
                $newFriendship->acted_user = $auth->id;
                $newFriendship->status = 'pending';
                $newFriendship->save();

                return back();
            }
        }

        if ($auth->friendStatus($userId) == 'pending' || $auth->friendStatus($userId) == 'confirmed') {
            Friendship::where(['first_user' => $auth->id, 'second_user' => $userId])
                ->orWhere(['first_user' => $userId, 'second_user' => $auth->id])
                ->first()
                ->delete();

            if ($auth->ifFollowing($userId)) {
                $this->follow($userId);
                return back();
            } else {
                return back();
            }
        }
    }

    public function acceptFriendRequest(string $userId)
    {
        $auth = Auth::user();

        $friendship = Friendship::where(['first_user' => $userId, 'second_user' => $auth->id])->first();
        $friendship->acted_user = $auth->id;
        $friendship->status = 'confirmed';
        $friendship->save();

        if ($auth->ifFollowing($userId)) {
            return back();
        } else {
            $this->follow($userId);
            return back();
        }
    }

    public function follow(string $userId)
    {
        $auth = Auth::user();

        if ($auth->ifFollowing($userId)) {
            $auth->following()->detach($userId);
        } else {
            $auth->following()->attach($userId);
        }

        return back();
    }

    public function user(string $userId)
    {
        $user = User::find($userId);
        $allIDs = $user->following->pluck('id')->push($user->id);

        $allPosts = DB::table('posts')
            ->whereIn('user_id', $allIDs)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($user->id == Auth::id()) {
            return redirect(route('profileShow', [
                'slug' => $user->profileRouteSlug()
            ]));
        }
        return view('user.user', [
            'user' => $user,
            'posts' => $allPosts,
            //'ifFollowing' => $this->ifFollowing($userId)
        ]);
    }

    public function allUsers()
    {
        return view('user.users', ['users' => User::all()]);
    }
}
