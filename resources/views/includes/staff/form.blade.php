    <input type="hidden" name="stfstatus" value="1" />
    <input type="hidden" name="stfschoolId" value="{{ $school->id }}" />
    <div class="row ">
        <!-- <div class="col s12 m8  offset-m4"> -->
            <div class="col s12 m4 center">
            <video src="" id="video">Video not available</video>
            </div>
            <div class="col s12 m4 center">
            <input type="submit" class="btn btn-default " style="background-color:#333;color:#fff" id="startbutton" value="Take a photo" disabled />
            </div>

            <canvas id="canvas">            
            </canvas>

            <div class="col s12 m4 output center">
                <img src="" name="imageFile" alt=""  id="photo">
            </div>
        <!-- </div> -->
    </div>

    <br />
    <hr />
    <input type="hidden" name="stfimgFile" id="imgFile" value="" />
    <input type="hidden" name="stfschool_id" id="imgFile" value="{{ $school->id }}" />

        <div class="input-field col s12 m6">
            <input type="text" name="stffirstName" class="validate" value="{{ $staff->firstName ?? '' }}">
            <label for="firstName">First Name</label>
        </div>

        <div class="input-field col s12 m6">
            <input type="text" name="stflastName" class="validate" value="{{ $staff->lastName ?? '' }}">
            <label for="lastName">Last Name</label>
        </div>

        <div class="input-field col s12 m6">
            <input type="text" name="stfotherNames" class="validate" value="{{ $staff->otherNames ?? '' }}">
            <label for="otherName">Other Name</label>
        </div>
        <div class="input-field col s12 m6">
            <input type="date" name="stfdob" class="validate" value="{{ $staff->dob ?? '' }}">
            <label for="DOB">Date of Birth</label>
        </div>
        <div class="col s12 m6 input-field">
            <select name="stfmarital_status_id">
                <option value="" disable selected>Marital Status</option>
                <option value="1" >Married</option>
                <option value="2">Single</option>
                <option value="3">Divorced</option>
                <!--Our Database Call comes in-->
            </select>
        </div>
        <div class="input-field col s12 m6">
            <input type="email" name="stfemail" class="validate" value="{{ $staff->email ?? '' }}">
            <label for="email" data-error="wrong" data-success="right">Email</label>
        </div>
            
        <span class="col s12 l12">
            <label>Gender
                <p>
                    <label>
                    <input type="radio" name="stfgender" value="M"/>
                    <span>Male</span>
                    </label>
                
                    <label>
                    <input type="radio" name="stfgender" value="F"/>
                    <span>Female</span>
                    </label>
                </p>
            </label>
        </span>
        
        <div class="input-field col s12 m6">
            <input type="text" name="stfhomeTown" class="validate" value="{{ $staff->homeTown ?? '' }}">
            <label for="hometown">Hometown</label>
        </div>
        <div class="input-field col s12 m6">
            <input type="text" name="stfhomeAddress" class="validate" value="{{ $staff->homeAddress ?? '' }}">
            <label for="homeAddress">Home Address</label>
        </div>
        <div class="col s12 m6 input-field">
            <select name="stfreligion_id">
                <option value="" disable selected>Choose Religion</option>
                <option value="1" >Catholic</option>
                <option value="2">Anglican</option>
                <option value="3">Protestant</option>
                <!--Our Database Call comes in-->
            </select>
        </div>
            
        <div class="input-field col s12 m6">
            <select name="stfstate_of_origin_id" class="form-control inform" id="selectState" >
            @foreach($states as $state)
                @if(isset($staff->state_of_origin_id) && $staff->state_of_origin_id == $state->id)
                    <option class="stateId" value="{{ $staff->state_of_origin_id }}" selected>
                    {{ $state->state }}
                    </option>
                @else
                    <option class="stateId" value="{{ $state->id }}">
                    {{ $state->state }}
                    </option>
                @endif
            @endforeach
            </select>
            <label class="form-label">State of Origin</label>
        </div>
        <div class="input-field col s12 m6">
            <select name="stflga_of_origin_id" class="form-control inform" id="stateLgas" >
            @if(isset($staff->lga_of_origin_id))
                @foreach($lgas as $lga)
                    @if($staff->lga_of_origin_id == $lga->id)
                        <option value="{{ $staff->lga_of_origin_id }}" selected>
                            {{ $lga->lga }}
                        </option>
                    @endif
                @endforeach
            @endif
            @foreach($lgas as $lga)
                @if($lga->state_id == 1)
                    <option value='{{ $lga->id}}'> {{ $lga->lga }}</option>
                @endif
            @endforeach
            </select>
            <label class="form-label">LGA of Origin</label>
        </div>

        <!-- <div class="input-field col s12 m6">
            <input type="password" name="password" class="validate">
            <label for="password">Password</label>
        </div>
        <div class="input-field col s12 m6">
            <input type="password" name="cPassword" class="validate">
            <label for="password">Confirm Password</label>
        </div> -->

        <div class="input-field col s12 m6">
            <input type="tel" name="stfphoneNo" class="validate" value="{{ $staff->phoneNo ?? '' }}">
            <label for="phone">Phone Number</label>
        </div>
        <div class="input-field col s12 m6">
            <input type="date" name="stfappointmentDate" class="validate" value="{{ $staff->appointmentDate ?? '' }}">
            <label for="dateOfAppoint">Date of Appointment</label>
        </div>
        
        <div class="col s12 m6 input-field">
            <input type="text" name="stfposition" class="validate" value="{{ $staff->position ?? '' }}">
            <label for="position">Staff Position</label>
        </div>
        
        <div class="col s12 m6 input-field">
            <select name="stfsalary_grade_id">
                <option value="" disable selected>Choose Grade</option>
                <option value="1">Upper</option>
                <!--Our Database Call comes in-->
            </select>
        </div>
        
        <div class="col s12 m6 input-field">
            <select name="stfrank_id">
                <option value="" disable selected>Choose Rank</option>
                <option value="1">1</option>
                <!--Our Database Call comes in-->
            </select>
        </div>

        <div class="row z-depth-1">
        <h4 class="center">Certificates</h4>
            @if(count($certs) > 1)
                @foreach($certs as $cert)
                <div class="valign-wrapper" style="display:flex; justify-content:left; padding: .5rem;">
                    <div style="padding:0 2rem;">
                        <img src="/storage/images/{{$school->prefix}}/photo/school/{{ $cert->certificate }}"
                        style="border:2px solid black;height:100px;width:100px" class="certView" alt="">
                    </div>
                    <div style="">
                        <p><span class="titext">Certificate Type:</span> {{ $cert->type }}</p>
                        <p><span class="titext">Institution:</span> {{ $cert->institution }}</p>
                        <p><span class="titext">Year Obtained:</span> {{ $cert->year }}
                            {!! 
                                $cert->grade ? 
                                '<span class="right"><span class="titext">Grade: </span>'.$cert->grade.'</span>' : 
                                '' 
                            !!}
                        </p>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
        
        
        <h4 style="text-align:center">Next Of Kin Details</h4>
        
        <div class="input-field col s12 m6">
            <input type="text" name="noklastName" class="validate" value="{{ $staff->nextOfKin->lastName ?? '' }}">
            <label for="surname">Surname</label>
        </div>
        <div class="input-field col s12 m6">
            <input type="text" name="nokfirstName" class="validate" value="{{ $staff->nextOfKin->firstName ?? '' }}">
            <label for="firstname">Firstname</label>
        </div>
        <div class="input-field col s12 m6">
            <input type="text" name="nokotherName" class="validate" value="{{ $staff->nextOfKin->otherName ?? '' }}">
            <label for="othername">Othername</label>
        </div>
        <div class="input-field col s12 m6">
            <input type="text" name="nokphoneNo" class="validate" value="{{ $staff->nextOfKin->phoneNo ?? '' }}">
            <label for="phone">Phone Number</label>
        </div>
        <div class="input-field col s12 m6">
            <input type="text" name="nokoccupation" class="validate" value="{{ $staff->nextOfKin->occupation ?? '' }}">
            <label for="occupation">Occupation</label>
        </div>
        <div class="input-field col s12 m6">
            <input type="text" name="nokrelationship" class="validate" value="{{ $staff->nextOfKin->relationship ?? '' }}">
            <label for="relationship">Relationship</label>
        </div>
        
