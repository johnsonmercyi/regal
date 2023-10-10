@extends('schools.layout.schoollayout')

@section('title', 'Register Staff')

@section('content')
    
    <div class="">
        <h4 class="center">Register Staff</h4>

        <!-- <div class="row "> -->
        <form method="POST" action="" id="addForm" class='greyBack'>
            {{-- @csrf --}}
            <input type="hidden" name="stfstatus" value="1" />
            <input type="hidden" name="stfschool_id" value="{{ $school->id }}" />
        <div class="row z-depth-1 form-section borderRound">

            <div class="">
                <h5 class="center">Bio-Data</h5><hr />
                <!-- <div class="col s12 m8  offset-m4"> -->
                <div class="row" style="margin-top:2rem">
                    <div class="col s12 m4 center">
                        <video src="" id="video">Video not available</video>
                    </div>
                    <div class="col s12 m4 center">
                        <!-- <input type="submit" class="btn btn-default " 
                        style="background-color:#333;color:#fff" id="startbutton" 
                        value="Take a photo" /> -->
                        <i id="startbutton" class="medium material-icons">camera_alt</i>
                    </div>

                    <canvas id="canvas">            
                    </canvas>

                    <div class="col s12 m4 output center">
                        <img src="" name="imageFile" alt=""  id="photo">
                    </div>
                </div>
            </div>

            <br />
            <!-- <hr /> -->
            <input type="hidden" name="stfimgFile" id="imgFile" value="" />

            <div class="row">
                <div class="input-field col s12 m4">
                    <input type="text" name="stffirstName" class="validate">
                    <label for="firstName">First Name<sup>*</sup></label>
                </div>
                <div class="input-field col s12 m4">
                    <input type="text" name="stfotherNames" class="validate">
                    <label for="otherName">Middle Name<sup>*</sup></label>
                </div>
                <div class="input-field col s12 m4">
                    <input type="text" name="stflastName" class="validate">
                    <label for="lastName">Surname<sup>*</sup></label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12 m4">
                    <select name="stftitle" class="browser-default">
                        <option value="" >Title<sup>*</sup></option>
                        <option value="MR" >MR</option>
                        <option value="MRS">MRS</option>
                        <option value="MISS">MISS</option>
                        <option value="REV. FR.">REV. FR.</option>
                        <option value="REV. SR.">REV. SR.</option>
                        <option value="REV. BR.">REV. BR.</option>
                        <option value="REV.">REV.</option>
                        <!--Our Database Call comes in-->
                    </select>
                </div>
                <div class="col s12 m4 input-field">
                    <select name="stfmarital_status_id" class="browser-default">
                        <option value="" >Marital Status<sup>*</sup></option>
                        <option value="1">Married</option>
                        <option value="2">Single</option>
                        <option value="3">Divorced</option>
                        <!--Our Database Call comes in-->
                    </select>
                </div>
                <div class="col s12 m4">
                    <label for="DOB">Date of Birth<sup>*</sup></label>
                    <input type="date" name="stfdob" class="validate">
                </div>
            </div>

            <div class="row">            
                <div class="input-field col s12 m4">
                    <select id="gender" name="stfgender" class="browser-default" value="">
                    <option value="">Choose Gender<sup>*</sup></option>
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                    </select>
                    <!-- <label for="gender" class="">Gender</label> -->
                </div>                     
                
                <div class="input-field col s12 m4 ">
                    <select name="stfstate_of_origin_id" class="browser-default" id="selectState">
                        <option class="" value=""  selected>State Of Origin<sup>*</sup></option>
                        @foreach($states as $state)
                            <option class="stateId" value="{{ $state->id }}">
                                {{ strtolower($state->state) }}
                            </option>
                        @endforeach
                    </select>
                    <!-- <label class="form-label">State of Origin</label> -->
                </div>
                <div class="input-field col s12 m4" >
                    <select name="stflgaOfOriginId" class="browser-default" id="stateLgas" >
                        <option class="" value=""  selected>LGA Of Origin<sup>*</sup></option>
                        @foreach($lgas as $lga)
                            @if($lga->state_id == 1)
                                <option value='{{ $lga->id}}'> {{ strtolower($lga->lga) }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- </div> -->

            <div class="row">
                <div class="input-field col s12 m4">
                    <input type="text" name="stfhomeTown" class="validate">
                    <label for="hometown">Hometown<sup>*</sup></label>
                </div>
                <div class="input-field col s12 m8">
                    <input type="text" name="stfhomeAddress" class="validate">
                    <!-- <input type="hidden" name="stfregNo" value="holder"> -->
                    <label for="homeAddress">Residential Address<sup>*</sup></label>
                </div>
            </div>

            <div class="row">                               
                <div class="col s12 m4 input-field">
                    <select name="stfcategory_id" class="browser-default" id="staffCategory" >
                        <option class="" value=""  selected>Staff Category<sup>*</sup></option>
                        @foreach($stf_category as $cat)
                            <option value="{{ $cat->id }}" >{{ $cat->name }}</option>
                        @endforeach                      
                    </select>
                </div>
                <div class="col s12 m4 input-field">
                    <select name="stfposition" class="browser-default" id="staffPosition" >
                        <option value=""  selected>Staff Position<sup>*</sup></option>
                        @foreach($stf_position as $posit)
                            @if($posit->category_id == 1)
                                <option value="{{ $posit->id }}" >{{ $posit->position }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                
                <div class="input-field col s12 m4">
                    <input type="email" name="stfemail" class="validate">
                    <label for="email" data-error="wrong" data-success="right">Email<sup>*</sup></label>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m4 input-field">
                    <select name="stfreligion_id" class="browser-default">
                        <option value=""  selected>Choose Religion</option>
                        @foreach($religions as $religion)
                            <option value="{{$religion->id}}">
                                {{ $religion->religion }}
                            </option>
                        @endforeach
                    </select>
                    <!-- <label for="stfreligion_id">Religion<sup>*</sup></label> -->
                </div>
                <div class="col s12 m4 input-field">
                    <select name="stfdenomination_id" class="browser-default">
                        <option value=""  selected>Choose Denomination</option>
                        @foreach($denominations as $den)
                            <option value="{{$den->id}}">
                                {{ $den->name }}
                            </option>
                        @endforeach
                    </select>
                    <!-- <label for="stfdenomination_id">Religious Denomination<sup>*</sup></label> -->
                </div>
                <div class="input-field col s12 m4">
                    <input type="tel" name="stfphoneNo" class="validate">
                    <label for="phone">Phone Number<sup>*</sup></label>
                </div>
            </div>

            <div class="row">   
                <div class="col s12 m4 input-field">
                    <select name="stfsalary_grade_id" id="gradeLevelSelect" class="browser-default">
                        <option value=""  selected>Grade Level<sup>*</sup></option>
                        <option value="0" data-rank="None">None</option>
                        @foreach($grade_level as $level)
                            <option value="{{ $level->id }}" data-rank="{{ strtoupper($level->rank) }}">{{ strtoupper($level->level .' - '. $level->category) }}</option>
                        @endforeach
                    </select>
                </div>                 
                <div class="col s12 m4 input-field">
                    <input type="tel" name="stfrank_id" id="rankInput" placeholder="Rank" readonly>
                    <label for="rank">Rank</label>
                </div>
                <div class="col s12 m4">
                    <label for="dateOfAppoint">Date of Appointment<sup>*</sup></label>
                    <input type="date" name="stfappointmentDate" class="validate">
                </div> 

                <div class="file-field input-field col s12 m12" style="margin-top:0">
                    <div class="btn green">
                            <span>Upload Signature Image</span>
                    <input type="file" accept="image/*" name="stfsignature" class="validate" >
                    </div>
                    <div class="file-path-wrapper">
                        <input type="text" class="file-path validate" id="stfsignature">
                    </div>
                </div>
            </div>
        </div>        

        <div class="row z-depth-1 form-section borderRound">
            <h5 style="text-align:center">Qualifications</h5>
            <hr/>    
            
            <div class="row">
                <h6 class="leftBord">Primary/Basic Education <small>(FSLC Optional)</small></h6>
                <div class="input-field col s12 m7">
                    <input type="text" name="stffslcinstitution" class="validate">
                    <label for="surname">Institution</label>
                </div>
                <!-- <div class=" col s12 m3">
                    <label for="firstname">FSLC Certificate</label>
                    <input type="file" accept="image/*" name="stffslccert" class="greyInp validate" style="width:150px; margin-top:1rem;">
                </div> -->
                <div class="input-field col s12 m2">
                    <input type="number" name="stffslcyear" class="validate" min="1940" max="2020" placeholder="Year">
                    <label for="othername">Year</label>
                </div>
                <div class="input-field col s12 m3">
                    <select name="stffslcgrade" class="browser-default greyInp">
                        <option value=""  selected>FSLC Grade</option>
                        <option value="Distinction">Distinction</option>
                        <option value="Credit">Credit</option>
                        <option value="Pass">Pass</option>
                        <option value="Fail">Fail</option>
                    </select>
                </div>
                
                <div class="file-field input-field col s12 m12">
                    <div class="btn green">
                            <span>Upload FSLC Certificate</span>
                    <input type="file" accept="image/*" name="stffslccert" class="validate" >
                    </div>
                    <div class="file-path-wrapper">
                        <input type="text" class="file-path validate" id="stffslccert">
                    </div>
                </div>
            </div>

            <div class="row">
                <h6 class="leftBord">Secondary Education<sup>*</sup></h6>
                <div class="input-field col s12 m6">
                    <input type="text" name="stfssceinstitution" class="validate">
                    <label for="">Institution</label>
                </div>
                <div class="input-field col s12 m2">
                    <input type="number" name="stfssceyear" class="validate" min="1930" max="2020" placeholder="Year">
                    <label for="">Year</label>
                </div>
                <div class="col s12 m4">
                    <select name="stfsscebody" class='browser-default' style="margin-top: 1rem;">
                        <option value=""  selected>Certificate Exam Body</option>
                        <option value="WASSCE" >WASSCE</option>
                        <option value="GCE">GCE</option>
                        <option value="NECO">NECO</option>
                        <!--Our Database Call comes in-->
                    </select>
                </div>
                <div class="row">
                    <button type="button" class="hide btn white waves-effect waves-light" 
                        id="removeSecEd" 
                        style="font-weight:bold; color:red;" name="">
                            CERTIFICATE NOT AVAILABLE
                    </button>                
                </div>
            </div>
            
            <div class="row" id="ssceCerts">
                <div class="input-field col s12 m6">
                    <input type="number" id="numSittings" class="validate" min="1" max="2">
                    <label for="">Number of Sittings</label>
                </div>
                <div class="file-field input-field col s12 m6">
                    <div class="btn green">
                            <span>Upload Certificate</span>
                    <input type="file" accept="image/*" name="stfsscecert" class="validate" >
                    </div>
                    <div class="file-path-wrapper">
                        <input type="text" class="file-path validate" id="stfsscecert">
                    </div>
                </div>
            </div>

            <div class="row">
                <h6 class="leftBord">SSCE SUBJECTS<sup>*</sup></h6>
                <div class="col s12 m12">
                    <table id="staffssce">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <!-- <input type="text" name="stfsscesub0" class="greyInp"> -->
                                    <select name="stfsscesub0" id="ssceSubjectSelect" class="browser-default greyInp">
                                        <option value=""  selected>SSCE Subject</option>
                                        @foreach($subjects as $sub)
                                            <option value="{{$sub->id}}">{{$sub->title}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <!-- <input type="text" name="stfsscegrade0" class="greyInp"> -->
                                    <select name="stfsscegrade0" id="ssceGradeSelect" class="browser-default greyInp">
                                        <option value=""  selected>SSCE Grade</option>
                                        <option value="A1">A1</option>
                                        <option value="B2">B2</option>
                                        <option value="B3">B3</option>
                                        <option value="C4">C4</option>
                                        <option value="C5">C5</option>
                                        <option value="C6">C6</option>
                                        <option value="D7">D7</option>
                                        <option value="E8">E8</option>
                                        <option value="F9">F9</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn green waves-effect waves-light" 
                    id="addSSCE" data-id="staff" 
                    style="font-weight:bold;" name="">ADD</button>
                </div>
            </div>
            
            <div class="row" id='higherEdDiv'>
                <h6 class="leftBord">HIGHER EDUCATION<sup>*</sup></h6>
                <!-- <div class="col s12 m12"> -->
                    <div id="higherIntn">
                            <!-- <div class style="border-bottom: none;"> -->
                            <div class="row">
                                <div class="col m6 s12">
                                    <input type="text" name="stfhigherinstitute0" placeholder="Institution Attended" class="greyInp" style="margin-bottom:0px">
                                </div>
                                <div class="col m3 s12">
                                    <select name="stfhigherinstqual0" id="staffQualifSelect" class="browser-default greyInp">
                                        <option value=""  selected>Qualification</option>
                                        <option value="B.Ed">B.Ed</option>
                                        <option value="NCE">NCE</option>
                                        <option value="B.A">B.A</option>
                                        <option value="B.Sc">B.Sc</option>
                                        <option value="B.Sc.Ed">B.Sc.Ed</option>
                                        <option value="HND">HND</option>
                                        <option value="OND">OND</option>
                                        <option value="TCI">TCI</option>
                                        <option value="TCII">TCII</option>
                                        <option value="PgD.E">PgD.E</option>
                                        <option value="TCII">TCII</option>
                                        <option value="M.Sc">M.Sc</option>
                                        <option value="M.BA">M.BA</option>
                                        <option value="PHD">PHD</option>
                                        <!--Our Database Call comes in-->
                                    </select>
                                </div>
                                <div class="col m3 s12">
                                    <select name="stfhigherinstgrade0" id="staffHigherGradeSelect" class="browser-default greyInp">
                                        <option value=""  selected>Grade</option>
                                        <option value="1st Class">1st Class</option>
                                        <option value="Distinction">Distinction</option>
                                        <option value="2nd Class Upper">2nd Class Upper</option>
                                        <option value="2nd Class Lower">2nd Class Lower</option>
                                        <option value="Upper Credit">Upper Credit</option>
                                        <option value="Lower Credit">Lower Credit</option>
                                        <option value="Credit">Credit</option>
                                        <option value="3rd Class">3rd Class</option>
                                        <option value="Pass">Pass</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col m6 s12">
                                    <input type="text" name="stfhighercourse0" placeholder="Course of Study" class="greyInp" style="margin-bottom:0px">
                                </div>
                                <div class="col m2 s12">
                                    <input type="number" name="stfhigherinstyear0" 
                                        class="validate greyInp" min="1930" max="2020" placeholder="Year" style="margin-bottom:0px">
                                    <!-- <input type="file" accept="image/*" name="stfhigherinstcert0" class="greyInp validate" style="width:150px"> -->
                                </div>
                                <div class="col m4 s12" style="padding:0">
                                    <div class="file-field input-field" style="margin-top:0">
                                        <div class="btn green">
                                                <span>Upload Certificate</span>
                                        <input type="file" accept="image/*" name="stfhigherinstcert0" class="validate" >
                                        </div>
                                        <div class="file-path-wrapper">
                                            <input type="text" class="file-path validate" id="stfhigherinstcert0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- </div> -->
                    </div>
                    <button type="button" class="btn green waves-effect waves-light" 
                    id="addhigherEd" data-id="staff" 
                    style="font-weight:bold;" name="">
                        ADD OTHER CERTIFICATE
                    </button>
                    <button type="button" class="hide btn white waves-effect waves-light" 
                    id="removeHigherEd" 
                    style="font-weight:bold; color:red;" name="">
                        CERTIFICATE NOT AVAILABLE
                    </button>
                <!-- </div> -->
            </div>

            
            <div class="row">
                <h6 class="leftBord">NYSC Certificate <small>(If available)</small></h6>
                <div class="input-field col s12 m4">
                    <input type="number" name="stfnyscyear" id="numSittings" class="" min="1970" max="2020">
                    <label for="stfnyscyear">Year: </label>
                </div>
                <div class="file-field input-field col s12 m8">
                    <div class="btn green">
                            <span>Upload Certificate</span>
                    <input type="file" accept="image/*" name="stfnysccert" class="" >
                    </div>
                    <div class="file-path-wrapper">
                        <input type="text" class="file-path validate" id="stfnysccert">
                    </div>
                </div>
            </div>

        </div>             


        <div class="row z-depth-1 form-section borderRound">
            <h5 style="text-align:center">Next Of Kin Details</h5>
            <hr/>    
            
            <div class="row">
                <div class="input-field col s12 m3">
                    <select name="noktitle" class="form-control inform">
                        <option value ='MR'>MR</option>
                        <option value ='MRS'>MRS</option>
                        <option value ='MISS'>MISS</option>
                        <option value ='CHIEF'>CHIEF</option>
                        <option value ='MADAM'>MADAM</option>
                        <option value ='SIR'>SIR</option>
                        <option value ='LADY'>LADY</option>
                        <option value ='DR'>DR</option>
                    </select>
                    <label class="form-label" style="text-align:center;">Title</label>
                </div>
                
                <div class="input-field col s12 m3">
                    <input type="text" name="nokfirstName" class="validate">
                    <label for="firstname">Firstname<sup>*</sup></label>
                </div>
                <div class="input-field col s12 m3">
                    <input type="text" name="nokotherName" class="validate">
                    <label for="othername">Middle Name<sup>*</sup></label>
                </div>
                <div class="input-field col s12 m3">
                    <input type="text" name="noklastName" class="validate">
                    <label for="surname">Surname<sup>*</sup></label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12 m6">
                    <input type="text" name="nokaddress" class="validate">
                    <label for="nokaddress">Address<sup>*</sup></label>
                </div>
                <div class="input-field col s12 m3">
                    <input type="text" name="nokrelationship" class="validate">
                    <label for="relationship">Relationship<sup>*</sup></label>
                </div>
                <div class="input-field col s12 m3">
                    <input type="text" name="nokphoneNo" class="validate">
                    <label for="phone">Phone Number<sup>*</sup></label>
                </div>
            </div>

            <div class="col center offset-m8 m4">
                <button type="submit" class="btn btn-large blue waves-effect waves-light" 
                    id="addRecordSubmit" data-id="staff" 
                    style="font-weight:bold;" name="actionStaff">
                    SUBMIT
                </button>
            </div>
        </div>                

    </form>
        </div>


    <div id="disModal" class="modal">
        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>
        <div class="modal-content">
            <div class="progress hide">
                <div class="indeterminate {{$school->themeColor}}"></div>
            </div>
            <div id="modalSuccess" class="row hide">
                <!-- Header -->
                <h4 class="center">Submitted Successfully</h4>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn blue" id="profileLink">View Profile</a>
            <a href="/{{ $school->id }}/staff/create" class="btn green">Register Another Staff</a>
        </div>
    </div>
    <!-- </div> -->
    <input type="hidden" id="lgasControl" value="{{ $lgas }}" />
    <input type="hidden" id="positionControl" value="{{ $stf_position }}" />
<script src="{{ asset('assets/js/camerasnap.js') }}" ></script>
<script src="{{ asset('assets/js/qr.min.js') }}" ></script>
        
    @endsection
    
    <!--This extends to the layout for other Js Usages-->
    
@section('dialog')
@include('includes.guardians.showGuardian')
@endsection