@extends('schools.layout.schoollayout')

@section('title', 'Generate Passwords')

@section('content')

<div class="section">
<div class="row z-depth-1 borderRound" >
    <form action="" id="">
        <div class="col s12 offset-m3 m4 input-field">
            <select id="classId" class="browser-default">
            <option value=""> Choose Class </option>
            @foreach($allClass as $classroom)
                <option value="{{ $classroom->id }}">{{ $classroom->level.$classroom->suffix}}</option>
            @endforeach
            </select>
            <!-- <label for="classroom" >Choose Class</label> -->
        </div>

        <div class="col s12 m3 center input-field">
            <button type="submit" id="generateBtn" style="height:auto" class="btn btn-default colCode">Generate Passwords</button>
        </div>   
    </form>
    </div>
</div>

    <div class="progress hide colCode">
        <div class="indeterminate colCode"></div>
    </div>

<!-- <div class="section display responsive-table z-depth-1 hide" id="scoreTable"> -->
    <div class="section display z-depth-1 hide" id="tableError">
    <h3 class='center'>Unable to fetch selected class.</h3>
    </div>
    
    <div id="pinsTableDiv" class="hide">
        <table class=" browser-default" id="studentPinsListTable">
                <thead class="white-text colCode" id="">
                    <th>S/No.</th>
                    <th>Student Name</th>
                    <th>Username/Reg. No.</th>
                    <th>Password</th>
                </thead>
                    
                <tbody class='center' id=""></tbody>
        </table>
    </div>


<script src="{{asset('assets/js/jspdf.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/passwordManager.js')}}"></script>
@endsection