@extends('schools.layout.schoollayout')

@section('title', 'View Results')

@section('content')
<div class="section">
<div class="row z-depth-1 borderRound" >
    <form action="" id="viewClassResult">
            <div class="col s12 m3 input-field">
                <select id="classId" class="browser-default">
                <option value=""> Choose Class </option>
                @foreach($allClass as $classroom)
                    @php 
                        if (strpos($classroom->level, "NURSERY") !== false || strpos($classroom->level, "PLAY GROUP") !== false) {
                            continue;
                        } else {
                            echo "<option value=\"$classroom->id\">$classroom->level$classroom->suffix</option>";
                        }
                    @endphp
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
                    <option value="4"> Annual Result</option>
                </select>
                <!-- <label for="term" >Choose Term</label> -->
            </div>

            <div class="col s12 m3 center input-field">
                <button type="submit" id="loadClassResultsList" class="btn btn-default colCode">Load</button>
                <button type="submit" id="viewClassResults" class="btn btn-default colCode">View All</button>
            </div>          
        </form>
    </div>
</div>

    <div class="progress hide colCode">
        <div class="indeterminate colCode"></div>
    </div>

<!-- <div class="section display responsive-table z-depth-1 hide" id="scoreTable"> -->
    <div class="section display z-depth-1 hide" id="tableError">
    <h3 class='center'>Unable to fetch selected class.</h3>
    </div>
    
    <table class=" display centered z-depth-1 hide mb-5" id="studentResultListTable">
            <thead class="white-text colCode" id="studentResultTableHead">
                <th>S/No.</th>
                <th>Reg. No.</th>
                <th>Student Name</th>
                <th>View Result</th>
            </thead>
                
            <tbody class='center' id="studentResultTableBody"></tbody>
    </table>

    <div id="resultModal" class="modal modal-fixed-footer">

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
        </div>
        <div class="modal-footer">
            <button class="btn" id="">SUBMIT</button>
        </div>
    </div>

<!-- </div> -->
<input type="hidden" id="schoolId" value="{{ $school->id}}">
<input type="hidden" id="schoolColor" value="{{ $school->themeColor}}">
<script type="module" src="{{ asset('assets/js/resultsManager.js') }}"></script>
@endsection