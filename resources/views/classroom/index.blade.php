{{-- @extends('layout') --}}
@extends('schools.layout.schoollayout')

@section('title', 'Class List')

@section('content')


        <div>
            <h4 class="center">Class List</h4>
        </div>

        <div class="progress hide">
            <div class="indeterminate {{$school->themeColor ?: 'blue' }}"></div>
        </div>

        <table class="display white z-depth-1" id="classData" style="border-radius: 2px;" width="100%">
            <thead class="center-align">
                <tr>
                    <th class="center">S/No.</th>
                    <th class="center">Class</th>
                    <th class="center">Form Teacher</th>
                    <th class="center">Action</th>
                </tr>

            </thead>

            <tbody>

                    <?php $num=1; ?>
                @foreach ($allClass as $classroom)
                    <tr id="{{ $classroom->id }}" class="class{{ $classroom->id }}">
                        <td>{{$num++}}</td>
                        <td class="center" ><span class="classInfoName">{{ $classroom->level.$classroom->suffix }}</span>
                            
                        </td>
                        <td class="center">
                            <span class="teacherInfoName">{{ ($classroom->lastName.' '.$classroom->firstName.' '.$classroom->otherNames) ?? 'Not Assigned' }}</span>
                            
                        </td>
                        <td class="center">
                            <button id="" class="btn btn-small waves-effect waves-light {{$school->themeColor ?: 'blue'}} selectFormTeacher">
                                ASSIGN FORM TEACHER
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div id="formAssignModal" class="modal">
            <button class="modal-close waves-effect waves-light btn-flat right" id="close">
                <i class="material-icons">close</i>
            </button>
            <h5 id='classInfo' class="center"></h5>

            <div class="modal-content">
                <div class="row center">
                        <h6 id="showAssignModalError" class="hide red-text"><span class="center"> Please Select a Teacher</span></h6>
                        <form id="formAssignForm">
                            <select id="formTeacherSelect" class="browser-default">
                                <option value="">--CHOOSE TEACHER--</option>
                                @foreach($notFormTeachers as $teacher)
                                <option value="{{$teacher->id}}">{{$teacher->lastName.' '.$teacher->firstName.' '.$teacher->otherNames}}</option>
                                @endforeach
                            </select>
                        </form>
                </div>
                <hr>
            </div>
            <div class="progress hide">
                <div class="indeterminate {{$school->themeColor}}"></div>
            </div>
            <div class="modal-footer">
                <button class="btn" id="submitAssignedFormTeacher">SUBMIT</button>
            </div>
        </div>

<input type="hidden" id="schoolId" value="{{ $school->id}}">
<input type="hidden" id="sessionId" value="{{ $school->academic_session_id}}">
<input type="hidden" id="termId" value="{{ $school->current_term_id}}">
<script src="{{ asset('assets/js/studentsManager.js')}}"></script>
@endsection

{{--@section('dialog')
    @include('includes.guardians.showGuardian')
@endsection --}}

