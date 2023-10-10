@extends('schools.layout.schoollayout')

@section('title', 'View Assignments')

    @section('content')

    <div class="row borderRound">
        <h4 class="center">View Assigments</h4>
    </div>

    <div class="row">
        @foreach($assignments as $assign)
            <div class="col s12 m4">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title colCode white-text center">{{ $assign->title }}</span>
                        <p>Class: {{ $assign->level.' '.$assign->suffix }}</p>
                        <p>Submit on: {{ $assign->date_of_submission }}</p>                    
                    </div>
                    <div class="">
                        <a href="/assignments/download/{{ $assign->file_name }}" style="width:100%" class="btn colCode" data-file="{{ $assign->file_name }}">Download</a>                    
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script type="module" src="{{asset('assets/js/assignmentsManager.js')}}"></script>


    @endsection
    
    <!--This extends to the layout for other Js Usages-->
    
@section('dialog')
@endsection