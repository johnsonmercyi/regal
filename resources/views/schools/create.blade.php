@extends('schools.schoolMaster')
@section('contents')
    <div class="row z-depth-2">
        <h1 class="center">Create School</h1>
        {{-- <div class="alert" id="message" style="display: none"></div> --}}
        <form action="" method="POST" enctype="multipart/form-data" id="schoolForm">
            @include('schools.form')

            <div class="col s12" id="submitSchool">
                <button class="btn waves-effect waves-light" id="submitForm" name="action" >Submit</button>
            </div>
        </form> 
    </div>
@endsection
    
    


