@extends('schools.layout.schoollayout')

@section('title', 'Termly Newsletter')

    @section('content')

    <div class="section">
        <div class="row z-depth-1 borderRound">
        </div>    
    </div>
    <h5 class="center">Upload Termly Newsletter</h5>    
    <iframe src="/storage/images/photo/school/{{ $newsletter->file_name }}" style="width:750px;height:750px"></iframe>

    
    @endsection
    
    <!--This extends to the layout for other Js Usages-->
    
@section('dialog')
@endsection