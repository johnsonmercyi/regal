{{-- @extends('layout') --}}
@extends('schools.layout.schoollayout')

@section('title', 'Capture Photo')

@section('content')

    
    <div>
        <h5 class="center">Capture Student Photo</h5>
    </div>
    
    
    <div class="row borderRound z-depth-1" id="selectDiv">
        <div class="col s12 m6 input-field">
            <select id="classSelect" class="browser-default">
                <option value="">Select Class</option>
                @foreach($allClass as $cla)
                    <option value="{{$cla->id}}">{{$cla->className()}}</option>
                @endforeach
            </select>
        </div>
        
        <div class="col s12 m6 input-field">
            <select id="studentSelect" class="browser-default">
                <option value="">Select Student</option>
            </select>
        </div>

        
        <div class="progress hide">
            <div class="indeterminate {{$school->themeColor ?: 'blue' }}"></div>
        </div>
    </div>

    <div class="row hide borderRound" id="studentName">
        <input type="hidden" id="student-id" >
        <div class="row m12 center">
            <h6></h6>
        </div>
    </div>

    <div id="passporttab" class="">          
        <div class="row">
            <div class="file-field input-field center col s12 m12">
                <div id='cameraIcon'>
                    <i class="large material-icons">camera_alt</i>
                    <br>
                    <h6>Browse Photo</h6>
                </div>
                    <img src='' id="src_img" class="hide">
                    <img src='' id="preview_compress_img" style="height:200px;width:150px" class="hide">
                <input type="file" accept="image/*" id="passportFile" class="validate" >
                <div class="file-path-wrapper hide">
                    <input type="text" class="file-path validate">
                </div>
            </div>
        </div>
        <div class="row center greyBack">
            <button type="button" class="btn colCode" id="studentPassportBtn">Save Photo</button>
        </div>
    </div>



        

<input type="hidden" id="schoolId" value="{{ $school->id}}">
<input type="hidden" id="sessionId" value="{{ $school->academic_session_id}}">
<input type="hidden" id="termId" value="{{ $school->current_term_id}}">
<script type="module" src="{{ asset('assets/js/captureManager.js')}}"></script>
@endsection

