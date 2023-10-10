{{-- @extends('layout') --}}
@extends('schools.layout.schoollayout')

@section('title', 'Edit '.$guardian->firstname.' '.$guardian->surname.'\'s Details')

@section('content')

    <div class="row form-container">

        <!-- Header -->

        <span class="col s12 flow-text" style="padding: 20px; margin-bottom: 10px;">
            Edit {{ $guardian->title.' '. $guardian->firstName.' '.$guardian->lastName.' '.$guardian->otherName }}'s  Details
        </span>


        <div class="col s12">

            <!-- Edit Customer Record Form -->
            <form action="" method="POST" id="editGuardianForm">

                @include('includes.guardians.form')

                <div class="col s12 right-align" style="padding-bottom: 20px; margin: 0;">

                    <button class="btn waves-effect waves-light red lighten-1 resp-btn" type="submit" id="editRecordSubmit" data-act="guardian" data-id="{{ $guardian->id }}">
                        Save
                    </button>

                    <a href="/schools/school/guardians/{{ $school->id}}" class="btn waves-effect waves-light red lighten-1 resp-btn">
                        Return to Guardians View
                    </a>
                </div>

                {{-- @csrf --}}
            </form>

        </div>

    </div>

    @include('includes.guardians.showGuardian')

@endsection
