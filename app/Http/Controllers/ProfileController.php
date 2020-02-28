<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();

        $allIDs = $user->following->pluck('id')->push(Auth::id());

        $allPosts = DB::table('posts')->whereIn('user_id', $allIDs)->orderBy('created_at', 'desc')->get();

        return view('profile.profile')->with(['user' => $user, 'posts' => $allPosts]);
    }

    public function edit()
    {
        return view('profile.profileEdit')->with(['user' => Auth::user()]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = User::where(['id' => Auth::id()])->first();
        $user->name = $request->input('name');
        $user->surname = $request->input('surname');
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->birthday = $request->input('birthday');
        $user->bio = $request->input('bio');
        $user->updated_at = Carbon::now();
        $user->save();

        if ($request->file() !== []){
            $path = $request->file('profileImg')->store('avatars', ['disk' => 'public']);

            $user = User::where(['id' => Auth::id()])->first();
            $user->profile_picture = $path;
            $user->updated_at = Carbon::now();
            $user->save();

        }

        return redirect(route('profileShow', ['slug' => Auth::user()->profileRouteSlug()]));
    }


}
