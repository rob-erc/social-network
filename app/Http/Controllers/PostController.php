<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'post'=> 'required'
        ]);

        DB::table('posts')
            ->insert([
                'user_id' => Auth::id(),
                'author_full_name' => Auth::user()->name . ' ' . Auth::user()->surname,
                'post_body' => $request->input('post'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

        return back();
    }
}
