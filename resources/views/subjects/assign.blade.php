@extends('schools.layout.schoollayout')

@section('title', 'Subject Students')

@section('content')

<div class="section center">
    <h4>Select Subject Students</h4>
    <div class="row z-depth-1 " >
        <form method="" action="" id="">
            <div class="col s12 m3 input-field">
                <select id="classId" class="browser-default">
                <option value=""> Choose Class </option>
                @foreach($allClass as $classroom)
                    <option value="{{ $classroom->id }}">{{ $classroom->level.$classroom->suffix}}</option>
                @endforeach
                </select>
                <!-- <label for="classroom" >Choose Class</label> -->
            </div>

            {{--<div class="col s12 m3 input-field">
                <select id="sessionIdSelect" class="browser-default">
                <option value=""> Choose Session </option>
                @foreach($acadSession as $acadSess)
                    <option value="{{ $acadSess->id }}">{{ $acadSess->sessionName}}</option>
                @endforeach
                </select>
                <!-- <label for="session" >Choose Session</label> -->
            </div>--}}

            <!-- <div class="col s12 m2 input-field">
                <select id="termId" class="browser-default">
                <option value="">Choose Term</option>
                <option value="1"> First Term </option>
                <option value="2"> Second Term </option>
                <option value="3"> Third Term </option>
                </select>
            </div> -->

            <div class="col s12 m4 input-field">
                <select id="subjectId" class="browser-default">
                <option value="">Choose  Subject </option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->title}}</option>
                @endforeach
                </select>
                <!-- <label for="subject" >Choose Subject</label> -->
            </div>

            <div class="col s12 m2 center input-field">
                <button type="submit" id="loadAssignedSubjectStudents" class="btn btn-default {{ $school->themeColor }}">Load</button>
            </div>
        </form>
    </div>
</div>

    <div class="progress hide">
        <div class="indeterminate {{$school->themeColor ?: 'blue' }}"></div>
    </div>

<!-- <div class="section display responsive-table z-depth-1 hide" id="scoreTable"> -->
<section id='assignTableSection' class='hide center mb-5'>
    <div class="section display z-depth-1 hide" id="tableError">
    <h3 class='center'>Unable to fetch selected class.</h3>
    </div>
    <h5 id='classInfo'></h5>
    <span><h6>Select All Students <button data-check="false" class='btn btn-floating grey' id="selectAllStudents"><i class='material-icons'>check</i></button></h6></span>
    
    <div class='row'>
        <div class='col offset-l2 l8 s12'>
            <table class=" display centered z-depth-1 mb-5 mt-5" id="assignListTable">
                    <thead class="white-text colCode" id="assignTableHead">
                        <th>S/No.</th>
                        <th>Reg. No.</th>
                        <th>Student Name</th>
                        <th>Assign</th>
                    </thead>
                    <!-- <button class='btn btn-floating' ><i class='material-icons'>check</i></button> -->
                    <tbody class='center' id="assignTableBody"></tbody>
            </table>
        </div>
    </div>
    <button class="btn colCode" id="submitAssignedStudents" >Submit Selected Students</button>
</section>


    <div id="failedModal" class="modal">

        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>

        <div class="modal-content">
            <div class="row ">
                <div class="col">
                    <h5>No teacher has been assigned to this subject. Do that and try again.</h5>
                </div>
            </div>
            <hr>
        </div>
    </div>

    <div id="selectStudents">
        @include('includes.layout.confirmModal')
    </div>


<!-- </div> -->
<script src="{{ asset('assets/js/subjectsManager.js') }}"></script>
@endsection