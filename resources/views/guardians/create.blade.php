@extends('layout')

@section('title', 'Add New Guardian')

@section('content')

    <div class="row form-container">

        <!-- Header -->

        <span class="col s12 flow-text">
            Add New Guardian
        </span>


        <div class="col s12">

            <!-- Add Guardian Record Form -->
            <form action="" method="POST" id="addForm">

                @include('includes.guardians.form')

                <div class="col s12 right-align" style="padding-bottom: 20px; margin: 0;">
                    <button class="btn waves-effect waves-light red lighten-1 resp-btn" data-id="guardian" type="submit" id="addRecordSubmit">
                        Submit
                    </button>
                    <a href="/guardians" class="btn waves-effect waves-light red lighten-1 resp-btn">
                        View Guardians
                    </a>
                </div>

                {{-- @csrf --}}
            </form>

        </div>

    </div>

@endsection

@section('dialog')
    @include('includes.guardians.showGuardian')
@endsection

