{{-- @extends('layout') --}}
@extends('schools.layout.schoollayout')

@section('title', 'School Configuration')

@section('content')


        <section class="row form-section borderRound z-depth-1 center" style="margin: 1rem 0;">            
               <h5> Configure School Details</h5>
            <form action=""  id="schoolDetailsForm">
                <div class="row center">
                    <div class="col m6 s12 input-field">
                        <input type="text" name="schoolName" value="{{$school->name}}">
                        <label for="schoolName">School Name </label>
                    </div>

                    <div class="col m6 s12 input-field">
                        <input type="text" name="schoolHead" value="{{$school->head}}">
                        <label for="schoolHead">School Head </label>
                    </div>

                </div>

                <div class="row center">
                    <div class="col m6 s12 input-field">
                        <input type="text" name="schoolAddress" value="{{$school->address}}" >
                        <label for="schoolAddress">School Address </label>
                    </div>

                    <div class="col m6 s12 input-field">
                        <input type="text" name="schoolMotto" value="{{$school->motto}}" >
                        <label for="schoolMotto">School Motto </label>
                    </div>

                </div>

            @if(!empty($school->logo))
                <div class="row">
                    <div class="col m2 s12 center">
                        <img src="/storage/images/{{ $school->prefix }}/photo/school/{{ $school->logo }}"
                        style="border:2px solid black;height:60px;width:60px" class="certView" alt="">
                    </div>
                    <div class="file-field input-field col s12 m10" style="margin-top:0">
                        <div class="btn green">
                                <span>Upload Logo</span>
                        <input type="file" accept="image/*" name="schoolLogo" class="" >
                        </div>
                        <div class="file-path-wrapper">
                            <input type="text" class="file-path ">
                        </div>
                    </div>
                </div>
            @else
                <div class="file-field input-field col s12 m12" style="margin-top:0">
                    <div class="btn green">
                            <span>Upload School Logo</span>
                    <input type="file" accept="image/*" name="schoolLogo" class="" >
                    </div>
                    <div class="file-path-wrapper">
                        <input type="text" class="file-path ">
                    </div>
                </div>
            @endif
            
            @if(!empty($school->headSignature))
                <div class="row">
                    <div class="col m2 s12 center">
                        <img src="/storage/images/{{ $school->prefix }}/photo/school/{{ $school->headSignature }}"
                        style="border:2px solid black;height:60px;width:60px" class="certView" alt="">
                    </div>
                    <div class="file-field input-field col s12 m10" style="margin-top:0">
                        <div class="btn green">
                                <span>Upload Signature</span>
                        <input type="file" accept="image/*" name="headSignature" class="" >
                        </div>
                        <div class="file-path-wrapper">
                            <input type="text" class="file-path ">
                        </div>
                    </div>
                </div>
            @else
                <div class="file-field input-field col s12 m12" style="margin-top:0">
                    <div class="btn green">
                            <span>Upload School Logo</span>
                    <input type="file" accept="image/*" name="headSignature" class="" >
                    </div>
                    <div class="file-path-wrapper">
                        <input type="text" class="file-path ">
                    </div>
                </div>
            @endif

                <div class="col s12 right-align" style="padding-bottom: 10px; margin: 0;">

                    <button class="btn waves-effect waves-light lighten-1 resp-btn colCode" 
                        type="submit" data-act="" id="changeSchoolDetails" data-id="{{ $school->id }}">
                        Save Changes
                    </button>
                </div>
                    <div class="progress hide colCode">
                        <div class="indeterminate colCode"></div>
                    </div>

                {{-- @csrf --}}
            </form>
        </section>
        <br>

        
        <section class="row form-section borderRound center z-depth-1" id="resumeSection">
            <h5>Set Next Term Resumption Date</h5>
            <h6>Next Term, {{ $nextTerm.", ".$properSession." Session"}} starts 
                <span id="nextTermDate">{{$resumeDate->startDate ?? '?'}}</span></h6>
            <div class="row">
                <div class="col offset-m3 m6 s12">
                    <label for="resumeDate" class="form-label">Resumption Date</label>
                    <input id="resumeDate" type="date" class="form-control inform">
                </div>
                <div class="col m3">
                    <button class="btn white-text colCode" id="submitResumeDate">Save</button>
                </div>
            </div>
            <div class="progress hide colCode">
                <div class="indeterminate colCode"></div>
            </div>
        </section>

        <br>

        <section class="row form-section borderRound z-depth-1 center" style="margin-bottom: 10px;" id="sessionChgSection">
            <h5>Configure School Session and Term</h5>
            <form id="sessionChangeForm">
                <div class="row center">
                    <div class="col s12 m6 input-field">
                        <select id="sessionIdSelect" class="browser-default">
                        <option value=""> Choose Session </option>
                        @foreach($acadSession as $acadSess)
                            <option value="{{ $acadSess->id }}">{{ $acadSess->sessionName}}</option>
                        @endforeach
                        </select>
                        <!-- <label for="session" >Choose Session</label> -->
                    </div>

                    <div class="col s12 m6 input-field">
                        <select id="termIdSelect" class="browser-default">
                        <option value="">Choose Term </option>
                        <option value="1"> First Term</option>
                        <option value="2"> Second Term</option>
                        <option value="3"> Third Term</option>
                        </select>
                        <!-- <label for="term" >Choose Term</label> -->
                    </div>
                </div>
            </form>    
            <div class="progress hide colCode">
                <div class="indeterminate colCode"></div>
            </div>
            <div class="col s12 right-align" style="padding-bottom: 10px; margin: 0;">
                <button class="btn waves-effect waves-light lighten-1 resp-btn colCode" type="submit" data-act="" id="sessionChangeBtn" data-id="{{ $school->id }}">
                    Save Changes
                </button>
            </div>
        </section>

        <!-- <section class="section center z-depth-1">
            <h4 class="red-text">Database Backup</h4>
            <div class="row">
                <div class="col offset-m2 m8 s12 center">
                    <button class="btn colCode" id="dbBackUpBtn">Start Backup</button>
                </div>
            </div>
        </section> -->


        <script type="module" src="{{ asset('assets/js/schoolManager.js')}}"></script>

@endsection
