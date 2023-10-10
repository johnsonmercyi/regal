@extends('schools.layout.schoollayout')

@section('title', 'Traits Assessment')

@section('content')

<div class="section">
<div class="row z-depth-1 " >
    <form method="" action="" id="makeTraitAssess">
            <div class="col s12 m3 input-field">
                <select name="classId" class="browser-default">
                <option value=""> Choose Class </option>
                @foreach($allClass as $classroom)
                    <option value="{{ $classroom->id }}">{{ $classroom->level.$classroom->suffix}}</option>
                @endforeach
                </select>
                <!-- <label for="classroom" >Choose Class</label> -->
            </div>

            <div class="col s12 m3 input-field">
                <select name="sessionId" class="browser-default">
                <option value=""> Choose Session </option>
                @foreach($acadSession as $acadSess)
                    <option value="{{ $acadSess->id }}">{{ $acadSess->sessionName}}</option>
                @endforeach
                </select>
                <!-- <label for="session" >Choose Session</label> -->
            </div>

            <div class="col s12 m3 input-field">
                <select name="termId" class="browser-default">
                <option value="">Choose Term </option>
                <option value="1"> First Term </option>
                <option value="2"> Second Term </option>
                <option value="3"> Third Term </option>
                </select>
                <!-- <label for="term" >Choose Term</label> -->
            </div>

            <div class="col s12 m3 center input-field">
                <button type="submit" id="loadClassStudentsTraits" class="btn btn-default colCode">Load</button>
            </div>
            
            
        </form>
    </div>
    <h5 class='center'>Make Trait Assessment</h5>
</div>

    <div class="progress hide">
        <div class="indeterminate {{$school->themeColor}}"></div>
    </div>

<!-- <div class="section display responsive-table z-depth-1 hide" id="scoreTable"> -->
    <div class="section display z-depth-1 hide" id="tableError">
    <h3 class='center'>Unable to fetch selected class.</h3>
    </div>
    <table class=" display responsive-table z-depth-1 hide mb-5" id="traitTable">
        <form method="" action="" id="">
            <thead class="white-text center-align colCode" id="traitTableHead">
                <th>S/No.</th>
                <th>Name</th>
                <th>Reg. No.</th>
                <th>Evaluate Traits</th>
            </thead>
            <tbody class='center' id="traitTableBody">
            </tbody>
        </form>
    </table>

    <div id="successModal" class="modal">

        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>

        <div class="modal-content">

            <div class="row">
                <!-- Header -->
                <div class="valign-wrapper mb-5">
                   <h2 class="center">Scores Submitted Successfully</h2>
                </div>
            </div>
        </div>
    </div>

    <div id="traitModal" class="modal modal-fixed-footer">

        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>

        <div class="modal-content">
            <div class="row ">
                <div class="col">
                    <h5><span class="stdInfo center"></span></h5>
                </div>
            </div>
            <hr>
            <div class="row">
                <table class="table">
                    <thead>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <div class="progress hide">
                <div class="indeterminate {{$school->themeColor}}"></div>
            </div>
            <button class="btn" id="packTraitAssessment">SUBMIT</button>
        </div>
    </div>

<!-- </div> -->
<input type="hidden" id="schoolId" value="{{ $school->id}}">
<input type="hidden" id="schoolColor" value="{{ $school->themeColor}}">

@endsection