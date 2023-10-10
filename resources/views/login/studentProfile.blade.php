@extends('schools.layout.profilelayout')

@section('title', ($student->lastName.' '.$student->firstName.' '.$student->otherName))

@section('content')

<!-- <h5 class='center'>STUDENT PROFILE</h5> -->

<div style="margin: 2rem 1rem; font-size:1.3rem">
    <div class="row greyBack valign-wrapper">
        <div class="col m8 s12">
            <h5 style="text-transform:capitalize;">Welcome {{ strtolower($student->lastName.' '.$student->firstName.' '.$student->otherName)}}</h5>
        </div>
        <div class="col m4 s12 center" style="">
            <img src="/storage/images/{{$school->prefix}}/passports/{{ $student->imgFile ?? 'no_image.jpg' }}" style="width:6rem;height:6rem;border-radius:55px;" class="rounded" />
        </div>                    
    </div>

        <div class="row greyBack">
            <div class="col m7 s12 center" style="padding-top:1rem;">
                <div class="row">
                    <div class="col m12 s12" style="">
                        <table>
                            <tr>
                                <td class="titext" style="padding-right:3rem">Gender:</td>
                                <td>
                                    {{ $student->gender}}
                                </td>
                            </tr>
                            <tr>
                                <td class="titext" style="padding-right:3rem">Home Address:</td>
                                <td>
                                    {{ $student->postalAddress}}
                                </td>
                            </tr>
                            <tr>
                                <td class="titext" style="padding-right:3rem">Date of Birth:</td>
                                <td>
                                    {{ $student->dob}}
                                </td>
                            </tr>
                            <tr>
                                <td class="titext" style="padding-right:3rem">State of Origin:</td>
                                <td>
                                    {{ $student->stateOfOrigin()}}
                                </td>
                            </tr>
                            <tr>
                                <td class="titext" style="padding-right:3rem">LGA of Origin:</td>
                                <td>
                                    {{ $student->lgaOfOrigin()}}
                                </td>
                            </tr>
                            <tr>
                                <td class="titext" style="padding-right:3rem">Hometown:</td>
                                <td>
                                    {{ $student->homeTown }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col m1 center" style="padding-top:1rem;">
            </div>

            <div class="col m4 s12 center" style="padding-top:1rem;">
                <div class="row">
                    <div class="col s12 m12">
                        <h5>Results</h5>

                        <div class="input-field">
                            <select class="browser-default" id="resChooseClass" >
                                <option selected disabled>CHOOSE CLASS</option>
                                @if(isset($stdSess) && count($stdSess) > 0)
                                    @foreach($stdSess as $sess)
                                        <option 
                                            value="{{$sess->academic_session_id}}"
                                            data-cls="{{$sess->class_id}}" data-session="{{$sess->sessionName}}"
                                            class="sessName">
                                            {{$sess->level ? $sess->level.' '.$sess->suffix : 'N/A'}}
                                        </option>                                
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="input-field">
                            <select class="browser-default" id="resChooseTerm" >
                                <option selected disabled>CHOOSE TERM</option>
                                <option value="1">FIRST TERM</option>
                                <option value="2">SECOND TERM</option>
                                <option value="3">THIRD TERM</option>
                                <option value="4">ANNUAL RESULT</option>
                            </select>
                        </div>

                        <div class="input-field">
                            <button class="btn" id="studentProfileResultBtn" 
                                data-stdid="{{$student->id}}"
                                style="width:100%"
                                >VIEW RESULT
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </div>

       {{-- @if(isset($stdSess) && count($stdSess) > 1)
        <h5 style="margin-top:2rem;">Termly Results</h5>
        <div>
            <div class="row" style="margin-top:2rem;padding:1rem;">
                    <table>
                        <tr>
                            <th>Academic Session</th>
                            <th>Class</th>
                            <th>Terms</th>
                            <th>Result</th>
                        </tr>
                    @foreach($stdSess as $sess)
                        <tr id="sess{{$sess->academic_session_id}}">
                            <td class="sessName">{{$sess->sessionName}}</td>
                            <td class="classTitle">{{$sess->level.' '.$sess->suffix}}</td>
                            <td>
                                <select name="termVal" id="termIdSelect{{$sess->academic_session_id}}">
                                    <option value="1">First Term</option>
                                    <option value="2">Second Term</option>
                                    <option value="3">Third Term</option>
                                </select>
                            </td>
                            <td>
                                <button 
                                    class="btn viewResultBtn" 
                                    data-id="{{$student->id}}"
                                    data-cls="{{$sess->class_id}}"
                                    data-session="{{$sess->academic_session_id}}"
                                >
                                    VIEW
                                </button>
                            </td>
                        </tr>
                    @endforeach
            </div>
            @endif
        </div>--}}

</div>

{{--
<section class="z-depth-1">
  <div class="row borderRound">
    <div class="col m4 s12 center">
      <img class="circle borderRound" style="height:6rem;width:6rem" src="/storage/images/passports/{{ $student->imgFile ?: 'no_image.jpg' }}" />
    </div>
    <div class="col m4 s12">
      <h6>Name: {{$student->lastName.' '.$student->firstName.' '.$student->otherName}}</h6>
      <h6>RegNo: {{$student->regNo}}</h6>
    </div>
    <div class="col m4 s12">
      <h6>Class: {{$student->classroom()->level.$student->classroom()->suffix}}</h6>
      <h6>Term: <span id="currentTerm">{{$school->Term($school->current_term_id)}}</span>,
    <span id="currentSession">{{$school->AcademicSession->sessionName}}</span> </h6>
    </div>
  </div>
</section>

<section class="section section-icons center" id="profileSection">
    <div class="row">
    
      <a href="#" id="profileResults">
        <div class="col m4 s12">
          <div class="card-panel {{$school->themeColor ?: 'blue'}}">
            <i class="material-icons large white-text">print</i>
            <h5 class="white-text">Print Results</h5>
          </div>
        </div>
      </a>

      <a href="#" id="profileAssignments">
        <div class="col m4 s12">
          <div class="card-panel {{$school->themeColor ?: 'blue'}}">
            <i class="material-icons large white-text">done</i>
            <h5 class="white-text">Assignments</h5>
          </div>
        </div>
      </a>

      <a href="#" id="profileBooks">
        <div class="col m4 s12">
          <div class="card-panel {{$school->themeColor ?: 'blue'}}">
            <i class="material-icons large white-text">library_books</i>
            <h5 class="white-text">E-books</h5>
          </div>
        </div>
      </a>

  </div>
</section>


<div id="resultSection" class="section hide">
    
    <h5 class="center {{$school->themeColor ?: 'blue'}} white-text">View Results</h5>

    @if($stdSessions)
        @foreach($stdSessions as $sess)
            <div class="row" id="card{{$sess->academic_session_id}}" >
                <form action="" id="viewStudentResult{{$sess->academic_session_id}}">
                        <div class="col offset-m3 s12 m6 input-field z-depth-1">
                            <h6 class="center {{$school->themeColor ?: 'blue'}} white-text"><span id="classDesc">{{$sess->level.$sess->suffix}}</span> <span id="sessName">{{$sess->sessionName}} Session</span></h6>
                            <select id="termIdSelect{{$sess->academic_session_id}}" class="browser-default" style="margin-bottom:4px !important;">
                                <option value="">Choose Term </option>
                                <option value="1">First Term</option>
                                <option value="2">Second Term</option>
                                <option value="3">Third Term</option>
                            </select>
                            <!-- <label for="term" >Choose Term</label> -->
                        <div class="progress hide {{$school->themeColor}}">
                            <div class="indeterminate"></div>
                        </div> 
                            <button type="submit" data-session="{{$sess->academic_session_id}}" data-id="{{$student->id}}"
                                data-class="{{$sess->class_id}}"
                                class="btn btn-small right {{$school->themeColor}} stdResultBtn" 
                                style="margin-bottom:4px !important;">View Result</button>
                        </div>            
                        
                                   
                </form>
            </div>
        @endforeach
    @else
        <h5>No Results Found!</h5> 
    @endif
</div>

    <div id="noResultModal" class="modal">
        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>

        <div class="modal-content">
            <h4 class="red-text">No Results for Selected Term!</h4>
                
            <hr>
        </div>
    </div>

--}}

{{--<input type="hidden" id="schoolId" value="{{ $school->id}}">
<input type="hidden" id="schoolColor" value="{{ $school->themeColor}}">--}}
<script type="module" src="{{ asset('assets/js/resultsManager.js') }}"></script>
<script type="module" src="{{ asset('assets/js/loginManager.js') }}"></script>
<script type="module" src="{{ asset('assets/util/layoutManager.js') }}"></script>
<script type="module" src="{{ asset('assets/util/utility.js') }}"></script>
@endsection