@extends('layout')

@section('title', 'Schools List')

@section('content')

    <div class="datatableContainer">

        <div>
            <h4>Schools List</h4>
        </div>

        <!-- Add Schools Button -->
        <div class="col s12 row mt-5">
            <a href="/schools/create" class="col s12 m3 l2 btn waves-effect waves-light red lighten-1 modal-trigger" data-target="addSchoolsModal">Add Schools</a>
        </div>

        <table class="display responsive-table white z-depth-1" id="schoolData" style="border-radius: 2px;" width="100%">
            <thead class="center-align">
                <tr>
                    <th>id</th>
                    <th>School Name</th>
                    <th>School Head</th>
                    <th>School Address</th>
                    <th>LGA</th>
                    <th>School Motto</th>
                    <th>School Prefix</th>
                    <th>Action</th>
                </tr>

            </thead>

            <tbody>

                @foreach ($schools as $school)
                    <tr>
                        <td>{{ $school->id }}</td>
                        <td>{{ $school->name }}</td>
                        <td>{{ $school->head }}</td>
                        <td>{{ $school->address }}</td>
                        <td>{{ $school->lga_id }}</td>
                        <td>{{ $school->motto }}</td>
                        <td>{{ $school->prefix }}</td>
                        <td class="center-align">
                             {{-- <a href="javacript:void(0)" data-target="showSchoolsModal" class="btn btn-floating waves-effect waves-light red lighten-1 col s6 modal-trigger viewSchools" id="{{ 'viewSchools'. $school->id }}" data-id="{{ $school->id }}"> --}}
                             <a href="schools/{{ $school->id }}" data-target="showSchoolsModal" class="btn btn-floating waves-effect waves-light red lighten-1 col s6 modal-trigger viewSchools" id="{{ 'viewSchools'. $school->id }}">
                                <i class="material-icons">pageview</i>
                            </a>
                            <a href="/schools/{{ $school->id }}/edit" data-id="{{ $school->id }}" class="btn btn-floating waves-effect waves-light red lighten-1 col s6 editschool">
                                <i class="material-icons">edit</i>
                            </a>
                            <a href="javacript:void(0)" class="btn btn-floating waves-effect waves-light red lighten-1 col s6 modal-trigger deleteSchool" data-id="{{ $school->id }}" id="{{ 'deleteSchool'. $school->id }}" data-target="showSchoolModal">
                                <i class="material-icons">delete</i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection

{{-- @section('dialog')
    @include('includes.schools.showSchool')
@endsection --}}

