{{-- @extends('layout') --}}
@extends('schools.layout.schoollayout')

@section('title', 'Students List')

@section('content')

    <div class="datatableContainer">

        <div>
            <h5 class="center">Students List</h5>
        </div>

        <table class="display responsive-table white z-depth-1" id="studentData" style="border-radius: 2px;" width="100%">
            <thead class="center-align">
                <tr>
                    <th>S/No.</th>
                    <th>Photo</th>
                    <th>Reg. No.</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Gender</th>
                    <th>Action</th>
                </tr>

            </thead>

            <tbody>
                <?php $num = 1; ?>
                @foreach ($students as $student)
                    <tr>
                        <td>{{ $num++ }}</td>
                        <td><img class="circle" style="border:2px solid black;height:60px;width:60px" src="/storage/images/{{$school->prefix}}/passports/{{ $student->imgFile ?: 'no_image.jpg' }}" /></td>
                        <td>{{ $student->regNo }}</td>
                        <td><a href="/{{ $school->id }}/student/{{ $student->id }}">
                            {{ strtoupper($student->firstName.' '.$student->otherName.' '.$student->lastName) }} </a></td>
                        <td>{{ $student->level.$student->suffix }}</td>
                        <td>{{ $student->gender }}</td>
                        <td class="center-align">
                             {{-- <a href="javacript:void(0)" data-target="showStudentsModal" class="btn btn-floating waves-effect waves-light colCode lighten-1 col s6 modal-trigger viewStudents" id="{{ 'viewStudents'. $student->id }}" data-id="{{ $student->id }}"> --}}
                             <a href="/{{ $school->id }}/student/{{ $student->id }}" title="View Student" class="btn btn-floating waves-effect waves-light colCode" >
                                <i class="material-icons">pageview</i>
                            </a>
                            <a href="/{{ $school->id }}/students/{{ $student->id }}/edit" title="Edit Student" data-id="{{ $student->id }}" class="btn btn-floating waves-effect waves-light colCode">
                                <i class="material-icons">edit</i>
                            </a>
                            {{--<a href="javacript:void(0)" class="btn btn-floating waves-effect waves-light colCode lighten-1 col s6" data-id="{{ $student->id }}" id="{{ 'deleteStudent'. $student->id }}">
                                <i class="material-icons">delete</i>--}}
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <input type="hidden" id="schoolId" value="{{ $school->id}}">
<input type="hidden" id="sessionId" value="{{ $school->academic_session_id}}">
<input type="hidden" id="termId" value="{{ $school->current_term_id}}">
<script src="{{ asset('assets/js/studentsManager.js')}}"></script>

@endsection

@section('dialog')
    @include('includes.guardians.showGuardian')
@endsection 

