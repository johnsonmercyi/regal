@extends('schools.layout.schoollayout')

@section('title', 'Student Details')

@section('content')
    
<div style="margin: 4rem 1rem 2rem  1rem; font-size:1.1rem" id="profileDiv">
    <h5 style="margin-bottom: .5rem" id="divTitle">Student Details</h5>  
        
        <div class="row greyBack">
            <div class="col offset-m6 m6 right-align">
                <a href="/{{ $school->id }}/students" class="titext" style="margin-right:1rem;text-decoration:underline;">Go to Students' List</a>
                <a href="/{{ $school->id }}/students/{{ $student->id }}/edit" class="btn btn-floating blue" id="" title="Edit Details">
                    <i class="material-icons">edit</i>
                </a>        
                <button class="btn btn-floating blue" id="printProfile" title="Print">
                    <i class="material-icons">print</i>
                </button>        
            </div>
        </div>      

        <div class="row">
            {{--<div class="col m4 s12 center" style="padding-top:3rem;">
                <img src="/storage/images/{{$school->prefix}}/passports/{{ $student->imgFile ?? 'N/A' }}" style="width:18rem" class="round" />
            </div>
            <div class="col m8 s12" style="padding-top:3rem;">--}}
                <table>
                    <tr>
                        <td style="padding:1rem" rowspan="30%">
                            <img src="/storage/images/{{$school->prefix}}/passports/{{ $student->imgFile ?? 'N/A' }}" 
                                style="width:16rem;height:16rem;border-radius:2rem;" id="profilePix" class="round" />
                        </td>
                        <td >
                            <table>
                                <tr>
                                    <td class="titext" >Name:</td>
                                    <td>
                                        {{ strtoupper($student->lastName.' '.$student->firstName.' '.$student->otherName) ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="titext" >Reg No:</td>
                                    <td>
                                        {{ $student->regNo ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="titext" >Gender:</td>
                                    <td>
                                        {{ strtoupper($student->getGender()) ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="titext" >Date of Birth:</td>
                                    <td>
                                        {{ $student->dob ?? 'N/A' }}
                                    </td>
                                    
                                </tr>
                                <tr style="border-bottom: none">
                                    <td class="titext" >Class:</td>
                                    <td>
                                        {{ $student->classroom()->className() ?? 'N/A' }}
                                    </td>
                                    
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                
            <!-- </div> -->
            <div class="col m12" style="padding-top:2rem">
                <table id="otherTable">
                    <tr>
                        <td class="titext" >Home Address:</td>
                        <td>
                            {{ strtoupper($student->postalAddress) ?? 'N/A' }}
                        </td>
                        <td class="titext" >State of Origin:</td>
                        <td>
                            {{ $student->stateOfOrigin() ?? 'N/A' }}
                        </td>
                    </tr>

                    <tr>
                        <td class="titext" >LGA of Origin:</td>
                        <td>
                            {{ $student->lgaOfOrigin() ?? 'N/A' }}
                        </td>
                        <td class="titext" >Hometown:</td>
                        <td>
                            {{ strtoupper($student->homeTown) ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="titext" >Phone Number:</td>
                        <td>
                            {{ $student->phoneNo ?? 'N/A' }}
                        </td>
                        <td class="titext" >Residential City:</td>
                        <td>
                            {{ strtoupper($student->residentialCity) ?? 'N/A' }}
                        </td>
                    </tr>    
                    <tr>
                        <td class="titext" >Religion:</td>
                        <td>
                            {{ strtoupper($student->religion()) ?? 'N/A' }}
                        </td>
                        <td class="titext" >Denomination:</td>
                        <td>
                            {{ strtoupper($student->denomination()) ?? 'N/A' }}
                        </td>
                    </tr>    
                </table>
            </div>
        </div>
    
    @if(!empty($guardian))
    <h5 style="padding-top:1rem" style="margin-bottom: .5rem">Parent/Guardians Details</h5>
        <div class="row">
            <div class="col m12">
                <table>
                    <tr>
                        <td class="titext" >Father's Name:</td>
                        <td>
                            {{ strtoupper($guardian->father_title).' '.strtoupper($guardian->father_firstName).' '.strtoupper($guardian->father_otherName).' '.strtoupper($guardian->father_lastName) ?? 'N/A' }}
                        </td>
                        <td class="titext" >Father's Phone Number:</td>
                        <td>
                            {{ strtoupper($guardian->father_phoneNo) ?? 'N/A' }}
                        </td>
                    </tr>

                    <tr>
                        <td class="titext" >Father's Occupation:</td>
                        <td>
                            {{ strtoupper($guardian->father_occupation) ?? 'N/A' }}
                        </td>
                        <td class="titext" >Father's Office Address:</td>
                        <td>
                            {{ strtoupper($guardian->father_officeAddress) ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="titext" >Father's Office Phone Number:</td>
                        <td>
                            {{ strtoupper($guardian->father_officePhone) ?? 'N/A' }}
                        </td>
                        <td class="titext">Father's Email Address:</td>
                        <td>
                            {{ $guardian->father_email ?? 'N/A' }}
                        </td>
                    </tr>     
                    <tr>
                        <td class="titext" >Mother'sName:</td>
                        <td>
                            {{ strtoupper($guardian->mother_title).' '.strtoupper($guardian->mother_firstName).' '.strtoupper($guardian->mother_otherName).' '.strtoupper($guardian->mother_lastName) ?? 'N/A' }}
                        </td>
                        <td class="titext" >Mother's Phone Number:</td>
                        <td>
                            {{ strtoupper($guardian->mother_phoneNo) ?? 'N/A' }}
                        </td>
                    </tr>

                    <tr>
                        <td class="titext" >Mother's Occupation:</td>
                        <td>
                            {{ strtoupper($guardian->mother_occupation) ?? 'N/A' }}
                        </td>
                        <td class="titext" >Mother's Office Address:</td>
                        <td>
                            {{ strtoupper($guardian->mother_officeAddress) ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="titext" >Mother's Office Phone Number:</td>
                        <td>
                            {{ strtoupper($guardian->mother_officePhone) ?? 'N/A' }}
                        </td>
                        <td class="titext" >Mother's Email Address:</td>
                        <td>
                            {{ $guardian->mother_email ?? 'N/A' }}
                        </td>
                    </tr>     
                    <tr>
                        <td class="titext" >Family Doctor's Name:</td>
                        <td>
                            {{ strtoupper($guardian->family_doctor_name) ?? 'N/A' }}
                        </td>
                        <td class="titext" >Hospital Address:</td>
                        <td>
                            {{ strtoupper($guardian->family_doctor_address) ?? 'N/A' }}
                        </td>
                    </tr>     
                    <tr>
                        <td class="titext" >Doctor's Phone Number:</td>
                        <td>
                            {{ strtoupper($guardian->family_doctor_phone) ?? 'N/A' }}
                        </td>
                    </tr>     
                </table>
            </div>
        </div>
        @endif

</div>

        @if(isset($stdSess) && count($stdSess) > 1)
        <h5 style="margin-top:2rem; margin-bottom: 0;">Termly Results</h5>
        <div>
            <div class="row" style="padding:1rem;">
                    <table>
                        <tr>
                            <th>Academic Session</th>
                            <th>Class</th>
                            <th>Terms</th>
                            <th>Result</th>
                        </tr>
                    @foreach($stdSess as $sess)
                        <tr id="sess{{$sess->academic_session_id?? 'N/A' }}">
                            <td class="sessName">{{$sess->sessionName?? 'N/A' }}</td>
                            <td class="classTitle">{{$sess->level.' '.$sess->suffix?? 'N/A' }}</td>
                            <td>
                                <select name="termVal" id="termIdSelect{{$sess->academic_session_id?? 'N/A' }}">
                                    <option value="1">First Term</option>
                                    <option value="2">Second Term</option>
                                    <option value="3">Third Term</option>
                                </select>
                            </td>
                            <td>
                                <button 
                                    class="btn viewResultBtn" 
                                    data-id="{{$student->id?? 'N/A' }}"
                                    data-cls="{{$sess->class_id?? 'N/A' }}"
                                    data-session="{{$sess->academic_session_id?? 'N/A' }}"
                                >
                                    VIEW
                                </button>
                            </td>
                        </tr>
                    @endforeach
            </div>
            @endif
        </div>
<script type="module" src="{{ asset('assets/js/resultsManager.js') }}"></script>

@endsection
