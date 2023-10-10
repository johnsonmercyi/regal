@extends('schools.layout.schoollayout')

@section('title', 'Parent Details')

@section('content')
    
<div style="margin: 4rem 1rem 2rem  1rem; font-size:1.1rem" id="profileDiv">
    <h5 style="margin-bottom: .5rem" id="divTitle">Parent Details</h5>  
        
        <div class="row greyBack">
            <div class="col offset-m6 m6 right-align">
                <a href="/schools/{{ $school->id }}/guardians" class="titext" style="margin-right:1rem;text-decoration:underline">Back to Parents' List</a>
                <a href="#" class="btn btn-floating blue" id="" title="Edit Details">
                    <i class="material-icons">edit</i>
                </a>        
                <button class="btn btn-floating blue" id="printProfile" title="Print">
                    <i class="material-icons">print</i>
                </button>        
            </div>
        </div>      

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
                        <td class="titext" >Father's Email Address:</td>
                        <td>
                            {{ strtoupper($guardian->father_email) ?? 'N/A' }}
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
                            {{ strtoupper($guardian->mother_email) ?? 'N/A' }}
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

        {!!
            count($students) > 1 ? '<h5>Children</h5>' : '<h5>Child</h5>'
        !!}
        @foreach($students as $student)
            <div class="row">
                <div class="col m12">
                <table>
                    <tr>
                        <td style="padding:1rem" rowspan="30%">
                            <img src="/storage/images/passports/{{ $student->imgFile ?? 'N/A' }}" 
                                style="width:10rem;height:10rem;border-radius:2rem;" id="profilePix" class="round" />
                        </td>
                        <td >
                            <table>
                                <tr>
                                    <td class="titext" >Name:</td>
                                    <td>
                                        {{ strtoupper($student->lastName.' '.$student->firstName.' '.$student->otherName) ?? 'N/A' }}
                                        <span class="right">
                                            <a href="/{{ $school->id }}/student/{{ $student->id }}" title="View Student" class="btn btn-floating waves-effect waves-light colCode lighten-1 ">
                                                <i class="material-icons">pageview</i>
                                            </a>
                                        </span>
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
                                <tr >
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
                </div>
            </div>
        @endforeach

</div>


@endsection
