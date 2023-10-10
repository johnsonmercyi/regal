@extends('schools.layout.schoollayout')

@section('title', 'Assign Subjects')

@section('content')

<div class="section center">
    <h5>Assign Subjects to {{$classroom->level.$classroom->suffix}} </h5>
</div>

    <div class="progress hide">
        <div class="indeterminate {{$school->themeColor ?: 'blue' }}"></div>
    </div>
    <input type="hidden" id="classId" value="{{$classroom->id}}">
<!-- <div class="section display responsive-table z-depth-1 hide" id="scoreTable"> -->
<section id='classAssignTableSection' class='center mb-5'>
    <div class="section display hide" id="tableError">
    <h6 class='center'>Unable to fetch selected class.</h6>
    </div>
    <h5 id='classInfo'></h5>
    
    <div class='row'>
        <div class='col m7 s12'>
            <h5><span id="countAssigned">{{count($assignedSubjects)}} </span>Assigned Subjects</h5>
            <table class=" display z-depth-1 mb-5 mt-5" id="assignedSubjectsTable">
                    <thead class="white-text colCode">
                        <th>Subject Title</th>
                        <th>Subject Teacher</th>
                        <th>Subject Type</th>
                        <th>Reassign</th>
                        <th>Remove</th>
                    </thead>
                    <tbody class=''>
                        @foreach($assignedSubjects as $subject)
                            <tr data-subject="{{$subject->subject_id}}" id="subject{{$subject->subject_id}}"><td class="subjectName">{{$subject->title}}</td>
                            <td class="teacherName">{{$subject->lastName.' '.$subject->firstName.' '.$subject->otherNames}}</td>
                            <td class="subjectType">{{$subject->subjectType}}</td>
                            <td><button class="reassignSect btn btn-floating btn-small right colCode" ><i class="material-icons">edit</i></button></td>
                            <td><button class="sectSubRemove btn btn-floating btn-small right red" ><i class="material-icons">close</i></button></td>
                            </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>

        <div class="col m5 s12">
            <h5>Unassigned Subjects <button class="btn colCode" id="assignMultiBtn">Assign</button></h5>
            
            <table class=" display  z-depth-1 mb-5 mt-5" id="unassignedSubjectsTable">
                    <thead class="white-text colCode">
                        <th>Select</th>
                        <th>Subject Title</th>
                        <th>Subject<br> Teacher</th>
                        <th>Subject Type</th>
                    </thead>
                    <tbody class='center'>
                        @foreach($unassignedSubjects as $subject)
                            <tr data-id="{{$subject->id}}" data-check="false" id="subject{{$subject->id}}">
                                <td><button class="grey center btn btn-small btn-floating"><i class="material-icons">check</i></button></td>
                                <td class="subjectName">{{$subject->title}}</td>
                                <td class="teacherCell">
                                    <select class="browser-default teacherSelect">
                                        <option value="">TEACHER</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{$teacher->id}}">{{$teacher->lastName.' '.$teacher->firstName.' '.$teacher->otherNames}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="subjectTypeCell">
                                    <select id="" class="browser-default subjectTypeSelect">
                                    <option value="">TYPE</option>
                                    <option value="Compulsory">Compulsory</option>
                                    <option value="Selective">Selective</option>
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>
    </div>
    @include('includes.layout.confirmModal')

</section>


    <div id="classAssignModal" class="modal">

        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>

        <div class="modal-content">
            <div class="row center">
                    <h4><span class="subjectName center"></span></h4>
                    <h6 id="showAssignModalError" class="hide red-text"><span class="center"> Please Select a Teacher and Type</span></h6>
                    <form id="classAssignForm">
                        <select id="teacherSelect" class="browser-default">
                            <option value="">--CHOOSE TEACHER--</option>
                            @foreach($teachers as $teacher)
                                <option value="{{$teacher->id}}">{{$teacher->lastName.' '.$teacher->firstName.' '.$teacher->otherNames}}</option>
                            @endforeach
                        </select>
                        <select id="subjectTypeSelect" class="browser-default">
                                <option value="">--CHOOSE SUBJ3CT TYPE--</option>
                                <option value="Compulsory">Compulsory</option>
                                <option value="Selective">Selective</option>
                        </select>
                    </form>
            </div>
            <hr>
        </div>
        <div class="progress hide">
            <div class="indeterminate {{$school->themeColor}}"></div>
        </div>
        <div class="modal-footer">
            <button class="btn" id="submitClassAssignedSubject">SUBMIT</button>
        </div>
    </div>

    <div id="subjectTeacherPage">
        @include('includes.layout.confirmModal')
    </div>
    <input type="hidden" name="section-id" id="sectionId" value="{{$section_id}}">
<!-- </div> -->

<script src="{{ asset('assets/js/subjectsManager.js') }}"></script>
@endsection