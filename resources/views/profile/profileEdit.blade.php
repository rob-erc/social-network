@extends('layouts.app')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ url('/css/profile.css') }}" />
    <div class="container emp-profile">
        <form method="post" action="{{ route('profileEdit') }}" enctype="multipart/form-data">
            @csrf
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
                        <img src="{{ url('storage/'.$user->profile_picture) }}"/>
                    </div>

                    <p>Upload Profile Image:</p>
                    <input type="file" name="profileImg" />
                    <br><br>

                </div>
                <div class="col-md-6">
                    <div class="profile-head">
                        <h5>
                            {{ $user->name }} {{$user->surname}}
                        </h5>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">About</a>
                            </li>
                        </ul>
                    </div>
                </div>
{{--                <div class="col-md-2">--}}
{{--                    <input type="submit" class="profile-edit-btn" name="btnAddMore" value="Edit Profile"/>--}}
{{--                </div>--}}
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="tab-content profile-tab" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Name</label>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name" value="{{ $user->name }}" placeholder="Name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Surname</label>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="surname" value="{{ $user->surname }}" placeholder="Surname">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Phone</label>
                                </div>
                                <div class="form-group">
                                        <input class="form-control" name="phone" type="tel" value="{{ $user->phone }}" placeholder="Phone">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Address</label>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" name="address" rows="3" placeholder="Address">{{ $user->address }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Birthday</label>
                                </div>
                                <div class="form-group">
                                        <input class="form-control" name="birthday" type="date" value="{{ $user->birthday }}" placeholder="Your date of birth">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Your Bio</label><br/>
                                    <div class="form-group">
                                        <textarea class="form-control" name="bio" rows="5" >{{ $user->bio }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <input type="hidden" name="_method" value="PUT"/>
                                    <button type="submit" class="profile-edit-btn" value="Submit">Submit</button>
                                </div>
                                <div class="col-md-2">
                                    <input type="submit" class="profile-edit-btn" id="cancel-btn" value="Cancel"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
