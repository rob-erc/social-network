@extends('layouts.app')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ url('/css/profile.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('/css/gallery.css') }}"/>

    <div class="container emp-profile">
        <h2>All Galleries</h2>
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
            @foreach($user->galleries as $gallery)
                <div class="img-box">
                    @if($gallery->pictures()->first() == null)
                        <img src="{{ url('storage/folder.png') }}" alt=""/>
                    @else
                        <img src="{{ url("storage/".$gallery->pictures()->first()->path) }}" alt=""/>
                    @endif
                    <form method="get" action="{{ route('userGallery', ['slug' => $user->profileRouteSlug(), 'galleryId' => $gallery->id]) }}">
                        <button type="submit" class="btn btn-light">
                            <div class="transparent-box">
                                <div class="caption">
                                    <h2 id="img-header">{{ $gallery->name }}</h2>
                                </div>
                            </div>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

@endsection
