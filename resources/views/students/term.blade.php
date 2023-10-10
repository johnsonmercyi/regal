@extends('schools.layout.schoollayout')

@section('title', 'Manage Active Students')

@section('content')

<div class="section center">
    <h5>Manage Active Students</h5>
    <div class="row z-depth-1 borderRound" >
        <form action="" id="fetchStudentTermStatus">
            <div class="col s12 m3 input-field">
                <select id="classId" class="browser-default">
                <option value=""> Choose Class </option>
                @foreach($allClass as $classroom)
                    <option value="{{ $classroom->id }}">{{ $classroom->level.$classroom->suffix}}</option>
                @endforeach
                </select>
                <!-- <label for="classroom" >Choose Class</label> -->
            </div>

            <div class="col s12 m3 input-field">
                <select id="sessionIdSelect" class="browser-default">
                    <option value=""> Choose Session </option>
                    @foreach($acadSession as $acadSess)
                        <option value="{{ $acadSess->id }}">{{ $acadSess->sessionName}}</option>
                    @endforeach
                </select>
                <!-- <label for="session" >Choose Session</label> -->
            </div>

            <div class="col s12 m3 input-field">
                <select id="termIdSelect" class="browser-default">
                    <option value="">Choose Term </option>
                    <option value="1"> First Term</option>
                    <option value="2"> Second Term</option>
                    <option value="3"> Third Term</option>
                </select>
                <!-- <label for="term" >Choose Term</label> -->
            </div>

            <div class="col s12 m3 center input-field">
                <button type="submit" id="loadStudentsStatusBtn" class="btn btn-default colCode">Load</button>
            </div>
            
            
        </form>
    </div>
</div>

<section id='studentsStatusSection' class='hide center mb-5'>

    <h5 id='classInfo'></h5>

    <button class="btn colCode" id="submitStudentsStatus" >Submit Students Status</button>
    
    <div class='row greyBack'>
        <div class='col offset-l1 l10 s12'>
            <table class="table display centered z-depth-1 mb-5 mt-5" id="studentsStatusTable">
                    <thead class="white-text colCode">
                        <th>S/No.</th>
                        <th>Reg. No.</th>
                        <th>Student Name</th>
                        <th>Status</th>
                    </thead>
                    <!-- <button class='btn btn-floating' ><i class='material-icons'>check</i></button> -->
                    <tbody class='center'></tbody>
            </table>
        </div>
    </div>
</section>


    <div id="failedModal" class="modal">

        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>

        <div class="modal-content">
            <div class="row ">
                <div class="col">
                    <h5></h5>
                </div>
            </div>
            <hr>
        </div>
    </div>

    <div id="selectStudents">
        @include('includes.layout.confirmModal')
    </div>

<!-- </div> -->

<script src="{{ asset('assets/js/studentsManager.js') }}" ></script>
@endsection