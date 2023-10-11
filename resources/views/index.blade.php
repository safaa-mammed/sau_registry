@extends('layout')

{{--content shown inside layout--}}
@section('content')
    <div class="pull-left">
        <h2>Student Registry</h2>
    </div>
    <div class="pull-right">
{{--        <a class="btn btn-outline-success" href="{{route('students.create')}}">Register Student</a>--}}
    </div>
{{--    <form action="{{route('searchStudent')}}" method="post">--}}
{{--        @csrf--}}
{{--        <div class="mb-3">--}}
{{--            <label for="exampleFormControlInput1" class="form-label">Search Student</label>--}}
{{--            <input type="checkbox" id="email" name="email" value="email">--}}
{{--            <label for="email">Email</label><br>--}}
{{--            <input type="checkbox" id="name" name="name" value="name">--}}
{{--            <label for="name">Name</label><br>--}}
{{--            <input name="name" required type="text" class="form-control" id="exampleFormControlInput1" placeholder="Safaa Mammed">--}}
{{--        </div>--}}
{{--    </form>--}}
    {{--display success message if student is found or not found--}}
    @if(!empty($message))
        <div class="alert alert-success"> {{ $message }}</div>
    @endif

{{--        if student data exist from search/display--}}
@if($hasStudent == 'True')
    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Address</th>
        </tr>
        </thead>
        <tbody>
        {{--    fetch the students variable from the controller--}}
        @foreach($students as $student)
            <tr>
                {{--                <td>{{$student->id}}</td>--}}
                <td>{{$student->name}}</td>
                {{--                <td>{{$student->email}}</td>--}}
                <td>{{$student->address}}</td>
                {{--                <td>{{$student->study_course}}</td>--}}
                {{--                <td>--}}
                {{--                    <a class="btn btn-outline-success" href="{{route('students.show',$student->id)}}">View</a>--}}
                {{--                    <a class="btn btn-outline-success" href="{{route('students.edit',$student->id)}}">Edit</a>--}}
                {{--                    <a class="btn btn-outline-success" href="{{route('students.destroy',$student->id)}}">Delete</a>--}}
                {{--                </td>--}}
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
{{--@if($students->links())--}}
{{--        {{$students->links()}}--}}
{{--@endif--}}

@endsection

