@extends('schools.layout.schoollayout')

@section('title', 'Create Section')

    @section('content')
        
                <h5 class="center">Create School Section</h5>

            <div class="row z-depth-1 ">


                <form action="" id="schoolSectionForm">
                {{-- @csrf --}}
                <input type="hidden" name="schoolId" id="schoolId" value="{{ $school->id }}" />
                <input type="hidden" name="status" id="status" value="ACTIVE" />

                <div class="row">
                    <div class="input-field col s12 m6">
                        <input type="text" name="sectionName" id="sectionName" class="validate">
                        <label>Section Name</label>
                    </div>

                    <div class="input-field col s12 m6">
                        <input type="text" name="shortName"  id="shortName" class="validate">
                        <label>Section Short Name <small>(eg. PRI, JSS, SS)</small></label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12 m6">
                        <input type="text" name="sectionHead"  id="sectionHead" class="validate">
                        <label for="otherName">Name of Section Head</label>
                    </div>
                    <div class="file-field input-field col s12 l6">
                    <div class="btn {{ $school->themeColor }} lighten-1">
                        <span>Upload Signature</span>
                        <input type="file"  accept="image/*" name="sectionHeadSign" id="sectionHeadSign" >
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate"  type="text" placeholder="Signature of Section Head">
                    </div>
                    </div>
                    <!-- <div class="file-field input-field col s12 m6">
                        <input type="file" name="secsectionHeadSign" class="validate">
                        <label>Signature of Section Head</label>
                    </div> -->
                </div>

                {{--<div class="input-field col s12 12">
                    <select name="sectionTypeId" id="sectionTypeId" class="validate ">
                            <option value="">Choose Section Category</option>
                        @foreach($sectionType as $type)
                            <option value="{{$type->id}}">{{$type->typeName}}</option>
                        @endforeach
                    </select>
                    <label>Section Category</label>
                </div>--}}

                <div id="checkfile" class="modal">
                    <div class="modal-content">
                        <h4>Do you want to create this section without signature?</h4>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default modal-close" id="yesbtn" data-choice="true">Yes</button>
                        <button class="btn btn-default modal-close" id="nobtn" data-choice="false">No</button>
                    </div>
                </div>
                                 
                    
                    <!--File Uploader goes here-->
                    <div class="row center input-field">
                        <button class="btn waves-effect waves-light {{ $school->themeColor }} lighten-1" type="submit" id="submitSectionCreate">Submit</button>
                        <!-- <input type="submit" class="btn btn-default" id="addRecordSubmit" name="actionStaff" value="SUBMIT" /> -->
                    </div>
                </form>
            </div>
        
    <script type="module" src="{{ asset('assets/js/classroomManager.js') }}" ></script>

    @endsection