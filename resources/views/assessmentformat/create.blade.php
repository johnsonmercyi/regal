@extends('schools.layout.schoollayout')

@section('title', 'Assessment Format')

@section('content')
  

    <div class="progress hide">
        <div class="indeterminate {{$school->themeColor}}"></div>
    </div>

    <!-- <input type="hidden" id="sectionId" name="sectionId" value="2" /> -->
    <!-- <div class="section display responsive-table z-depth-1 hide" id="scoreTable"> -->

    <section class="section center" id="formatTablesSection">
            <h5>School Assessment Formats <button class="btn white-text" id="createNewFormat">Create New Format</button></h5>
            @if(count($allFormats) != 0)
                @foreach($allFormats as $format)
                <div class="row center form-section borderRound">
                    <div class="col offset-m2 m8 s12">
                        <h6>Format {{ $format[0]->format_id }}</h6>
                        <table style="border:1px solid #333;">
                            <tr style="border:1px solid #333;">
                                <th>S/No.</th>
                                <th>Name</th>
                                <th>Percentage</th>
                            </tr>
                            <?php $serNum = 1; ?>
                            @foreach($format as $formatItem)
                                <tr style="border:1px solid #333;">
                                    <td>{{$serNum++}}</td>
                                    <td>{{$formatItem->name}}</td>
                                    <td>{{$formatItem->percentage}}</td>
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


    <section class="section z-depth-1 form-section borderRound" id="assessAssignSection">
        <h5 class="center">Assign Assessment Format to School Section</h5>
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
                <td class="assessForm"> {{ $section->assessment_format_id ? 'Format'.$section->assessment_format_id : "No Format" }}</td>
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
                    <button class="btn btn-floating assignAssess waves-effect waves-light colCode lighten-1 right"  data-id="{{ $section->id }}">
                        <i class="material-icons">send</i>
                    </button>
                </td>
            </tr>
        @endforeach
    </table>
    </section>

    <br>

<div class="section row hide" id="formatCreateSection">
    <div class="offset-l2 col m12 l8">
    <h4 class="center">Create Assessment Formats</h4>
    <table class="display table z-depth-1  mb-5" id="assessTable">
            <thead class="white-text center-align {{ $school->themeColor }}" id="assessmentFormatTableHead">
                <th >S/No.</th>
                <th >Name</th>
                <th >Percentage</th>
            </thead>
            <tbody class='center' id="assessTableBody">
                <tr id="EXAM">
                    <td>1</td>
                    <td><span class="name">Examination<span></td>
                    <td><input type='number' class="assessVal center percentage" value='0' style='width:50px;background-color:#ddd;border-radius:5px' /></td>
                </tr>
                <tr id="CA1">
                    <td>2</td>
                    <td><input class="name" style='background-color:#ddd;border-radius:5px' type="text" value="First Test" /></td>
                    <td><input type='number' class="assessVal center percentage" value='0' style='width:50px;background-color:#ddd;border-radius:5px' /></td>
                </tr>
            </tbody>
            <tfoot class="center">
                <tr>
                    <td></td>
                    <td>Total</td>
                    <td><span id="totalAssess">0</span></td>
                </tr>
                <tr>
                    <td></td>
                    <td><button type="submit" class="btn btn-default {{$school->themeColor}} darken-2"  id="submitFormat">Submit</button></td>
                    <td><button type="submit" class="btn btn-default {{$school->themeColor}} darken-2 " style="height:auto" id="addFormatRow">Add Assessment</button></td>
                </tr>
            </tfoot>
    </table>
            <div class="progress hide colCode">
                <div class="indeterminate colCode"></div>
            </div>
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
                   <h2 class="center">Assessment Format Submitted Successfully</h2>
                </div>
            </div>
        </div>
    </div>
<!-- </div> -->
<script type="module" src="{{ asset('assets/js/assessmentFormat.js') }}" ></script>

@endsection