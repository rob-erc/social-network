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
            @foreach(auth()->user()->galleries as $gallery)
                <div class="img-box">
                    @if($gallery->pictures()->first() == null)
                        <img src="{{ url('storage/folder.png') }}" alt=""/>
                    @else
                        <img src="{{ url("storage/".$gallery->pictures()->first()->path) }}" alt=""/>
                    @endif
                    <form method="get" action="{{ route('showGallery', ['gallery' => $gallery]) }}">
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
        <form method="post" action="{{ route('createGallery') }}">
            @csrf
            {{--            <button type="submit" class="btn btn-primary">Add new Gallery</button>--}}
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Add new
                Gallery
            </button>

            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">New Gallery</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Title:</label>
                                <input type="text" class="form-control" name="name">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
