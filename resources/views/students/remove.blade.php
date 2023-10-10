@extends('schools.layout.schoollayout')

@section('title', 'Remove Student')

@section('content')

<div class="section center">
    <h5>Remove/Delete Students </h5>
    <div class="row z-depth-1 borderRound" >
        <form method="" action="" id="">
            <div class="col offset-m3 s12 m4 input-field">
                <select id="classId" class="browser-default">
                <option value=""> Choose Class </option>
                @foreach($allClass as $classroom)
                    <option value="{{ $classroom->id }}">{{ $classroom->level.$classroom->suffix}}</option>
                @endforeach
                </select>
                <!-- <label for="classroom" >Choose Class</label> -->
            </div>

            <div class="col s12 m2 center input-field">
                <button type="submit" id="loadRemoveClassStudents" class="btn btn-default {{ $school->themeColor }}">Load</button>
            </div>
        </form>
    </div>
</div>

    <div class="progress hide">
        <div class="indeterminate {{$school->themeColor ?: 'blue' }}"></div>
    </div>

<!-- <div class="section display responsive-table z-depth-1 hide" id="scoreTable"> -->
<section id='classStudentsSection' class='hide center mb-5'>
    <div class="section display z-depth-1 hide" id="tableError">
    </div>

    <div class="row greyBack">
        <div class="col offset-m4 s12 m4 input-field">
            <button class="btn colCode" id="submitRemovedStudents" >Remove Selected Student(s)</button>
        </div>
    </div>
    
    <h5 id='classInfo'></h5>
    
    <div class='row greyBack'>
        <div class='col offset-l1 l10 s12'>
            <table class=" display centered z-depth-1 mb-5 mt-5" id="classStudentsTable">
                    <thead class="white-text colCode" id="classMembersHead">
                        <th>S/No.</th>
                        <th>Reg. No.</th>
                        <th>Student Name</th>
                        <th>Select</th>
                    </thead>
                    <!-- <button class='btn btn-floating' ><i class='material-icons'>check</i></button> -->
                    <tbody class='center' id="classStudentsBody"></tbody>
            </table>
        </div>
    </div>
</section>


    
    <div id="selectStudents">
        @include('includes.layout.confirmModal')
    </div>

<!-- </div> -->

<script src="{{ asset('assets/js/studentsManager.js') }}" ></script>
@endsection