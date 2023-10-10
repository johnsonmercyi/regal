@extends('schools.layout.schoollayout')

@section('title', 'Class Assignments')

    @section('content')

    <div class="row z-depth-1 borderRound">
        <h4 class="center">Create Class Assignment</h4>    
    </div>
    <div class="row z-depth-1  borderRound" style="padding:20px 0 !important">

        <form id="assignmentForm">

        <div class="row">
            <div class="input-field col s12 m6">
                <input type="date" id="createdDate" name="dateCreated" value="{{date('Y-m-d')}}" class="validate">
                <!-- <span id="dateCreated"></span> -->
                <label>Date Created</label>
            </div>

            <div class="input-field col s12 m6">
                <input type="date" id="submitDate" name="dateSubmit" class="validate">
                <label for="otherName">Date of Submission</label>
            </div>
        </div>

        <div class="row">
            <div class="col m6 s12">
                <h6 class="center" style="border-bottom:solid  {{ $school->themeColor ?: 'blue' }}">SELECTED CLASS(ES)</h6>
                <ul class="collection" id="assignClassList">
                    <li class="collection-item">No Class Selected.</li>
                </ul>
                <div class="input-field col s12 m12">
                    <div class="btn" style="background-color:  {{ $school->themeColor ?: 'blue' }}" id="classPick">
                        <span>Choose Class</span>
                    </div>
                </div>
            </div>
            <div class="col m6 s12">
                <h6 class="center" style="border-bottom:solid  {{ $school->themeColor ?: 'blue' }}">SELECTED SUBJECT</h6>
                <select name="selSubject" class="browser-default" id="selSubject">
                    <option value="">SELECT SUBJECT</option>
                    @foreach($allSubj as $subj)
                        <option value="{{$subj->id}}">{{ $subj->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="file-field input-field col s12 m12">
                <div class="btn" style="background-color:  {{ $school->themeColor ?: 'blue' }}">
                    <span>Choose Assignment File</span>
                    <input type="file" name="assignmentFile" id="assignmentFile" >
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text" placeholder="Assignment File">
                </div>
            </div>
        </div>

        {{--<div class="input-field col s12 12">
            <select name="sectionTypeId" id="sectionTypeId" class="validate ">
                    <option value="">Choose Section Category</option>
                @foreach($sectionType as $type)
                    <option value="{{$type->id}}">{{$type->typeName}}</option>
                @endforeach
            </select>
            <label>Section Category</label>
        </div>--}}

        <div id="checkfile" class="modal">
            <div class="modal-content">
                <h4>Do you want to create this section without signature?</h4>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default modal-close" id="yesbtn" data-choice="true">Yes</button>
                <button class="btn btn-default modal-close" id="nobtn" data-choice="false">No</button>
            </div>
        </div>
                            
            
            <!--File Uploader goes here-->
            <div class="row center input-field">
                <div class="progress hide colCode">
                    <div class="indeterminate"></div>
                </div>
                <button class="btn waves-effect waves-light"  style="background-color:{{ $school->themeColor ?: 'blue' }}" type="submit" id="submitAssignment">Submit</button>
                <!-- <input type="submit" class="btn btn-default" id="addRecordSubmit" name="actionStaff" value="SUBMIT" /> -->
            </div>
        </form>
    </div>
        


    <div id="amentModal" class="modal modal-fixed-footer">
        <!-- <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button> -->

        <div class="modal-content ">
            <h4 class="green-text">Select Classes</h4><hr>
            <table id="assignClassesTable">
                <thead>
                    <th>Class</th>
                    <th>Select</th>
                </thead>
                <tbody>
                    @foreach($allClass as $klass)
                        <tr data-id="{{$klass->id}}">
                            <td>{{ $klass->level.' '.$klass->suffix }}</td>
                            <td>
                                <button class="btn btn-floating btn-small grey amentClassCheck" 
                                data-check="false" data-id="{{$klass->id}}" 
                                data-name="{{ $klass->level.' '.$klass->suffix }}">
                                    <i class="material-icons">check</i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- <ul class="collection">
                    <li class="collection-item" value="{{$klass->id}}"> -->
                        <!-- <span style="display:inline-block !important; vertical-align:middle" class=" right"> -->
                            
                            <!-- <button class="btn btn-floating btn-small">
                                <i class="material-icons">check</i>
                            </button> -->
                        <!-- </span> -->
                    <!-- </li>
            </ul> -->
            </div>
            <div class="modal-footer">
                <button class="btn green right" id="chooseDoneBtn">DONE</button>
            </div>
    </div>

    <script type="module" src="{{asset('assets/js/assignmentsManager.js')}}"></script>


    @endsection
    
    <!--This extends to the layout for other Js Usages-->
    
@section('dialog')
@endsection