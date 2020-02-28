@extends('layouts.app')

@section('script')
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>tinymce.init({selector: 'textarea'});</script>
@endsection

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ url('/css/profile.css') }}"/>

    <div class="container emp-profile">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-md-4">
                <div class="profile-img">
                    <img src="{{ url('storage/'.$user->profile_picture) }}">
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
                    <form method="GET" action="{{ route('profileEdit') }}">
                        <input type="submit" class="btn btn-primary" value="Edit Profile"/>
                    </form>
                </div>
                <div class="profile-button">
                    <form method="GET" action="{{ route('allGalleries') }}">
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
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Your Friends</label><br/>
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

                                    <div class="col-md-8">
                                        <label>Pending Friend Requests</label><br/>

                                        @foreach($user->pendingFriends() as $user)
                                            <ul class="list-group list-group-horizontal">

                                                <form method="post"
                                                      action="{{ route('userShow', ['slug' => $user->profileRouteSlug()]) }}">
                                                    <button type="submit"
                                                            class="list-group-item list-group-item-action">{{ $user->name }} {{ $user->surname }}</button>
                                                </form>

                                                <form method="post"
                                                      action="{{ route('userAcceptFriendRequest', ['userId' => $user->id]) }}">
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="list-group-item list-group-item-action list-group-item-success">
                                                        Accept
                                                        Request
                                                    </button>
                                                </form>

                                                <form method="post"
                                                      action="{{ route('userFriendRequest', ['userId' => $user->id]) }}">
                                                    <button type="submit"
                                                            class="list-group-item list-group-item-action list-group-item-dark">
                                                        Refuse
                                                        Request
                                                    </button>
                                                </form>

                                            </ul>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container emp-profile">
        <form method="post" action="">
            @csrf
            <div class="col-md-12">
                <label>New Post</label><br/>
                <div class="form-group">
                    <textarea class="form-control" name="post" rows="5"></textarea>
                    <div class="col-md-2">
                        <input type="submit" class="btn btn-primary" value="Submit" style="margin-top: 5px"/>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <div class="container emp-profile">
        <h5>Your Feed</h5>
    </div>

    @if($posts->count() > 0)
        @foreach($posts as $post)
            <div class="container emp-profile">
                <p>{!! $post->post_body !!}</p>
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
    @endif

@endsection
