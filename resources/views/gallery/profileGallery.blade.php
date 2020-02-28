@extends('layouts.app')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ url('/css/profile.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('/css/gallery.css') }}"/>

    <div class="container emp-profile">
        <h2>{{ $gallery->name }}</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            <div class="gallery-image">
                @foreach($gallery->pictures as $picture)
                    <div class="img-box">
                        <a data-toggle="modal" data-target="{{ '#modal'.$picture->id }}"><img src="{{ url("storage/$picture->path") }}" style="background: transparent; border: none !important; font-size:0;"/></a>
                    </div>
                    <div class="modal fade" id="{{ 'modal'.$picture->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <!--Content-->
                            <div class="modal-content">
                                <!--Body-->
                                <div class="modal-body">
                                    <img class="img-fluid" src="{{ url("storage/$picture->path") }}"/>
                                </div>
                                <!--Footer-->
                                <div class="modal-footer justify-content-left">
                                    <button type="button" class="btn btn-outline-primary btn-rounded btn-md ml-4" data-dismiss="modal">
                                        Close
                                    </button>
                                    <form method="post" action="{{ route('deletePicture', ['galleryId' => $gallery->id, 'pictureId' => $picture->id]) }}">
                                        @method('delete')
                                        <button type="submit" class="btn btn-outline-danger btn-rounded btn-md ml-4" >
                                            Delete
                                        </button>
                                    </form>
                                    <form  method="post" action="{{ route('likePicture', ['pictureId' => $picture->id]) }}">
                                        @csrf

                                        @if($picture->likes()->count() == 0)
                                            <button type="submit" class="btn btn-primary"><img
                                                    src="https://img.icons8.com/cotton/2x/thumb-up.png" style=" height: 20px; width: auto;">
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-primary"><img
                                                    src="https://img.icons8.com/cotton/2x/thumb-up.png"
                                                    style=" height: 20px; width: auto;">{{ $picture->likes()->count() }}</button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        <form method="post" action="{{ route('addPicToGallery', ['galleryId' => $gallery->id]) }}" enctype="multipart/form-data">
            <input type="file" name="image" required>
            <button type="submit" class="btn btn-primary">Add Image</button>
        </form>
    </div>

@endsection
