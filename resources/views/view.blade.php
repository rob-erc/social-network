@extends('layouts.app')

@section('content')


    <form action="/view" method="post" enctype="multipart/form-data">
        @csrf
        Select image to upload:
        <input type="file" name="example" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
    </form>


@endsection
