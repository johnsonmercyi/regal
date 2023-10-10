@extends('schools.layout.schoollayout')

    @section('content')
        
        <div class="container">
            <div class="row z-depth-1 ">

                <h3 class="center">Edit School Section</h3>

                <form method="POST" action="" id="addForm">
                {{-- @csrf --}}
                <input type="hidden" name="schoolId" id="schoolId" value="{{ $school->id }}" />
                <input type="hidden" name="status" id="status" value="ACTIVE" />

                <div class="row">
                    <div class="input-field col s12 m6">
                        <input type="text" name="sectionName" id="sectionName" class="validate" value="{{ $schoolSection->sectionName }}">
                        <label>Section Name</label>
                    </div>

                    <div class="input-field col s12 m6">
                        <input type="text" name="shortName"  id="shortName" class="validate" value="{{ $schoolSection->shortName }}">
                        <label>Section Short Name <small>(eg. PRI, JSS, SSS)</small></label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12 m6">
                        <input type="text" name="sectionHead"  id="sectionHead" class="validate" value="{{ $schoolSection->sectionHead }}">
                        <label for="otherName">Name of Section Head</label>
                    </div>
                    <div class="col s12 l6">
                        <span class="label">Section Head Signature</span>
                        <img class="circle ml-5" style="border:2px solid {{$school->themeColor}};height:60px;width:80px" 
                        src="/storage/images/{{$school->prefix}}/schools/photo/{{ $schoolSection->sectionHeadSign }}" />                    
                    </div>
                    <!-- <div class="file-field input-field col s12 m6">
                        <input type="file" name="secsectionHeadSign" class="validate">
                        <label>Signature of Section Head</label>
                    </div> -->
                </div>
                
                <div class="row">
                <div class="file-field input-field col s12 l12">
                    <div class="btn {{ $school->themeColor }} lighter-1">
                        <span>Choose File</span>
                        <input type="file" name="sectionHeadSign"  >
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" id="sectionHeadSign" type="text" placeholder="Change Signature of Section Head">
                    </div>
                </div>
                </div>
                                 
                    
                    <!--File Uploader goes here-->
                    <div class="row center input-field">
                        <button class="btn waves-effect waves-light {{ $school->schoolThemeColor }} lighter-1" type="submit" id="submitFileForm" data-id="section" name="actionSection" disabled>Submit</button>
                        <!-- <input type="submit" class="btn btn-default" id="addRecordSubmit" name="actionStaff" value="SUBMIT" /> -->
                    </div>
                </form>
            </div>
        </div>
        
    @endsection
    
    <!--This extends to the layout for other Js Usages-->
    
@section('dialog')
@include('includes.guardians.showGuardian')
@endsection