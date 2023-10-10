{{-- @extends('layout') --}}
@extends('schools.layout.schoollayout')

@section('title', 'Capture Photo and Signature')

@section('content')

    
        <div>
            <h5 class="center">Capture Staff Photo and Signature</h5>
        </div>
        
        
        <div class="row borderRound z-depth-1">
            <div class="col s12 m8 offset-2 input-field">
                <select id="capturedStaff" class="browser-default">
                    <option value="">SELECT STAFF</option>
                    @foreach($staff as $teacher)
                    <option value="{{$teacher->id}}">{{$teacher->lastName.' '.$teacher->firstName.' '.$teacher->otherNames}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row hide borderRound" id="staffName">
            <input type="hidden" id="staff-id" >
            <div class="row m12 center">
                <h6></h6>
            </div>
        </div>
        
        <div class="progress hide">
            <div class="indeterminate {{$school->themeColor ?: 'blue' }}"></div>
        </div>

    <div id="signaturetab" class="">
        <div  class="row greyBack" id="">
            <div  id="signature-pad" class="signature-pad borderRound">
                <div class="signature-pad--body">
                    <canvas style="width:100%;  padding:10px;"></canvas>
                </div>                                                
            </div>
            <br />      
        </div>
        <div class="row greyBack center">
            <input id="btnClear" type="button" data-action="clear" class="btn btn-warning" value="Clear Signature" />
            <button type="button" class="btn" id="btnSave">Save</button>
            <br>
            <button class="btn" id="showPassy" style="margin-top:1rem;">Capture Passport</button>
        </div>
    </div>                 

        <div id="passporttab" class="hide">          
            <div class="row">
                <div class="file-field input-field center col s12 m12">
                        <div id='cameraIcon'>
                            <i class="large material-icons">camera_alt</i>
                            <br>
                            <h6>Browse Photo</h6>
                        </div>
                        <img src='' id="src_img" class="hide">
                        <img src='' id="preview_compress_img" style="height:200px;width:150px" class="hide">
                    <input type="file" accept="image/*" id="passportFile" class="validate" >
                    <div class="file-path-wrapper hide">
                        <input type="text" class="file-path validate">
                    </div>
                </div>
            </div>
            <div class="row center greyBack">
                <button type="button" class="btn" id="passportSubmit">Save Photo</button>
            </div>
        </div>



        

        <div id="formAssignModal" class="modal">
            <button class="modal-close waves-effect waves-light btn-flat right" id="close">
                <i class="material-icons">close</i>
            </button>
            <h5 id='classInfo' class="center"></h5>

            <div class="modal-content">
                <div class="row center">
                        <h6 id="showAssignModalError" class="hide red-text"><span class="center"> Please Select a Teacher</span></h6>
                        <form id="formAssignForm">
                            <select id="formTeacherSelect" class="browser-default">
                                
                            </select>
                        </form>
                </div>
                <hr>
            </div>
            <div class="progress hide">
                <div class="indeterminate {{$school->themeColor}}"></div>
            </div>
            <div class="modal-footer">
                <button class="btn" id="submitAssignedFormTeacher">SUBMIT</button>
            </div>
        </div>

<input type="hidden" id="schoolId" value="{{ $school->id}}">
<input type="hidden" id="sessionId" value="{{ $school->academic_session_id}}">
<input type="hidden" id="termId" value="{{ $school->current_term_id}}">
<script src="{{ asset('assets/js/signature_pad.js')}}"></script>
<script type="module" src="{{ asset('assets/js/signature-app.js')}}"></script>
<script type="module" src="{{ asset('assets/js/captureManager.js')}}"></script>
@endsection

