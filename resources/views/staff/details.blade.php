@extends('schools.layout.schoollayout')

@section('title', 'Staff Details')

@section('content')
    

    <div style="margin: 4rem 1rem; font-size:1.1rem" id="profileDiv">
    <h5 style="margin-bottom: 1rem"id="divTitle">Staff Details</h5>
        <div class="row greyBack">
            <div class="col offset-m6 m6 right-align">
                <a href="/{{ $school->id }}/staff" class="titext" style="margin-right:1rem;text-decoration:underline;">Go to Staff List</a>
                <a href="/{{ $school->id }}/staff/{{ $staff->id }}/edit" class="btn btn-floating blue" id="" title="Edit Details">
                    <i class="material-icons">edit</i>
                </a>        
                <button class="btn btn-floating blue" id="printProfile" title="Print">
                    <i class="material-icons">print</i>
                </button>        
            </div>
        </div>
        <div class="row">
            <!-- <div class="col m4 s12 center" style="padding-top:3rem;">
            </div>
            <div class="col m8 s12" style="padding-top:3rem;"> -->
                <table>
                    <tr>
                        <td style="padding:1rem;">
                            <img src="/storage/images/{{$school->prefix}}/passports/{{ $staff->imgFile ? $staff->imgFile : 'no_image.jpg' }}" 
                                style="width:16rem;height:16rem;border-radius:2rem;" class="round" />
                        </td>
                        <td>
                            <table>
                                <tr>
                                    <td class="titext" style="padding-right:1rem">Name:</td>
                                    <td>
                                        {{ strtoupper($staff->title.' '.$staff->lastName.' '.$staff->firstName.' '.$staff->otherNames) ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="titext" style="padding-right:1rem">Reg No:</td>
                                    <td>
                                        {{ $staff->regNo ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="titext" style="padding-right:1rem">Date of Birth:</td>
                                    <td>
                                        {{ $staff->dob ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr style="border-bottom: none">
                                    <td class="titext" style="padding-right:1rem">Gender:</td>
                                    <td>
                                        {{ $staff->getGender() ?? 'N/A' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            <!-- </div> -->

            <div class="col m12">
                <table>
                    <tr>
                        <td class="titext" style="padding-right:1rem">Residential Address:</td>
                        <td>
                            {{ strtoupper($staff->homeAddress) ?? 'N/A' }}
                        </td>
                        <td class="titext" style="padding-right:1rem">State of Origin:</td>
                        <td>
                            {{ $staff->stateOfOrigin() ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="titext" style="padding-right:1rem">LGA of Origin:</td>
                        <td>
                            {{ $staff->lgaOfOrigin() ?? 'N/A' }}
                        </td>
                        <td class="titext" style="padding-right:1rem">Hometown:</td>
                        <td>
                            {{ strtoupper($staff->homeTown)  ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="titext" style="padding-right:1rem">Phone Number:</td>
                        <td>
                            {{ $staff->phoneNo ?? 'N/A' }}
                        </td>
                        <td class="titext" style="padding-right:1rem">Email:</td>
                        <td>
                            {{ $staff->email ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="titext" style="padding-right:1rem">Religion:</td>
                        <td>
                            {{ strtoupper($staff->religion()) ?? 'N/A' }}
                        </td>
                        <td class="titext" style="padding-right:1rem">Religious Denomination:</td>
                        <td>
                            {{ strtoupper($staff->denomination()) ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="titext" style="padding-right:1rem">Staff Category:</td>
                        <td>
                            {{ strtoupper($staff->staffCategory()) ?? 'N/A' }}
                        </td>
                        <td class="titext" style="padding-right:1rem">Staff Position:</td>
                        <td>
                            {{ strtoupper($staff->staffPosition()) ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="titext" style="padding-right:1rem">Staff Grade Level:</td>
                        <td>
                            {{ $grade_level->level ?? 'N/A' }}
                        </td>
                        <td class="titext" style="padding-right:1rem">Staff Rank:</td>
                        <td>
                            {{ $grade_level ? strtoupper($grade_level->rank) : 'N/A' }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

            @if(!empty($nextOfKin))
            <h5 style="padding-top:1rem" style="margin-bottom: .5rem">Next of Kin's Details</h5>
            <div class="row">
                <div class="col m12">
                    <table>
                        <tr>
                            <td class="titext" style="padding-right:1.5rem">Next of Kin's Name:</td>
                            <td>
                                {{ strtoupper($nextOfKin->title.' '.$nextOfKin->firstName.' '.$nextOfKin->otherName.' '.$nextOfKin->lastName) ?? 'N/A' }}
                            </td>
                            <td class="titext" style="padding-right:1.5rem">Next of Kin's Phone Number:</td>
                            <td>
                                {{ $nextOfKin->phoneNo ?? 'N/A' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="titext" style="padding-right:1.5rem">Next of Kin's Relationship:</td>
                            <td>
                                {{ strtoupper($nextOfKin->relationship) ?? 'N/A' }}
                            </td>
                            <td class="titext" style="padding-right:1.5rem">Next of Kin's Address:</td>
                            <td>
                                {{ strtoupper($nextOfKin->address) ?? 'N/A' }}
                            </td>
                        </tr>
                    </table>    
                </div>
            </div>
            @endif



        @if(!empty($certs))
            <h5 >Qualifications</h5>
            <div class="row " style="margin-top:.5rem;">
                <div class="col m12">
                <table>
                @foreach($certs as $cert)
                    <tr class="imgPrintRow" id="certTable">
                        <td class="center">
                            <img src="/storage/images/{{$school->prefix}}/photo/school/{{ $cert->certificate }}"
                            style="border:2px solid black;height:100px;width:100px" class="certView" alt="">
                        </td>
                        <td class="left">
                            <table>
                                <tr>
                                    <th>Certificate Type:</th>
                                    <td style='padding-left:.5rem !important;'>{{ $cert->type }}</td>
                                </tr>
                                <tr>
                                    <th>Institution:</th>
                                    <td style='padding-left:.5rem !important;'>{{ $cert->institution }}</td>
                                </tr>
                                <tr>
                                    <th>Year Obtained:</th>
                                    <td style='padding-left:.5rem !important;'>{{ $cert->year }}</td>
                                </tr>
                                @if(isset($cert->body))
                                    <tr>
                                        <th>Exam Body:</th>
                                        <td style='padding-left:.5rem !important;'>{{ $cert->body }}</td>
                                    </tr>
                                @endif
                                @if(isset($cert->course))
                                    <tr>
                                        <th>Course of Study:</th>
                                        <td style='padding-left:.5rem !important;'>{{ $cert->course }}</td>
                                    </tr>
                                @endif
                                @if(isset($cert->grade))
                                    <tr>
                                        <th>Grade:</th>
                                        <td style='padding-left:.5rem !important;'>{{ $cert->grade }}</td>
                                    </tr>
                                @endif
                            </table>
                        </td>
                        <td class="center">
                            <button class="btn-floating blue viewStaffCert tooltipped" data-tooltip="View">
                                <i class="material-icons">pageview</i>
                            </button> 
                            <button class="btn-floating blue printStaffCert tooltipped" data-tooltip="Print">
                                <i class="material-icons">print</i>
                            </button> 
                        </td>
                    </tr>
                @endforeach
                </table>                        
                </div>
            </div>
        @endif

        @if(!empty($ssce))
        <h5>SSCE Result</h5>
            <table>
                <tr>
                    <th>SUBJECT</th>
                    <th>GRADE</th>
                </tr>
            @foreach($ssce as $sub)
                <tr>
                    <td>{{ $sub->title }}</td>
                    <td>{{ $sub->grade }}</td>
                </tr>
            @endforeach
            </table>
        @endif

        @if($staff->signature != '')
            <h5 >Signature</h5>
            <div class="row" style="padding: .5rem">
                <div class="col m12">
                    <img src="/storage/images/{{$school->prefix}}/photo/school/{{ $staff->signature }}"
                            style="border:2px solid black;height:100px;width:100px" class="certView" alt="">
                </div>
            </div>
        @endif


        </div>

        <div id="certModal" class="modal" >
            <button class="modal-close waves-effect waves-light btn-flat right" id="close">
                <i class="material-icons">close</i>
            </button>
            <div class="modal-content" style="height:auto;">
                <img src="" style="width:95%" />
            </div>
        </div>
    

@endsection
