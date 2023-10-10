{{-- @extends('layout') --}}
@extends('schools.layout.schoollayout')

@section('title', 'Grading Format')

@section('content')

    <div class="container">

        <div class="center">
            <h4>School Grading Format</h4>
        </div>

        <!-- Add Classs Button -->
        <!-- <div class="col s12 row mt-5">
            <a href="/class/create" class="col s12 m3 l2 btn waves-effect waves-light red lighten-1 modal-trigger" data-target="addClassModal">Add Class</a>
        </div> -->

        <table class="display browser-default white" id="gradeIndexTable" style="border-radius: 2px;margin-bottom:10px;" width="100%">
            <thead class="center-align" style="border:1px solid #333;">
                <tr>
                    <th class="center">Section Name</th>
                    <th class="center">Short Name</th>                    
                    <th class="center">Grade Format</th>                    
                    <th class="center">Action</th>
                </tr>

            </thead>

            <tbody>

                @foreach ($schoolSections as $section)
                    <tr style="border:1px solid #333;">
                        <td class="center">{{ $section->sectionName }}</td>
                        <td class="center">{{ $section->shortName }}</td>
                        <td class="center">
                            <table style="border:1px solid #333;">
                            <tr><th class="center">Description</th>
                                <th class="center">Grade</th>
                                <th class="center">Min. Score</th>
                                <th class="center">Max. Score</th></tr>
                            @foreach($gradeFormat as $format)
                                @if($format->sectionId == $section->id)
                                    <tr style="border:1px solid #333;"><td>{{ $format->description }}</td><td>{{ $format->grade }}</td>
                                    <td>{{ $format->minScore }}</td><td>{{ $format->maxScore }}</td></tr>
                                @endif
                            @endforeach
                            </table>
                        </td>
                        <td class="center-align">
                            <a href="/{{ $school->id }}/{{ $section->id }}/grades/edit" class="btn btn-floating waves-effect waves-light red lighten-1 col s6">
                                <i class="material-icons">edit</i>
                            </a>
                            <a href="javacript:void(0)" class="btn btn-floating waves-effect waves-light red lighten-1 col s6 deleteSection" data-id="{{ $section->id }}" id="{{ 'deleteClass'. $section->id }}" data-target="showClassModal">
                                <i class="material-icons">delete</i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection

