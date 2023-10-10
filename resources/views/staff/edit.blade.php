{{-- @extends('layout') --}}
@extends('schools.layout.schoollayout')

@section('title', 'Edit '.$staff->firstname.' '.$staff->surname.'\'s Details')

@section('content')



        <!-- Header -->

        <div class="row greyBack" style="margin-top:1rem;">
            <div class="col s4 m2 center">
            <img src="/storage/images/{{$school->prefix}}/passports/{{ $staff->imgFile != '' ? $staff->imgFile : 'no_image.jpg' }}" 
                class="circle" style="height:100px;width:100px" alt="">
            </div>
            <div class="col s8 m10 flow-text" style="padding: 20px; margin-bottom: 10px;">
                Edit {{ $staff->firstName.' '.$staff->lastName.' '.$staff->otherName }}'s  Details
            </div>
        </div>


        <div class="">

        <!-- <div class="row "> -->
        <form method="POST" action="" id="editForm" class='greyBack'>
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
                    <input type="text" name="stffirstName" class="validate" value="{{ $staff->firstName }}">
                    <label for="firstName">First Name<sup>*</sup></label>
                </div>
                <div class="input-field col s12 m4">
                    <input type="text" name="stfotherNames" class="validate" value="{{ $staff->otherNames }}">
                    <label for="otherName">Middle Name<sup>*</sup></label>
                </div>
                <div class="input-field col s12 m4">
                    <input type="text" name="stflastName" class="validate" value="{{ $staff->lastName }}">
                    <label for="lastName">Surname<sup>*</sup></label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12 m4">
                    <select name="stftitle" class="browser-default">
                        @if(trim($staff->title) != '')
                            <option value="{{ $staff->title }}">{{ $staff->title }} </option>
                        @endif
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
                        <option value="1" {{$staff->marital_status_id == '1' ? 'selected' : ''}}>Married</option>
                        <option value="2" {{$staff->marital_status_id == '2' ? 'selected' : ''}}>Single</option>
                        <option value="3" {{$staff->marital_status_id == '3' ? 'selected' : ''}}>Divorced</option>
                        <!--Our Database Call comes in-->
                    </select>
                </div>
                <div class="col s12 m4">
                    <label for="DOB">Date of Birth<sup>*</sup></label>
                    <input type="date" name="stfdob" class="validate" value="{{ $staff->dob }}">
                </div>
            </div>

            <div class="row">            
                <div class="input-field col s12 m4">
                    <select id="gender" name="stfgender" class="browser-default">
                        <option value="">Choose Gender<sup>*</sup></option>
                        <option value="M" {{$staff->gender == 'M' ? 'selected' : ''}}>Male</option>
                        <option value="F" {{$staff->gender == 'F' ? 'selected' : ''}}>Female</option>
                    </select>
                    <!-- <label for="gender" class="">Gender</label> -->
                </div>                     
               
                <div class="input-field col s12 m4 ">
                    <select name="stfstate_of_origin_id" class="browser-default" id="selectState" style="text-transform: capitalize">
                        <option class="" value="">State Of Origin<sup>*</sup></option>
                        @foreach($states as $state)
                            <option class="stateId" {{$staff->state_of_origin_id == $state->id ? 'selected' : ''}}
                                value="{{ $state->id }}" style="text-transform: capitalize">
                                {{ strtolower($state->state) }}
                            </option>
                        @endforeach
                    </select>
                    <!-- <label class="form-label">State of Origin</label> -->
                </div>
                <div class="input-field col s12 m4" >
                    <select name="stflgaOfOriginId" class="browser-default" id="stateLgas"  style="text-transform: capitalize">
                        <option class="" value="">LGA Of Origin<sup>*</sup></option>
                        @foreach($lgas as $lga)
                            @if($lga->state_id == $staff->state_of_origin_id)
                            <option value='{{ $lga->id}}'  style="text-transform: capitalize"
                                {{$staff->lga_of_origin_id == $lga->id ? 'selected' : ''}}
                            > 
                                {{ strtolower($lga->lga) }}
                            </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- </div> -->

            <div class="row">
                <div class="input-field col s12 m4">
                    <input type="text" name="stfhomeTown" class="validate" value="{{ $staff->homeTown }}">
                    <label for="hometown">Hometown<sup>*</sup></label>
                </div>
                <div class="input-field col s12 m8">
                    <input type="text" name="stfhomeAddress" class="validate" value="{{ $staff->homeAddress }}">
                    <!-- <input type="hidden" name="stfregNo" value="holder"> -->
                    <label for="homeAddress">Residential Address<sup>*</sup></label>
                </div>
            </div>

            <div class="row">                               
                <div class="col s12 m4 input-field">
                    <select name="stfcategory_id" class="browser-default" id="staffCategory" >
                        <option class="" value="" selected>Staff Category<sup>*</sup></option>
                        @foreach($stf_category as $cat)
                            <option value="{{ $cat->id }}" 
                                {{$staff->category_id == $cat->id ? 'selected' : ''}}
                            >
                                {{ $cat->name }}
                            </option>
                        @endforeach                      
                    </select>
                </div>
                <div class="col s12 m4 input-field">
                    <select name="stfposition" class="browser-default" id="staffPosition" >
                        <option value="" selected>Staff Position<sup>*</sup></option>
                        @foreach($stf_position as $posit)
                            <option value="{{ $posit->id }}" {{$staff->position == $posit->id ? 'selected' : ''}}>
                                {{ $posit->position }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="input-field col s12 m4">
                    <input type="email" name="stfemail" class="validate" value="{{ $staff->email }}">
                    <label for="email" data-error="wrong" data-success="right">Email<sup>*</sup></label>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m4 input-field">
                    <select name="stfreligion_id" class="browser-default">
                        <option value="" selected>Choose Religion</option>
                        @foreach($religions as $religion)
                            <option value="{{$religion->id}}" {{ $staff->religion_id == $religion->id ? 'selected' : ''}}>
                                {{ $religion->religion }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col s12 m4 input-field">
                    <select name="stfdenomination_id" class="browser-default">
                        <option value="" selected>Choose Denomination</option>
                        @foreach($denominations as $den)
                            <option value="{{$den->id}}" {{ $staff->denomination_id == $den->id ? 'selected' : ''}}>
                                {{ $den->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="input-field col s12 m4">
                    <input type="tel" name="stfphoneNo" class="validate" value="{{ $staff->phoneNo }}">
                    <label for="phone">Phone Number<sup>*</sup></label>
                </div>
            </div>

            <div class="row">   
                <div class="col s12 m4 input-field">
                    <select name="stfsalary_grade_id" id="gradeLevelSelect" class="browser-default">
                        <option value="" >Grade Level<sup>*</sup></option>
                        <option value="0" data-rank="None"
                            {{ $staff->gradeLevel() ? '' : 'selected'}}
                        >None</option>
                        @foreach($grade_level as $level)
                            <option value="{{ $level->id }}" data-rank="{{ $level->rank }}"
                                {{ $staff->gradeLevel() ? ($staff->gradeLevel()->gid == $level->id ? 'selected' : '') : ''}}
                            >
                                {{ $level->level }} - {{ $level->category }}
                            </option>
                        @endforeach
                    </select>
                </div>                 
                <div class="col s12 m4 input-field">
                    <input type="tel" name="stfrank_id" id="rankInput" readonly value="{{ $staff->gradeLevel() ? ($staff->gradeLevel()->rank) : 'None' }}" placeholder='Rank'>
                    <label for="rank">Rank</label>
                </div>
                <div class="col s12 m4">
                    <label for="dateOfAppoint">Date of Appointment<sup>*</sup></label>
                    <input type="date" name="stfappointmentDate" class="validate" value="{{ $staff->appointmentDate }}">
                </div> 
            </div>

            @if(!empty($staff->signature))
                <div class="row">
                    <div class="col m2 s12 center">
                        <img src="/storage/images/{{$school->prefix}}/photo/school/{{ $staff->signature }}"
                        style="border:2px solid black;height:60px;width:60px" class="certView" alt="">
                    </div>
                    <div class="file-field input-field col s12 m10" style="margin-top:0">
                        <div class="btn green">
                                <span>Change Signature Image</span>
                        <input type="file" accept="image/*" name="stfsignature" class="validate" >
                        </div>
                        <div class="file-path-wrapper">
                            <input type="text" class="file-path validate">
                        </div>
                    </div>
                </div>
            @else
                <div class="file-field input-field col s12 m12" style="margin-top:0">
                    <div class="btn green">
                            <span>Upload Signature Image</span>
                    <input type="file" accept="image/*" name="stfsignature" class="validate" >
                    </div>
                    <div class="file-path-wrapper">
                        <input type="text" class="file-path validate">
                    </div>
                </div>
            @endif
        </div>        

        <div class="row z-depth-1 form-section borderRound">
            <h5 style="text-align:center">Qualifications</h5>
            <hr/>    
            
            <div class="row">

                <h6 class="leftBord">Primary/Basic Education <small>(FSLC Optional)</small></h6>
                    @if(!empty($fslc))
                    @foreach($fslc as $fs)
                        <div class="col m2 s12 center">
                            <img src="/storage/images/{{$school->prefix}}/photo/school/{{ $fs->certificate }}"
                            style="border:2px solid black;height:60px;width:60px" class="certView" alt="">
                        </div>
                    <div class="input-field col s12 m8">
                        <input type="text" name="oldfslcinstitution" class="validate" value="{{ isset($fs) ? $fs->institution : '' }}">
                        <label for="surname">Institution</label>
                    </div>
                    <div class="input-field col s12 m2">
                        <input type="number" name="oldfslcyear" class="validate" min="1940" max="2020" value="{{ isset($fs) ? $fs->year : '' }}">
                        <label for="othername">Year</label>
                    </div>
                    <div class="input-field col s12 m4">
                        <select name="oldfslcgrade" class="browser-default greyInp">
                            @if(isset($fs) && isset($fs->grade))
                                <option value="{{$fs->grade}}" selected> {{$fs->grade}} </option>
                            @endif
                            <option value="">FSLC Grade</option>
                            <option value="Distinction">Distinction</option>
                            <option value="Credit">Credit</option>
                            <option value="Pass">Pass</option>
                            <option value="Fail">Fail</option>
                        </select>
                    </div>
                    <div class="file-field input-field col s12 m8">
                        <div class="btn green">
                                <span> {{ isset($fs) ? 'Change' : 'Upload' }} FSLC Certificate</span>
                        <input type="file" accept="image/*" name="oldfslccert" class="validate" >
                        </div>
                        <div class="file-path-wrapper">
                            <input type="text" id="oldfslccert" class="file-path validate">
                        </div>
                    </div>
                    @endforeach
                    @else
                        <div class="input-field col s12 m7">
                            <input type="text" name="stffslcinstitution" class="validate">
                            <label for="surname">Institution</label>
                        </div>
                        <!-- <div class=" col s12 m3">
                            <label for="firstname">FSLC Certificate</label>
                            <input type="file" accept="image/*" name="stffslccert" class="greyInp validate" style="width:150px; margin-top:1rem;">
                        </div> -->
                        <div class="input-field col s12 m2">
                            <input type="number" name="stffslcyear" class="validate" min="1940" max="2020" placeholder="1960">
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
                    @endif
                
            </div>

            @if(!empty($ssce))
            <?php $num = 0; ?>
            @foreach($ssce as $exam)
            <div class="row">
                <h6 class="leftBord">Secondary Education<sup>*</sup></h6>
                <div class="col m2 s12 center">
                    <img src="/storage/images/{{$school->prefix}}/photo/school/{{ $exam->certificate }}"
                    style="border:2px solid black;height:60px;width:60px" class="certView" alt="">
                </div>
                <div class="input-field col s12 m7">
                    <input type="text" name="oldssceinstitution{{$num}}" class="validate" value="{{$exam->institution}}">
                    <input type="hidden" name="oldssceid{{$num}}" value="{{$exam->id}}">
                    <label for="">Institution</label>
                </div>
                <div class="input-field col s12 m3">
                    <input type="number" name="oldssceyear{{$num}}" class="validate" min="1930" max="2020" value="{{$exam->year}}" >
                    <label for="">Year</label>
                </div>
                <div class="col s12 m4">
                    <select name="oldsscebody{{$num}}" class='browser-default' style="margin-top: 1rem;">
                        <option value="{{$exam->body}}">{{ $exam->body }}</option>
                        <option value="">Certificate Exam Body</option>
                        <option value="WASSCE" >WASSCE</option>
                        <option value="GCE">GCE</option>
                        <option value="NECO">NECO</option>
                        <!--Our Database Call comes in-->
                    </select>
                </div>
                <div class="file-field input-field col s12 m8">
                    <div class="btn green">
                            <span>Change Certificate</span>
                    <input type="file" accept="image/*" name="oldsscecert{{$num}}" class="validate" >
                    </div>
                    <div class="file-path-wrapper">
                        <input type="text" class="file-path validate" id="oldsscecert{{$num++}}">
                    </div>
                </div>
            </div>
            @endforeach
            @else
                <div class="row">
                    <h6 class="leftBord">Secondary Education<sup>*</sup></h6>
                    <div class="input-field col s12 m6">
                        <input type="text" name="stfssceinstitution" class="validate">
                        <label for="">Institution</label>
                    </div>
                    <div class="input-field col s12 m2">
                        <input type="number" name="stfssceyear" class="validate" min="1930" max="2020" placeholder="1960">
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
            @endif

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
                            @if(!empty($sscegrade))
                                @foreach($sscegrade as $grade)
                                <tr>
                                    <td>
                                        {{$grade->title}}
                                    </td>
                                    <td>
                                        {{$grade->grade}}
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                            <tr class="hide">
                                <td>
                                    <!-- <input type="text" name="stfsscesub0" class="greyInp"> -->
                                    <select id="ssceSubjectSelect" class="browser-default greyInp">
                                        <option value="" selected>SSCE Subject</option>
                                        @foreach($subjects as $sub)
                                            <option value="{{$sub->id}}">{{$sub->title}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <!-- <input type="text" name="stfsscegrade0" class="greyInp"> -->
                                    <select id="ssceGradeSelect" class="browser-default greyInp">
                                        <option value="" selected>SSCE Grade</option>
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
            
            <div class="row">
                <h6 class="leftBord">HIGHER EDUCATION<sup>*</sup></h6>
                <div id="higherIntn">
                @if(!empty($tertiary))
                    <?php  $ternum = 1; ?>
                    @foreach($tertiary as $higher)
                        <div class="row">
                            <div class="col m2 s12 center">
                                <img src="/storage/images/{{$school->prefix}}/photo/school/{{ $higher->certificate }}"
                                style="border:2px solid black;height:60px;width:60px" class="certView" alt="">
                            </div>
                            <div class="col m6 s12">
                                <input type="text" name="oldhigherinstitute{{$ternum}}" value="{{$higher->institution}}" class="greyInp" style="margin-bottom:0px">
                                <input type="hidden" name="oldhigherid{{$ternum}}" value="{{$higher->id}}">
                            </div>
                            <div class="col m2 s12">
                                <select name="oldhigherinstqual{{$ternum}}" id="staffQualifSelect" class="browser-default greyInp">
                                    <option value="{{$higher->type}}">{{$higher->type}}</option>
                                    <option value="">Qualification</option>
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
                                    <!--Our Database Call comes in-->
                                </select>
                            </div>
                            <div class="col m2 s12">
                                <select name="oldhigherinstgrade{{$ternum}}" id="staffHigherGradeSelect" class="browser-default greyInp">
                                    <option value="{{$higher->grade}}">{{$higher->grade}}</option>
                                    <option value="">Grade</option>
                                    <option value="1st Class">1st Class</option>
                                    <option value="Distinction">Distinction</option>
                                    <option value="2nd Class Upper">2nd Class Upper</option>
                                    <option value="2nd Class Lower">2nd Class Lower</option>
                                    <option value="Upper Credit">Upper Credit</option>
                                    <option value="Lower Credit">Lower Credit</option>
                                    <option value="Credit">Credit</option>
                                    <option value="3rd Class">3rd Class</option>
                                    <option value="Pass">Pass</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col m6 s12">
                                <input type="text" name="oldhighercourse{{$ternum}}" value="{{$higher->course}}" class="greyInp" style="margin-bottom:0px">
                            </div>
                            <div class="col m2 s12">
                                <input type="number" name="oldhigherinstyear{{$ternum}}" 
                                    class="validate greyInp" min="1930" max="2020" value="{{$higher->year}}" style="margin-bottom:0px">
                                <!-- <input type="file" accept="image/*" name="stfhigherinstcert0" class="greyInp validate" style="width:150px"> -->
                            </div>
                            <div class="col m4 s12" style="padding:0">
                                <div class="file-field input-field" style="margin-top:0">
                                    <div class="btn green">
                                            <span>Change Certificate</span>
                                    <input type="file" accept="image/*" name="oldhigherinstcert{{$ternum}}" class="validate" >
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input type="text" class="file-path validate" id="oldhigherinstcert{{$ternum++}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                <!-- <div class="col s12 m12"> -->
                            <!-- <div class style="border-bottom: none;"> -->
                            <div class="row">
                                <div class="col m6 s12">
                                    <input type="text" name="stfhigherinstitute0" placeholder="Institution Attended" class="greyInp" style="margin-bottom:0px">
                                </div>
                                <div class="col m3 s12">
                                    <select name="stfhigherinstqual0" id="staffQualifSelect" class="browser-default greyInp">
                                        <option value="" selected>Qualification</option>
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
                                        <!--Our Database Call comes in-->
                                    </select>
                                </div>
                                <div class="col m3 s12">
                                    <select name="stfhigherinstgrade0" id="staffHigherGradeSelect" class="browser-default greyInp">
                                        <option value="" selected>Grade</option>
                                        <option value="1st Class">1st Class</option>
                                        <option value="Distinction">Distinction</option>
                                        <option value="2nd Class Upper">2nd Class Upper</option>
                                        <option value="2nd Class Lower">2nd Class Lower</option>
                                        <option value="Upper Credit">Upper Credit</option>
                                        <option value="Lower Credit">Lower Credit</option>
                                        <option value="Credit">Credit</option>
                                        <option value="3rd Class">3rd Class</option>
                                        <option value="Pass">Pass</option>
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
                @endif
                </div>
                <button type="button" class="btn green waves-effect waves-light" 
                id="addhigherEd" data-id="staff" 
                style="font-weight:bold;" name="">
                    ADD OTHER CERTIFICATE
                </button>
                <!-- </div> -->
            </div>

            
            <div class="row">
                <h6 class="leftBord">NYSC Certificate <small>(If available)</small></h6>
                @if(!empty($nysc))
                    @foreach($nysc as $ny)
                    <div class="col m2 s12 center">
                        <img src="/storage/images/{{$school->prefix}}/photo/school/{{ $ny->certificate }}"
                        style="border:2px solid black;height:60px;width:60px" class="certView" alt="">
                    </div>
                    <div class="input-field col s12 m4">
                        <input type="number" name="oldnyscyear" value="{{$ny->year}}" min="1970" max="2020">
                        <label for="oldnyscyear">Year: </label>
                    </div>
                    <div class="file-field input-field col s12 m6">
                        <div class="btn green">
                                <span>Change Certificate</span>
                        <input type="file" accept="image/*" name="oldnysccert" class="" >
                        </div>
                        <div class="file-path-wrapper">
                            <input type="text" class="file-path validate" id="oldnysccert">
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="input-field col s12 m4">
                        <input type="number" name="stfnyscyear" class="" min="1970" max="2020">
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
                @endif
            </div>


        </div>             


        <div class="row z-depth-1 form-section borderRound">
            <h5 style="text-align:center">Next Of Kin Details</h5>
            <hr/>    
            <input type='hidden' name='nokid' value="{{ $staff->nextOfKin->id }}">
            <div class="row">
                <div class="input-field col s12 m3">
                    <select name="noktitle" class="form-control inform" >
                        @if(trim($staff->nextOfKin->title) != '')
                            <option value="{{ $staff->nextOfKin->title }}">{{ $staff->nextOfKin->title }}</option>
                        @endif
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
                    <input type="text" name="nokfirstName" class="validate" value="{{ $staff->nextOfKin->firstName ?? '' }}">
                    <label for="firstname">Firstname<sup>*</sup></label>
                </div>
                <div class="input-field col s12 m3">
                    <input type="text" name="nokotherName" class="validate" value="{{ $staff->nextOfKin->otherName ?? '' }}">
                    <label for="othername">Middle Name<sup>*</sup></label>
                </div>
                <div class="input-field col s12 m3">
                    <input type="text" name="noklastName" class="validate" value="{{ $staff->nextOfKin->lastName ?? '' }}">
                    <label for="surname">Surname<sup>*</sup></label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12 m6">
                    <input type="text" name="nokaddress" class="validate" value="{{ $staff->nextOfKin->address ?? '' }}">
                    <label for="nokaddress">Address<sup>*</sup></label>
                </div>
                <div class="input-field col s12 m3">
                    <input type="text" name="nokrelationship" class="validate" value="{{ $staff->nextOfKin->relationship ?? '' }}">
                    <label for="relationship">Relationship<sup>*</sup></label>
                </div>
                <div class="input-field col s12 m3">
                    <input type="text" name="nokphoneNo" class="validate" value="{{ $staff->nextOfKin->phoneNo ?? '' }}">
                    <label for="phone">Phone Number<sup>*</sup></label>
                </div>
            </div>

            <div class="col center offset-m8 m4">
                <button type="submit" class="btn btn-large blue waves-effect waves-light" 
                    id="editRecordSubmit" data-id="{{$staff->id}}" data-act="stf"
                    style="font-weight:bold;" name="actionStaff">
                    SUBMIT
                </button>
            </div>
        </div>                

    </form>
</div>
        
        <div id="certModal" class="modal">
            <button class="modal-close waves-effect waves-light btn-flat right" id="close">
                <i class="material-icons">close</i>
            </button>
            <div class="modal-content">
                <img src="" style="width:95%" />
            </div>
        </div>
    </div>

    <input type="hidden" id="lgasControl" value="{{ $lgas }}" />
    <input type="hidden" id="positionControl" value="{{ $stf_position }}" />
<script src="{{ asset('assets/js/camerasnap.js') }}" ></script>

@endsection
