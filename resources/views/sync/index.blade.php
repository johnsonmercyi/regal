@extends('schools.layout.schoollayout')

@section('title', 'Archive')

@section('content')

<div class="section center">
    <h5>Synchronize Data To Online Server</h5>
    <div class="row z-depth-1 center borderRound" >
        <form method="" action="" id="">
            <div class="col s12 m12 center input-field">
                <button class="btn colCode" id="startBtn">START SYNC</button>
                <button class="btn colCode" id="picsBtn">SYNC PHOTOS</button>
            </div>
        </form>
    </div>
</div>

    <div id="pixCon">
        <img src='' id="src_img" class="hide">
        <img src='' id="preview_compress_img" style="height:200px;width:150px" class="hide">
    </div>
    <input type='hidden' value="{{ $tables }}" id="tableInput" >

    <h4 id="syncStatus" class="hide center">Synchronized <span id="countSync">0</span> / <span id="lenTables">100%</span></h4>
    <div class="progress hide">
        <div class="indeterminate {{$school->themeColor ?: 'blue' }}"></div>
    </div>

<!-- </div> -->

<script type="module" src="{{asset('assets/js/syncManager.js')}}"></script>
@endsection