@extends('schools.layout.schoollayout')

@section('title', 'Archive')

@section('content')

<div class="section center">
    <h5>Archive of Removed Students/Staff</h5>
    <div class="row z-depth-1 borderRound" >
        <form method="" action="" id="">
            <div class="col offset-m2 s12 m4 input-field">
                <input type="text" id="searchName" class="greyInp" style="margin-bottom:0px">
            </div>

            <div class="col s12 m4 center input-field">
                <button class="btn colCode" id="studentBtn" >SEARCH STUDENT</button>
                <button class="btn colCode" id="searchStaffBtn" >SEARCH STAFF</button>
            </div>
        </form>
    </div>
</div>

    <div class="progress hide">
        <div class="indeterminate {{$school->themeColor ?: 'blue' }}"></div>
    </div>

<!-- <div class="section display responsive-table z-depth-1 hide" id="scoreTable"> -->
<section id='archivedStudentsSection' class='center mb-5 hide'>
    <div class="section display z-depth-1 hide" id="tableError">
    </div>
    
    
    <h5 id=''>Archived Students</h5>
    
    <div class='row greyBack'>
        <div class='col offset-l1 l10 s12'>
            <table class=" display centered z-depth-1 mb-5 mt-5" id="archiveStudentsTable">
                <thead class="white-text colCode" id="classMembersHead">
                    <th>S/No.</th>
                    <th>Reg. No.</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Session</th>
                    <th>Action</th>
                </thead>
                <!-- <button class='btn btn-floating' ><i class='material-icons'>check</i></button> -->
                <tbody class='center' id="">
                    
                </tbody>
            </table>
        </div>
    </div>
    
</section>


<section id='archivedStaffSection' class='center mb-5 hide'>
    <div class="section display z-depth-1 hide" id="tableError">
    </div>
    
    
    <h5 id=''>Archived Staff</h5>
    
    <div class='row greyBack'>
        <div class='col offset-l1 l10 s12'>
            <table class=" display centered z-depth-1 mb-5 mt-5" id="archiveStaffTable">
                <thead class="white-text colCode" id="classMembersHead">
                    <th>S/No.</th>
                    <th>Reg. No.</th>
                    <th>Staff Name</th>
                    <th>Action</th>
                </thead>
                <!-- <button class='btn btn-floating' ><i class='material-icons'>check</i></button> -->
                <tbody class='center' id="">
                    
                </tbody>
            </table>
        </div>
    </div>
    
</section>

       
<div id="restoreStudentsModal" class="modal ">

    <button class="modal-close waves-effect waves-light btn-flat right" id="close">
        <i class="material-icons">close</i>
    </button>

    <div class="modal-heaader">
        <h5 class="center " id="studentName"></h5>
    </div>

    <div class="modal-content">

        <!-- Header -->
        <form action="" id="generalValuesForm">
            <div class="row">
                <div class="col s12 m6 input-field">
                    <select id="sessionIdSelect" class="browser-default">
                        <option value=""> Choose Session </option>
                        @foreach($acadSession as $acadSess)
                            <option value="{{ $acadSess->id }}">{{ $acadSess->sessionName}}</option>
                        @endforeach
                    </select>
                    <!-- <label for="session" >Choose Session</label> -->
                </div>
                <div class="input-field col s12 m6">
                    <select class="browser-default" id="termIdSelect">
                        <option value=""> Choose Term </option>
                        <option value="1"> First Term </option>
                        <option value="2"> Second Term </option>
                        <option value="3"> Third Term </option>
                    </select>
                </div>
                <div class="input-field col s12 m6">
                    <select class="browser-default" id="classSelect">
                        <option value=""> Choose New Class </option>
                        @foreach($allClass as $classroom)
                            <option value="{{ $classroom->id }}">{{ $classroom->level.$classroom->suffix}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    <div class="modal-footer">
        <button class="btn colCode" id="restoreBtn">Restore</button>
    </div>

</div>


    
    <div id="selectStudents">
        @include('includes.layout.confirmModal')
    </div>

<!-- </div> -->

<script src="{{ asset('assets/js/archiveManager.js') }}" ></script>
@endsection