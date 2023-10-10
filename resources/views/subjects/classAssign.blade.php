{{-- @extends('layout') --}}
@extends('schools.layout.schoollayout')

@section('title', 'Sections List')

@section('content')

    <!-- <div class="container"> -->

    <div class="progress hide">
        <div class="indeterminate {{$school->themeColor ?: 'blue' }}"></div>
    </div>

        <ul class="collection with-header" id="sectionCollectionList">
        <li class="collection-item white-text" style="background-color: {{ $school->themeColor ?: 'blue' }}"><h4>Sections</h4></li>
            @foreach($schoolSections as $sec)
                <li class="collection-item" ><span class="sectionName">{{$sec->sectionName}}</span>
                <button class="selectSectionAssign btn colCode right" data-type="{{$sec->sectionTypeId}}" data-id="{{$sec->id}}">ASSIGN SUBJECTS</button>
                </li>
            @endforeach
        </ul>

    <section id="classTableSection" class=" section hide">
        <div class="row">
            <button class="btn colCode" id="sectionReturn">Back</button>
        </div>
        <h5 id="sectionTitle" class="center"></h5>

        <ul class="collection with-header center" id="sectionCollectionList">
            <li class="collection-item"><h5>Assign Compulsory Subjects to Section</h5>
            <button id="compulsorySectionAssign" class=" btn colCode" data-type="" data-id="">ASSIGN SUBJECTS</button>        
            </li>
        </ul>



    <div class="row center">
        <h5 class="col l12">Assign Subjects to Classes</h5>
        <table class="display responsive-table white z-depth-1" id="classSubjectsTable" style="border-radius: 2px;" width="100%">
            <thead class="center-align">
                <tr>
                    <th class="center">Class</th>
                    <th class="center">Assign Subjects</th>
                </tr>

            </thead>

            <tbody></tbody>
        </table>
    </div>

        {{--<table class="display responsive-table white z-depth-1" id="" style="border-radius: 2px;" width="100%">
            <thead class="center-align">
                <tr>
                    <th class="center">Class</th>
                    <th class="center">Assign Subjects</th>
                </tr>

            </thead>

            <tbody>

                @foreach ($allClass as $classroom)
                    <tr>
                        <td class="center">{{ $classroom->level.$classroom->suffix }}</td>
                        <td class="center">
                            <a href="/subjects/class/{{ $school->id }}/{{ $classroom->id }}/{{$school->academic_session_id}}/{{$school->current_term_id}}" class="btn btn-floating waves-effect waves-light {{$school->themeColor ?: 'blue'}} lighten-1 col s6">
                                <i class="material-icons">edit</i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>--}}
    </section>


    <section id='sectionAssignTableSection' class='center mb-5  hide'>
        <div class="section display hide" id="tableError">
        <h6 class='center'>Unable to fetch selected class.</h6>
        </div>
        <h5 id='classInfo'></h5>
        
        <div class='row'>
            <div class='col l8 m8 s12'>
                <h5><span id="sectionSubTitle"></span> Compulsory Subjects</h5>
                <table class=" display centered z-depth-1 mb-5 mt-5" id="sectionAssignedSubjectsTable">
                        <thead class="white-text colCode">
                            <th>Subject Title</th>
                            <th>Subject Teacher</th>
                            <th>Remove</th>
                        </thead>
                        <tbody class='center'>
                        </tbody>
                </table>
            </div>

            <div class="col offset-l1 l3 m3 s12">
                <h5>Unassigned Subjects</h5>
                <table class=" display centered z-depth-1 mb-5 mt-5" id="sectionUnassignedSubjectsTable">
                        <thead class="white-text colCode">
                            <th>Subject Title</th>
                            <th>Assign</th>
                        </thead>
                        <tbody class='center'>
                        </tbody>
                </table>
            </div>
        </div>
    </section>


    <div id="sectionAssignModal" class="modal">

        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>

        <div class="modal-content">
            <div class="row center">
                    <h4><span class="subjectName center"></span></h4>
                    <h6 id="showAssignModalError" class="hide red-text"><span class="center"> Please Select a Teacher and Type</span></h6>
                    <form id="sectionAssignForm">
                        <select id="teacherSelect" class="browser-default">
                            <option value="">--CHOOSE TEACHER--</option>
                            @foreach($teachers as $teacher)
                                <option value="{{$teacher->id}}">{{$teacher->lastName.' '.$teacher->firstName.' '.$teacher->otherNames}}</option>
                            @endforeach
                        </select>
                    </form>
            </div>
            <hr>
        </div>        
        <div class="modal-footer">
            <button class="btn" id="submitSectionAssignedSubject">SUBMIT</button>
        </div>
    </div>

    @include('includes.layout.confirmModal')


    <!-- </div> -->
<script src="{{asset('assets/js/subjectsManager.js')}}"></script>
@endsection

@section('dialog')
    @include('includes.guardians.showGuardian')
@endsection 

