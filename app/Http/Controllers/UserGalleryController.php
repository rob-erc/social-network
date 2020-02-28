<?php

namespace App\Http\Controllers;

use App\Gallery;
use App\User;
use Illuminate\Http\Request;

class UserGalleryController extends Controller
{
    public function index(string $userId)
    {
        $user = User::where(['id' => $userId])->first();

        return view('gallery.userGalleries', ['user' => $user]);
    }

    public function show(string $userId, int $galleryId)
    {
        $user = User::where(['id' => $userId])->first();

        $gallery = Gallery::where(['id' => $galleryId])->first();

        return view('gallery.userGallery', ['user' => $user, 'gallery' => $gallery]);
    }
}
