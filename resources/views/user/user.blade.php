@extends('layouts.app')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ url('/css/profile.css') }}"/>
    <div class="container emp-profile">

        <div class="row">
            <div class="col-md-4">
                <div class="profile-img">
                    <img src="{{ url('storage/'.$user->profile_picture) }}"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="profile-head">
                    <h5>
                        {{ $user->name }} {{$user->surname}}
                    </h5>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                               aria-controls="home" aria-selected="true">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#bio" role="tab"
                               aria-controls="profile" aria-selected="false">Bio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#friends" role="tab"
                               aria-controls="profile" aria-selected="false">Friends</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-2">
                <div class="profile-button">
                    @if(auth()->user()->ifFollowing($user->id))
                        <form method="POST" action="{{ route('userFollow', ['userId' => $user->id]) }}">
                            <input type="submit" class="btn btn-secondary" id="cancel-btn" value="Unfollow"/>
                        </form>
                    @else
                        <form method="POST" action="{{ route('userFollow', ['userId' => $user->id]) }}">
                            <input type="submit" class="btn btn-primary" value="Follow"/>
                        </form>
                    @endif
                </div>
                <div class="profile-button">
                    @if(auth()->user()->friendStatus($user->id) == 'confirmed')
                        <form method="POST" action="{{ route('userFriendRequest', ['userId' => $user->id]) }}">
                            <input type="submit" class="btn btn-secondary" id="cancel-btn" value="Unfriend"/>
                        </form>
                    @elseif(auth()->user()->friendStatus($user->id) == 'pending')
                        <form method="POST" action="{{ route('userFriendRequest', ['userId' => $user->id]) }}">
                            <input type="submit" class="btn btn-secondary" value="Cancel Friend Request"/>
                        </form>
                    @else
                        <form method="POST" action="{{ route('userFriendRequest', ['userId' => $user->id]) }}">
                            <input type="submit" class="btn btn-primary" value="Send Friend Request"/>
                        </form>
                    @endif
                </div>
                <div class="profile-button">
                    <form method="GET" action="{{ route('userGalleries', ['slug' => $user->profileRouteSlug()]) }}">
                        <input type="submit" class="btn btn-primary" value="Galleries"/>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="tab-content profile-tab" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Email</label>
                            </div>
                            <div class="col-md-6">
                                <p>{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Phone</label>
                            </div>
                            <div class="col-md-6">
                                <p>{{ $user->phone }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Address</label>
                            </div>
                            <div class="col-md-6">
                                <p>{{ $user->address }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Birthday</label>
                            </div>
                            <div class="col-md-6">
                                <p>{{ $user->birthday }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="bio" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Your Bio</label><br/>
                                <p>{{ $user->bio }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="friends" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <label>{{ $user->name }}`s Friends</label><br/>
                                    <ul class="list-group">
                                        @foreach($user->friends as $friend)

                                            <form method="get"
                                                  action="{{ route('userShow', ['slug' => $friend->profileRouteSlug()]) }}">
                                                <button type="submit"
                                                        class="list-group-item list-group-item-action">{{ $friend->name }} {{ $friend->surname }}</button>
                                            </form>

                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($posts as $post)
        <div class="container emp-profile">
            <p>{!! $post->post_body !!} </p>
            <p id="post-author">Author: {{ $post->author_full_name }}</p>
            <form method="post" action="{{ route('postLikePls', ['postId' => $post->id]) }}">
                @csrf

                @if($post->likes()->count() == 0)
                    <button type="submit" class="btn btn-primary"><img
                            src="https://img.icons8.com/cotton/2x/thumb-up.png" style=" height: 20px; width: auto;">
                    </button>
                @else
                    <button type="submit" class="btn btn-primary"><img
                            src="https://img.icons8.com/cotton/2x/thumb-up.png"
                            style=" height: 20px; width: auto;">{{ $post->likes()->count() }}</button>
                @endif
            </form>
        </div>
    @endforeach

@endsection
