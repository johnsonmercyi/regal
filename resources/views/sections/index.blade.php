{{-- @extends('layout') --}}
@extends('schools.layout.schoollayout')

@section('title', 'Sections List')

@section('content')

    <div class="container">

        <div>
            <h4 class="center">School Sections List</h4>
        </div>

        <!-- Add Classs Button -->
        <!-- <div class="col s12 row mt-5">
            <a href="/class/create" class="col s12 m3 l2 btn waves-effect waves-light red lighten-1 modal-trigger" data-target="addClassModal">Add Class</a>
        </div> -->

        <table class="display responsive-table white" id="classData" style="border-radius: 2px;" width="100%">
            <thead class="center-align">
                <tr>
                    <th class="center">Section Name</th>
                    <th class="center">Short Name</th>
                    <th class="center">Section Head</th>
                    <th class="center">Section Head Signature</th>
                    <th class="center">Action</th>
                </tr>

            </thead>

            <tbody>

                @foreach ($schoolSections as $section)
                    <tr>
                        <td class="center">{{ $section->sectionName }}</td>
                        <td class="center">{{ $section->shortName }}</td>
                        <td class="center">{{ $section->sectionHead }}</td>
                        <td class="center"><img style="border:2px solid {{$school->themeColor}};" width="80px" height="50px" src="/storage/images/{{$school->prefix}}/photo/school/{{ $section->sectionHeadSign }}" /></td>
                        <td class="center-align">
                             {{-- <a href="javacript:void(0)" data-target="showClassModal" class="btn btn-floating waves-effect waves-light red lighten-1 col s6 modal-trigger viewClass" id="{{ 'viewClass'. $section->id }}" data-id="{{ $section->id }}"> --}}
                            <a href="/{{ $school->id }}/{{ $section->id }}/section/edit" data-id="{{ $section->id }}" class="btn btn-floating waves-effect waves-light red lighten-1 col s6 editSection">
                                <i class="material-icons">edit</i>
                            </a>
                            {{--<a href="javacript:void(0)" class="btn btn-floating waves-effect waves-light red lighten-1 col s6 deleteSection" data-id="{{ $section->id }}" id="{{ 'deleteClass'. $section->id }}" data-target="showClassModal">
                                <i class="material-icons">delete</i>
                            </a>--}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection

@section('dialog')
    @include('includes.guardians.showGuardian')
@endsection 

