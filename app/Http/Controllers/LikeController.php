<?php

namespace App\Http\Controllers;

use App\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function likePost(string $postId)
    {
        $this->handleLike('App\Post', $postId);
        return redirect()->back();
    }

    public function likePicture(string $pictureId)
    {
        $this->handleLike('App\Picture', $pictureId);
        return redirect()->back();
    }

    public function handleLike($type, $postId)
    {
        $existing_like = Like::withTrashed()->whereLikeableType($type)->whereLikeableId($postId)->whereUserId(Auth::id())->first();

        if (is_null($existing_like)) {
            Like::create([
                'user_id'       => Auth::id(),
                'likeable_id'   => $postId,
                'likeable_type' => $type,
            ]);
        } else {
            if (is_null($existing_like->deleted_at)) {
                $existing_like->delete();
            } else {
                $existing_like->restore();
            }
        }
    }
}
