@extends('schools.layout.schoollayout')

@section('title', 'Class List')

@section('content')

<div class="section">

    <div class="row greyBack">
        <div class="col m12">
            <button class="btn blue right" id="printProfile">
                <i class="material-icons">print</i>
            </button>        
        </div>
    </div>
    <div class="row z-depth-1 borderRound" >
        <form method="POST" action="" id="enterScores">
            <div class="col s12 m3 input-field">
                <select name="classId" id="selectedClass" class="browser-default">
                <option value=""> Choose Class </option>
                @foreach($allClass as $classroom)
                dd($allClass)
                    <option value="{{ $classroom->id }}">{{ $classroom->level.$classroom->suffix}}</option>
                @endforeach
                </select>
                <!-- <label for="classroom" >Choose Class</label> -->
            </div>

            <div class="col s12 m2 input-field">
                <select name="sessionId" id="selectedSession" class="browser-default">
                <option value=""> Choose Session </option>
                @foreach($acadSession as $acadSess)
                    <option value="{{ $acadSess->id }}">{{ $acadSess->sessionName}}</option>
                @endforeach
                </select>
                <!-- <label for="session" >Choose Session</label> -->
            </div>

            <div class="col s12 m2 input-field">
                <select name="termId" id="selectedTerm" class="browser-default">
                <option value="">Choose Term</option>
                <option value="1"> First Term </option>
                <option value="2"> Second Term </option>
                <option value="3"> Third Term </option>
                </select>
                <!-- <label for="term" >Choose Term</label> -->
            </div>

            <div class="col s12 m3 input-field">
                <select name="subjectId" id="selectedSubject" class="browser-default">
                <option value="">Choose  Subject </option>
                @if(count($subjects) > 0)
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->title}}</option>
                    @endforeach
                @endif

               
                </select>
                <!-- <label for="subject" >Choose Subject</label> -->
            </div>

            <div class="col s12 m2 center input-field">
                <button type="submit" id="loadSubjectStudents" class="btn btn-default {{ $school->themeColor }}">Load</button>
            </div>
            
            
        </form>
    </div>
    <h5 class="center">Enter Subject Scores</h5>
    <h5 class="center" id="subjectClassInfo"></h5>

</div>

    <div class="progress hide">
        <div class="indeterminate {{$school->themeColor}}"></div>
    </div>

<!-- <div class="section display responsive-table z-depth-1 hide" id="scoreTable"> -->
    <div class="section display z-depth-1 hide" id="tableError">
    <h5 class='center red-text'>Unable to Fetch Selected Class.</h5>
    </div>
    <div class="section display z-depth-1 hide" id="subjectError">
    <h5 class='center red-text'>This Subject is not Assigned to the Class.</h5>
    </div>
    
    <div id="profileDiv">
    <table class=" display responsive-table z-depth-1 hide mb-5" id="scoreTable">
        <form method="" action="" id="">
            <thead class="white-text center-align {{ $school->themeColor }}" id="scoreTableHead">
            </thead>
            <tbody class='center' id="scoreTableBody">
            </tbody>
            <tfoot class="center">
                <tr>
                    <td><button type="submit" class="btn btn-default {{$school->themeColor}}"  id="submitScores">Submit</button></td>
                    <td colspan='4'>
                        <div class="progress hide">
                            <div class="indeterminate {{$school->themeColor}}"></div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </form>
    </table>
    </div>

    <div id="successModal" class="modal">

        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>

        <div class="modal-content">

            <div class="row">
                <!-- Header -->
                <div class="center mb-5">
                   <h4 class="center">Scores Submitted Successfully</h4>
                </div>
            </div>
            <div class="row">
                <div class="center mb-5">
                   <h5 class="center">Use the<span class="{{$school->themeColor}}-text "> Update Buttons </span>to make Future Changes</h5>
                </div>
            </div>
        </div>
    </div>
    
    <div id="emptyStudentsModal" class="modal modal-fixed-footer">

        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>

        <div class="modal-content">

            <div class="row">
                <!-- Header -->
                <h4 class="center">No score was entered for these students.</h4>
                <div class="center mb-5" id="emptyStudentsList">
                </div>

                <h4 class="center">Do you still want to submit?</h4>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn blue" id="emptyCancelBtn">
                CANCEL
            </button>
            <button class="btn green" id="emptySubmitBtn">
                SUBMIT
            </button>
        </div>
    </div>
<!-- </div> -->
<input type="hidden" id="schoolId" value="{{ $school->id}}">
<input type="hidden" id="schoolColor" value="{{ $school->themeColor}}">

@endsection