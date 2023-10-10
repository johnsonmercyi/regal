@extends('schools.layout.schoollayout')

@section('title', 'Grading Format')

@section('content')
  

    <div class="progress hide">
        <div class="indeterminate {{$school->themeColor}}"></div>
    </div>

    <!-- <input type="hidden" id="sectionId" name="sectionId" value="2" /> -->
<!-- <div class="section display responsive-table z-depth-1 hide" id="scoreTable"> -->

        <section class="section center" id="existFormatSection">
            <h5>School Grading Formats <button class="btn white-text" id="createNewFormat">Create New Format</button></h5>
            @if(count($allFormats) != 0)
                @foreach($allFormats as $format)
                <div class="row center form-section borderRound">
                    <div class="col offset-m2 m8 s12">
                        <h6>Format {{ $format[0]->format_id }}</h6>
                        <table >
                            <tr>
                                <th>S/No.</th>
                                <th>Description</th>
                                <th>Grade</th>
                                <th>Min. Score</th>
                                <th>Max. Score</th>
                            </tr>
                            <?php $serNum = 1; ?>
                            @foreach($format as $formatItem)
                                <tr>
                                    <td>{{$serNum++}}</td>
                                    <td>{{$formatItem->description}}</td>
                                    <td>{{$formatItem->grade}}</td>
                                    <td>{{$formatItem->minScore}}</td>
                                    <td>{{$formatItem->maxScore}}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                @endforeach
            @else
                <h5 class="red-text">No Formats Created</h5>
            @endif
    </section>

    <br>

    <section class="section z-depth-1 form-section borderRound" id="gradeAssignSection">
        <h5 class="center">Assign Grading Format to School Section</h5>
        <div class="progress hide">
            <div class="indeterminate {{$school->themeColor}}"></div>
        </div>
        <table id="formatAssignTable">
            <tr>
                <th>Name</th>
                <th>Short Name</th>
                <th>Assessment Format</th>
                <th>Change Format</th>
            </tr>
        @foreach ($schoolSections as $section)
            <tr id="row{{ $section->id }}">
                <td>{{ $section->sectionName }}</td>
                <td>{{ $section->shortName }}</td>
                <td class="gradeForm"> {{ $section->grading_format_id ? 'Format '.$section->grading_format_id : "No Format" }}</td>
                <td>
                    <div class="left">
                        <select name="" id="assess{{ $section->id }}" class="browser-default">
                            <option value="">Choose</option>
                            @if(count($existFormat) != 0)
                                @foreach($existFormat as $formatId)
                                    <option value="{{$formatId->format_id}}">Format {{$formatId->format_id}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <button class="btn btn-floating assignGrade waves-effect waves-light colCode lighten-1 right"  data-id="{{ $section->id }}">
                        <i class="material-icons">send</i>
                    </button>
                </td>
            </tr>
        @endforeach
    </table>
    </section>

    <br>

<div class="section row hide" id="createFormatSection">
    <div class="offset-l2 col m12 l8">
    <h4 class="center">Create Grading Format</h4>
    <table class="display table z-depth-1 centered" id="gradesTable">
        <thead class="white-text center-align colCode" id="gradingFormatTableHead">
            <th >S/No.</th>
            <th >Description</th>
            <th >Grade</th>
            <th >Min. Score</th>
            <th >Max. Score</th>
        </thead>
        <tbody class='center' id="gradeTableBody">
            <tr>
                <td>1</td>
                <td><input type='text' class="center description" value='Excellent' style='background-color:#ddd;border-radius:5px' /></td>
                <td><input type='text' class="center numInput grade" value='A'/></td>
                <td><input type='number' class="center numInput minScore" value='70'/></td>
                <td><input type='number' class="center numInput maxScore" value='100'/></td>
            </tr>
        </tbody>
        <tfoot class="center">
            <tr>
                <td></td>
                <td><button type="submit" class="btn btn-default colCode darken-2"  id="submitGradeFormat">Submit</button></td>
                <td><button type="submit" class="btn btn-default colCode darken-2 " id="addGradeFormatRow">Add Grade</button></td>
            </tr>
        </tfoot>
    </table>
    </div>
</div>

    <div id="successModal" class="modal">

        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>

        <div class="modal-content">

            <div class="row">
                <!-- Header -->
                <div class="valign-wrapper mb-5">
                   <h2 class="center">Grade Format Submitted Successfully</h2>
                </div>
            </div>
        </div>
    </div>

    <div id="confirmModal" class="modal">

        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>

        <div class="modal-content">

            <div class="row">
                <!-- Header -->
                <div class="valign-wrapper mb-5">
                   <h2 class="center">Are you sure you want to save this Format?</h2>
                </div>
            </div>
            <div class="row center">
                <button type="submit" class="btn green"  id="yesConfirm">YES</button>
                <button type="submit" class="btn red"  id="noConfirm">NO</button>
            </div>
        </div>
    </div>

<!-- </div> -->
<script src="{{ asset('assets/js/gradeFormatManager.js') }}"></script>
@endsection