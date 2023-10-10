{{-- @extends('layout') --}}
@extends('schools.layout.schoollayout')

@section('title', 'Register Student')

@section('content')
<div id="stdRegDiv">

<h4 class="center">Register Student</h4>
<form class="regForm" action="" method="POST" id="addForm" enctype="multipart/form-data">
    {{-- @csrf --}}
    
    
    @include('includes.students.form')
</form>

    <div id="disModal" class="modal">
        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>
        <div class="modal-content">
            <div class="progress hide">
                <div class="indeterminate {{$school->themeColor}}"></div>
            </div>
            <div id="modalSuccess" class="row hide">
                <!-- Header -->
                <h4 class="center">Submitted Successfully</h4>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn blue" id="profileLink">View Profile</a>
            <a href="/{{ $school->id }}/students/create" class="btn green">Register Another Student</a>
        </div>
    </div>

<input type="hidden" id="lgasControl" value="{{ $lgas }}" />
<script src="{{ asset('assets/js/camerasnap.js') }}" ></script>
</div>

@endsection

@section('dialog')
    @include('includes.guardians.showGuardian')
@endsection