@extends('layouts.app')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ url('/css/profile.css') }}"/>

    @if($users !== [])
        @foreach($users as $user)
            <div class="container emp-profile">
                <p>User id: {{ $user->id }}</p>
                <p>User full name: {{ $user->name }} {{ $user->surname }}</p>
                <div class="col-md-2">
                    <form method="GET" action="{{ route('userShow', ['slug' => $user->profileRouteSlug()]) }}">
                        <input type="submit" class="profile-edit-btn" value="Show"/>
                    </form>
                </div>
            </div>
        @endforeach
    @endif

@endsection
