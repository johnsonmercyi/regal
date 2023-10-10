@extends('schools.layout.schoollayout')

@section('title', 'Edit '.$student->firstname.' '.$student->surname.'\'s Details')

@section('content')

        <div class="row greyBack" style="margin-top:1rem;">
            <div class="col s4 m2 center">
            <img src="/storage/images/{{$school->prefix}}/passports/{{ $student->imgFile }}" class="circle" style="border:2px solid black;height:100px;width:100px" alt="">
            </div>
            <div class="col s8 m10 flow-text" style="padding: 20px; margin-bottom: 10px;">
                Edit {{ $student->firstName.' '.$student->lastName.' '.$student->otherName }}'s  Details
            </div>
        </div>

<form class="" action="" method="POST" id="editForm" enctype="multipart/form-data">
    <div id="stdRegDiv">
        {{-- @csrf --}}    
        
        <input type="hidden" name="stdstatus" value="1" />
        <input type="hidden" name="stdschool_id" value="{{ $school->id }}" />
        <div class="row z-depth-1 form-section borderRound">
            <h5 class="center">Bio-Data</h5><hr />
            <div class="row ">
                <!-- <div class="col s12 m8  offset-m4"> -->
                    <div class="col s12 m4 center">
                    <video src="" id="video">Video not available</video>
                    </div>
                    <div class="col s12 m4 center">
                        <!-- <button id="startbutton" class="btn-large">
                        </button> -->
                        <i id="startbutton" class="medium material-icons">camera_alt</i>
                    <!-- <input type="submit" class="btn btn-default" style="background-color:#333;color:#fff" id="startbutton" value="Take a photo"/> -->
                    </div>

                    <canvas id="canvas">            
                    </canvas>

                    <div class="col s12 m4 output center">
                        <img src="" name="imageFile" alt="" id="photo">
                    </div>
                <!-- </div> -->
            </div>

                <br />
                <hr />
                <input type="hidden" name="stdimgFile" id="imgFile" value="" />
                <div class="row">
                    <div class="input-field col s12 m4" >
                        <label class="form-label">First Name</label>
                        <input type="text" name="stdfirstName" class="form-control inform "  value="{{ $student->firstName ?? '' }}">
                    </div>
                    <div class="input-field col s12 m4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="stdotherName" class="form-control inform"  value="{{ $student->otherName ?? '' }}">
                    </div>
                    <div class="input-field col s12 m4">
                        <label class="form-label">Surname</label>
                        <input type="text" name="stdlastName" class="form-control inform"  value="{{ $student->lastName ?? '' }}">
                    </div>
                </div>

                <div class="row">
                    <div class=" col s12 m4">
                        <label for="DOB" class="form-label">Date of Birth</label>
                        <input id="DOB" type="date" name="stddob" class="form-control inform"  value="{{ $student->dob ?? '' }}">
                    </div>
                    <div class="input-field col s12 m4">
                        <select id="gender" name="stdgender" class="browser-default" value="{{ $student->gender ?? '' }}">
                        <option value="">Choose Gender</option>
                        <option value="M" {{ $student->gender == 'M' ? 'selected' : '' }}>Male</option>
                        <option value="F" {{ $student->gender == 'F' ? 'selected' : '' }}>Female</option>
                        </select>
                        <!-- <label for="gender" class="">Gender</label> -->
                    </div>
                    <div class="input-field col s12 m4">
                        <label class="form-label">Hometown</label>
                        <input type="text" name="stdhomeTown" class="form-control inform"  value="{{ $student->homeTown ?? '' }}">
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12 m4 ">
                        <select name="stdstate_of_origin_id" class="browser-default" id="selectState" style="text-transform:capitalize">
                            <option class="" value="" >State Of Origin</option>
                            @foreach($states as $state)
                                <option class="stateId" 
                                    value="{{ $state->id }}" style="text-transform:capitalize"
                                    {{ $student->state_of_origin_id == $state->id ? 'selected' : '' }}>
                                    {{ strtolower($state->state) }}
                                </option>
                            @endforeach
                        </select>
                        <!-- <label class="form-label">State of Origin</label> -->
                    </div>
                    <div class="input-field col s12 m4">
                        <select name="stdlga_id" class="browser-default" id="stateLgas" style="text-transform:capitalize">
                            <option class="" value="" >LGA Of Origin</option>
                            @foreach($lgas as $lga)
                                @if($lga->state_id == $student->state_of_origin_id)
                                <option value='{{ $lga->id}}'
                                    style="text-transform:capitalize"
                                    {{ $student->lga_id == $lga->id ? 'selected' : '' }}
                                > 
                                    {{ strtolower($lga->lga) }}
                                </option>
                               @endif 
                            @endforeach
                        </select>
                        <!-- <label class="form-label">LGA of Origin</label> -->
                    </div>
                    <div class="input-field col s12 m4 ">
                        <select name="stdstate_of_residence_id" class="browser-default" id="" style="text-transform:capitalize">
                            <option class="" value="" >State Of Residence</option>
                            @foreach($states as $state)
                                    <option class="" value="{{ $state->id }}"
                                        style="text-transform:capitalize"
                                        {{ $student->state_of_residence_id == $state->id ? 'selected' : '' }}
                                    >
                                        {{ strtolower($state->state) }}
                                    </option>
                            @endforeach
                        </select>
                        <!-- <label class="form-label">State of Origin</label> -->
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12 m8">
                        <label class="form-label">Residential Address</label>
                        <input type="text" name="stdpostalAddress" class="form-control inform"  value="{{ $student->postalAddress ?? '' }}">
                    </div>
                    <div class="input-field col s12 m4">
                        <label class="form-label">Residential City</label>
                        <input type="text" name="stdresidentialCity" class="form-control inform"  value="{{ $student->residentialCity ?? '' }}">
                    </div>                
                </div>
                <div class="row">
                    <div class="input-field col s12 m4">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="stdphoneNo" class="form-control inform"  value="{{ $student->phoneNo ?? '' }}">
                    </div>
                    <div class="input-field col s12 m4">
                        <!-- <label class="form-label">Religion id</label> -->
                        <select name="stdreligion_id" class="browser-default">
                            <option value="">Choose Religion</option>
                            @foreach($religions as $religion)
                                <option value="{{$religion->id}}" {{ $student->religion_id == $religion->id ? 'selected' : ''}}>
                                    {{ $religion->religion }}
                                </option>
                            @endforeach
                        </select>
                            <!-- <label for="classroom" >Choose Class</label> -->
                        {{--<input type="text" name="stdreligionId" class="form-control inform"  value="{{ $student->religionId ?? '' }}">--}}
                    </div>

                    <div class="col s12 m4 input-field">
                        <select name="stddenomination_id" class="browser-default">
                            <option value="" disable selected>Choose Denomination</option>
                            @foreach($denominations as $den)
                                <option value="{{$den->id}}" {{ $student->denomination_id == $den->id ? 'selected' : ''}}>
                                    {{ $den->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- <div class="input-field col s12 m6">
                        <label class="form-label">Creator's Id</label>
                        <input type="text" name="stdcreatedBy" class="form-control inform"  value="">
                    </div> -->
                </div>
            </div>
            <!-- END OF BIO DATA SECTION -->

            <!-- START OF EDUCATION SECTION -->
            <div class="row z-depth-1 form-section borderRound">
                <h5 class="center">Education</h5><hr />
                <div class="row">
                    <div class=" col s12 m6">
                        <label for="DOA" class="form-label">Date of Admission</label>
                        <input id="DOA" type="date" name="stddoa" class="form-control inform"  value="{{ $student->doa ?? '' }}">
                    </div>
                    
                    {{--<div class="input-field col s12 m6">
                        <!-- <label class="form-label">Class</label> -->
                        <select name="stdcurrent_class_id" class="browser-default" id="classOptions">
                            <option value="" id="optionHolder"> Choose Current Class </option>
                            @foreach($allClass as $classroom)
                                <option value="{{ $classroom->id }}"
                                    {{ $student->classroom()->id == $classroom->id ? 'selected' : ''}}
                                >
                                    {{ $classroom->level.$classroom->suffix}}
                                </option>
                            @endforeach
                        </select>--}}                 
                    </div>
                </div>
            </div>

            @if(empty($student->guardian))
            <div class="row z-depth-1 form-section borderRound" id="siblingDiv">
                <div class="row">
                    <h5 class="center">Do you have a sibling (brother/sister) in this school?</h5>
                    <div class="input-field col s12 m8 offset-m2 center" >
                        <button  class='btn green' id='siblingYes'>YES</button>
                        <button  class='btn blue' id='siblingNo'>NO</button>
                    </div>                
                </div>
                <div class="row hide" id="siblingClassDiv">
                    <div class="input-field col s12 m6">
                        <select class="browser-default" id="siblingClassSelect">
                            <option value="" id="optionHolder"> Choose Sibling's Class </option>
                            @foreach($allClass as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->level.$classroom->suffix}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-field col s12 m6">
                        <select id='siblingClassList' class="browser-default">
                            <option value="">Select Sibling</option>
                        </select>
                    </div>
                </div>
                <div class="progress hide">
                        <div class="indeterminate blue"></div>
                </div>
            </div>
            @endif


            <!-- START OF PARENT INFORMATION -->
            <div class="row z-depth-1 form-section borderRound" id="guardianFormDiv">
                <h5 class="center">Parent/Guardian Information</h5>
                <div class="divider"></div>
                <h6 class='center' style='font-weight:bold;'>
                    <span class='red-text'>**</span>
                        This refers to who the student is currently living with!
                    <span class='red-text'>**</span>
                </h6>
                <div class="divider"></div>

                <div class="row">
                    <h6 class="leftBord">Father's Information</h6>
                    <div class="input-field col s12 m4" >
                        <label class="form-label">Father's First Name</label>
                        <input type="text" name="prtfather_firstName" class="form-control inform "  value="{{ $student->guardian->father_firstName ?? '' }}">
                    </div>
                    <div class="input-field col s12 m4">
                        <label class="form-label">Father's Middle Name</label>
                        <input type="text" name="prtfather_otherName" class="form-control inform"  value="{{ $student->guardian->father_otherName ?? '' }}">
                    </div>
                    <div class="input-field col s12 m4">
                        <label class="form-label">Father's Surname</label>
                        <input type="text" name="prtfather_lastName" class="form-control inform"  value="{{ $student->guardian->father_lastName ?? '' }}">
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12 m4">
                        <select name="prtfather_title" class="browser-default">
                            @if(isset($student->guardian->father_title) && trim($student->guardian->father_title) != '')
                                <option value ='{{$student->guardian->father_title}}'>{{$student->guardian->father_title}}</option>
                            @endif
                            <option value =''>Father's Title</option>
                            <option value ='MR'>MR</option>
                            <option value ='CHIEF'>CHIEF</option>
                            <option value ='SIR'>SIR</option>
                            <option value ='DR'>DR</option>
                        </select>
                        <!-- <label class="form-label" style="text-align:center;">Father's Title</label> -->
                    </div>

                    
                    <div class="input-field col s12 m4">
                        <label class="form-label">Father's Phone Number </label>
                        <input type="text" name="prtfather_phoneNo" class="form-control inform"  value="{{ $student->guardian->father_phoneNo ?? '' }}">
                    </div>

                    <div class="input-field col s12 m4">
                        <select name="prtfather_occupation" class="browser-default" value="{{ $student->guardian->father_occupation ?? '' }}">
                            @if(isset($student->guardian->father_occupation) && trim($student->guardian->father_occupation) != '')
                                <option value ='{{$student->guardian->father_occupation}}'>{{$student->guardian->father_occupation}}</option>
                            @endif
                            <option value=''>Father's Occupation</option>
                            <option value='Businessman'>Businessman</option>
                            <option value='Craftsman'>Craftsman</option>
                            <option value='Driver'>Driver</option>
                            <option value='Civil Servant'>Civil Servant</option>
                            <option value='Doctor'>Doctor</option>
                            <option value='Engineer'>Engineer</option>
                            <option value='Farmer'>Farmer</option>
                            <option value='Teacher'>Teacher</option>
                            <option value='Nurse'>Nurse</option>
                            <option value='Pharmacist'>Pharmacist</option>
                            <option value='Lawyer'>Lawyer</option>
                            <option value='Physician'>Physician</option>
                            <option value='Other'>Other</option>
                        </select>
                        <!-- <label class="form-label" style="text-align:center;">Father's Occupation</label> -->
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12 m4">
                        <label class="form-label">Father's Email</label>
                        <input type="text" name="prtfather_email" class="form-control inform"  value="{{ $student->guardian->father_email ?? '' }}">
                        </select>
                    </div>
                    <div class="input-field col s12 m4">
                        <label class="form-label">Father's Office Address</label>
                        <input type="text" name="prtfather_officeAddress" class="form-control inform"  value="{{ $student->guardian->father_officeAddress ?? '' }}">
                        </select>
                    </div>
                    <div class="input-field col s12 m4">
                        <label class="form-label">Father's Office Phone Number </label>
                        <input type="text" name="prtfather_officePhone" class="form-control inform"  value="{{ $student->guardian->father_officePhone ?? '' }}">
                    </div>
                </div>

                <div class="row">
                </div>

                <div class="row">
                    <h6 class="leftBord">Mother's Information</h6>
                    <div class="input-field col s12 m4" >
                        <label class="form-label">Mother's First Name</label>
                        <input type="text" name="prtmother_firstName" class="form-control inform "  value="{{ $student->guardian->mother_firstName ?? '' }}">
                    </div>
                    <div class="input-field col s12 m4">
                        <label class="form-label">Mother's Middle Name</label>
                        <input type="text" name="prtmother_otherName" class="form-control inform"  value="{{ $student->guardian->mother_otherName ?? '' }}">
                    </div>
                    <div class="input-field col s12 m4">
                        <label class="form-label">Mother's Surname</label>
                        <input type="text" name="prtmother_lastName" class="form-control inform"  value="{{ $student->guardian->mother_lastName ?? '' }}">
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12 m4">
                        <select name="prtmother_title" class="browser-default" value="{{ $student->guardian->mother_title ?? '' }}">
                            @if(isset($student->guardian->mother_title) && trim($student->guardian->mother_title) != '')
                                <option value ='{{$student->guardian->mother_title}}'>{{$student->guardian->mother_title}}</option>
                            @endif
                            <option value =''>Mother's Title</option>
                            <option value ='MRS'>MRS</option>
                            <option value ='DR'>DR</option>
                            <option value ='MADAM'>MADAM</option>
                            <option value ='LADY'>LADY</option>
                        </select>
                        <!-- <label class="form-label" style="text-align:center;">Mother's Title</label> -->
                    </div>

                    
                    <div class="input-field col s12 m4">
                        <label class="form-label">Mother's Phone Number </label>
                        <input type="text" name="prtmother_phoneNo" class="form-control inform"  value="{{ $student->guardian->mother_phoneNo ?? '' }}">
                    </div>

                    <div class="input-field col s12 m4">
                        <select name="prtmother_occupation" class="browser-default" value="{{ $student->guardian->mother_occupation ?? '' }}">
                            @if(isset($student->guardian->mother_occupation) && trim($student->guardian->mother_occupation) != '')
                                <option value ='{{$student->guardian->mother_occupation}}'>{{$student->guardian->mother_occupation}}</option>
                            @endif
                            <option value=''>Mother's Occupation</option>
                            <option value='Businesswoman'>Businesswoman</option>
                            <option value='Seamstress'>Seamstress</option>
                            <option value='Civil Servant'>Civil Servant</option>
                            <option value='Doctor'>Doctor</option>
                            <option value='Engineer'>Engineer</option>
                            <option value='Caterer'>Caterer</option>
                            <option value='Teacher'>Teacher</option>
                            <option value='Public Servant'>Public Servant</option>
                            <option value='Clergy'>Clergy</option>
                            <option value='Nurse'>Nurse</option>
                            <option value='Pharmacist'>Pharmacist</option>
                            <option value='Lawyer'>Lawyer</option>
                            <option value='Physician'>Physician</option>
                            <option value='Other'>Other</option>
                        </select>
                        <!-- <label class="form-label" style="text-align:center;">Mother's Occupation</label> -->
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12 m4">
                        <label class="form-label">Mother's Email</label>
                        <input type="text" name="prtmother_email" class="form-control inform"  value="{{ $student->guardian->mother_email ?? '' }}">
                        </select>
                    </div>
                    <div class="input-field col s12 m4">
                        <label class="form-label">Mother's Office Address</label>
                        <input type="text" name="prtmother_officeAddress" class="form-control inform"  value="{{ $student->guardian->mother_officeAddress ?? '' }}">
                        </select>
                    </div>
                    <div class="input-field col s12 m4">
                        <label class="form-label">Mother's Office Phone Number </label>
                        <input type="text" name="prtmother_officePhone" class="form-control inform"  value="{{ $student->guardian->mother_officePhone ?? '' }}">
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12 m4" >
                        <label class="form-label">Family Doctor's Name</label>
                        <input type="text" name="prtfamily_doctor_name" class="form-control inform "  value="{{ $student->guardian->family_doctor_name ?? '' }}">
                    </div>
                    <div class="input-field col s12 m4">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="prtfamily_doctor_phone" class="form-control inform"  value="{{ $student->guardian->family_doctor_phone ?? '' }}">
                    </div>
                    <div class="input-field col s12 m4">
                        <label class="form-label">Hospital Address</label>
                        <input type="text" name="prtfamily_doctor_address" class="form-control inform"  value="{{ $student->guardian->family_doctor_address ?? '' }}">
                    </div>
                </div>
            </div>

            <br />
            <!-- START OF MEDICAL INFORMATION -->
            <div class="row z-depth-1 form-section borderRound">
                <h5 class="center">Medical Information</h5>
                <div class="divider"></div>
                
                <div class="row">
                <div class="input-field col s12 m6">
                        <select name="stdhealthchallenge" class="form-control inform" value="{{ $student->guardian->occupation ?? '' }}">
                            <option value='' >None</option>
                            <option value='Sickle Cell Anemia'>Sickle Cell Anemia</option>
                            <option value='Epilepsy'>Epilepsy</option>
                            <option value='Asthma'>Asthma</option>
                        </select>
                        <label class="form-label" style="text-align:center;">Health Challenges</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <select name="stdphysicalchallenge" class="form-control inform" value="{{ $student->guardian->occupation ?? '' }}">
                            <option value='' >None</option>
                            <option value='Blind'>Blind</option>
                            <option value='Deaf'>Deaf</option>
                            <option value='Mute'>Dumb</option>
                            <option value='Crippled'>Crippled</option>
                        </select>
                        <label class="form-label" style="text-align:center;">Physical Challenges</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col center offset-m4 m4">
                        <button type="submit" class="btn btn-large blue" 
                            id="editRecordSubmit" data-act="std"  data-id="{{$student->id}}"
                            style="font-weight:bold;">
                            SUBMIT
                        </button>
                    </div>
                </div>
            </div>
    </form>

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
    </div>

    <input type="hidden" id="lgasControl" value="{{ $lgas }}" />
    <script src="{{ asset('assets/js/camerasnap.js') }}" ></script>
</div>

@endsection
