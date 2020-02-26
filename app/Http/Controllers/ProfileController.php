<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
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
        DB::table('users')
            ->where('id', Auth::id())
            ->update([
                'name' => $request->input('name'),
                'surname' => $request->input('surname'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'birthday' => $request->input('birthday'),
                'bio' => $request->input('bio'),
                'updated_at' => Carbon::now()]);

        if ($request->file() !== []){
            $path = $request->file('profileImg')->store('avatars', ['disk' => 'public']);

            DB::table('users')
                ->where('id', Auth::id())
                ->update([
                    'profile_picture' => $path,
                    'updated_at' => Carbon::now()
                ]);
        }

        return redirect(route('profileShow', ['slug' => Auth::user()->profileRouteSlug()]));
    }


}
