{{-- @extends('layout') --}}
@extends('schools.layout.schoollayout')

@section('title', 'Class List')

@section('content')

    <!-- <div class="container"> -->

    <div class="progress hide">
        <div class="indeterminate {{$school->themeColor ?: 'blue' }}"></div>
    </div>

    <div class="row center greyBack">
        <h5 class="col l12">Assign Subjects to Classes</h5>
    </div>

        <table class="display white z-depth-1" id="" style="border-radius: 2px;" width="100%">
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
                            <a href="/subjects/class/{{ $school->id }}/{{ $classroom->id }}/{{$school->academic_session_id}}/{{$school->current_term_id}}?section_id={{$classroom->section_id}}" class="btn btn-floating waves-effect waves-light {{$school->themeColor ?: 'blue'}} lighten-1 col s6">
                                <i class="material-icons">edit</i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>


    

    @include('includes.layout.confirmModal')


    <!-- </div> -->
<script src="{{asset('assets/js/subjectsManager.js')}}"></script>
@endsection

@section('dialog')
    @include('includes.guardians.showGuardian')
@endsection 

