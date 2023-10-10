@extends('schools.layout.schoollayout')

@section('title', 'Archive')

@section('content')

<div class="section center">
    <h5>Change User Password (<span class="red-text">in development</span>)</h5>
    <div class="row z-depth-1 center borderRound" >
        <form method="" action="" id="">
            <div class="col s12 m4 input-field">
                <input type="text" id="uname" class="greyInp" style="margin-bottom:0px" placeholder='Username'>
            </div>
            <div class="col s12 m4 input-field">
                <input type="password" id="pword" class="greyInp" style="margin-bottom:0px" placeholder='Password'>
            </div>

            <div class="col s12 m4 input-field">
                <button class="btn colCode" id="rePasswordBtn">Change/Add Password</button>
            </div>
        </form>
    </div>
</div>

    <div class="progress hide">
        <div class="indeterminate {{$school->themeColor ?: 'blue' }}"></div>
    </div>

   
    <div id="selectStudents">
        @include('includes.layout.confirmModal')
    </div>

<!-- </div> -->

<script type="module" src="{{asset('assets/js/loginManager.js')}}"></script>
@endsection