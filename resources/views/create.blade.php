@extends('layout')

{{--content shown inside layout--}}
@section('content')

<br>
    <h1>Register Student</h1>
<div class="pull-right">
    <a href="{{route('display')}}" class="btn btn-primary mb-3">Back</a>
</div>
<form action="{{route('create')}}" method="post">
    @csrf
    <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Name</label>
        <input name="name" required type="text" class="form-control" id="exampleFormControlInput1" placeholder="Safaa Mammed">
    </div>
    <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Email address</label>
        <input required name="email" type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
    </div>
    <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Course</label>
        <input required name="study_course" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Computer Science">
    </div>
    <div class="mb-3">
        <label for="exampleFormControlTextarea1" class="form-label">Address</label>
        <textarea required name="address"class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary mb-3">Register</button>
    </div>
</form>
@endsection
