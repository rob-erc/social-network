<?php

namespace App\Http\Controllers;

use App\Gallery;
use App\Http\Requests\AddPicToGalleryRequest;
use App\Http\Requests\CreateGalleryRequest;
use App\Picture;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileGalleryController extends Controller
{
    public function index()
    {
        return view('gallery.profileGalleries');
    }

    public function show(Gallery $gallery)
    {
        return view('gallery.profileGallery', ['gallery' => $gallery]);
    }

    public function create(CreateGalleryRequest $request)
    {
        $user = Auth::user();
        if ($user->galleries()->count() == 0){
            Storage::makeDirectory("public/users/$user->id");
        }
        $gallery = new Gallery();
        $gallery->user_id = Auth::id();
        $gallery->name = $request->input('name');
        $gallery->save();

        Storage::makeDirectory("public/users/$user->id/$gallery->id");

        return back();
    }

    public function addPicture(AddPicToGalleryRequest $request)
    {
        $user = Auth::user();
        $picture = new Picture();
        $picture->gallery_id = $request->input('galleryId');

        $file = $request->file('image')->store("users/$user->id/$picture->gallery_id", ['disk' => 'public']);

        $picture->path = $file;
        $picture->save();

        return back();
    }

//    public function deletePicture(Picture $picture)
//    {
////        Picture::where(['id' => $pictureId])
////            ->first()
////            ->delete();
//        Storage::disk('public')->delete($picture->path);
//
//        $picture->delete();
//
//        return back();
//    }
}
