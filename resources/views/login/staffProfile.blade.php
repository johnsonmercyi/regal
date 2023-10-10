@extends('schools.layout.profilelayout')

@section('title', ($staff->lastName.' '.$staff->firstName.' '.$staff->otherName))

@section('content')


<div style="margin: 2rem 1rem; font-size:1.3rem">
    <div class="row greyBack valign-wrapper">
        <div class="col m8 s12">
            <h5 style="text-transform:capitalize;">Welcome {{ strtolower($staff->lastName.' '.$staff->firstName.' '.$staff->otherName)}}</h5>
        </div>
        <div class="col m4 s12 center" style="">
            <img src="/storage/images/passports/{{ $staff->imgFile ?: 'no_image.jpg'  }}" style="width:6rem;border-radius:55px;" class="rounded" />
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
                                    {{ $staff->gender}}
                                </td>
                            </tr>
                            <tr>
                                <td class="titext" style="padding-right:3rem">Home Address:</td>
                                <td>
                                    {{ $staff->postalAddress}}
                                </td>
                            </tr>
                            <tr>
                                <td class="titext" style="padding-right:3rem">Date of Birth:</td>
                                <td>
                                    {{ $staff->dob}}
                                </td>
                            </tr>
                            <tr>
                                <td class="titext" style="padding-right:3rem">State of Origin:</td>
                                <td>
                                    {{ $staff->stateOfOrigin()}}
                                </td>
                            </tr>
                            <tr>
                                <td class="titext" style="padding-right:3rem">LGA of Origin:</td>
                                <td>
                                    {{ $staff->lgaOfOrigin()}}
                                </td>
                            </tr>
                            <tr>
                                <td class="titext" style="padding-right:3rem">Hometown:</td>
                                <td>
                                    {{ $staff->homeTown }}
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
                        <h5>Scores</h5>

                        <div class="input-field">
                            <select class="browser-default" id="" >
                                <option selected disabled>CHOOSE SESSION</option>
                                <option value="1">2020/2021</option>
                            </select>
                        </div>
                        
                        <div class="input-field">
                            <select class="browser-default" id="" >
                                <option selected disabled>CHOOSE TERM</option>
                                <option value="1">FIRST TERM</option>
                                <option value="2">SECOND TERM</option>
                                <option value="3">THIRD TERM</option>
                                <option value="4">ANNUAL RESULT</option>
                            </select>
                        </div>

                        <div class="input-field">
                            <select class="browser-default" id="" >
                                <option selected disabled>CHOOSE CLASS</option>
                                @if(isset($stdSess) && count($stdSess) > 1)
                                    @foreach($stdSess as $sess)
                                        <option value="{{$sess->academic_session_id}}" class="sessName">
                                            {{$sess->level ? $sess->level.' '.$sess->suffix : 'N/A'}}
                                        </option>                                
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        
                        <div class="input-field">
                            <select class="browser-default" id="" >
                                <option selected disabled>CHOOSE SUBJECT</option>
                                <option value="1">SUBJECT</option>
                            </select>
                        </div>

                        <div class="input-field">
                            <button class="btn" style="width:100%">Enter Scores</button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
</div>

<section class="section section-icons center" id="staffProfileSection">
    <div class="row">
    
      <a href="#" id="staffResultBtn">
        <div class="col m4 s12">
          <div class="card-panel {{$school->themeColor ?: 'blue'}}">
            <i class="material-icons large white-text">print</i>
            <h5 class="white-text">View Results</h5>
          </div>
        </div>
      </a>

      <a href="#" id="staffProfileScores">
        <div class="col m4 s12">
          <div class="card-panel {{$school->themeColor ?: 'blue'}}">
            <i class="material-icons large white-text">done</i>
            <h5 class="white-text">Enter Scores</h5>
          </div>
        </div>
      </a>

      <a href="#" id="staffProfileBooks">
        <div class="col m4 s12">
          <div class="card-panel {{$school->themeColor ?: 'blue'}}">
            <i class="material-icons large white-text">library_books</i>
            <h5 class="white-text">E-books</h5>
          </div>
        </div>
      </a>

  </div>
</section>


<div id="scoresSection" class="section hide">
    {{--<h5 class="center {{$school->themeColor ?: 'blue'}} white-text">Select Class</h5>--}}

    @if($stfSubjects)
      <table>
        <tr>
          <th>SUBJECT</th>
          <th>CLASS</th>
          <th>ENTER SCORES</th>
        </tr>

        @foreach($stfSubjects as $sub)
          <tr>
            <td><span id="subjectName">{{$sub->title}}</span></td>
            <td><span id="classTitle">{{$sub->level.$sub->suffix}}</span></td>
            <td>
              <button class="btn btn-floating staffScorePicker"
                data-subject="{{$sub->subject_id}}" data-class="{{$sub->class_id}}"
                >
                  <i class="material-icons">edit</i>
                </button>
            </td>
          </tr>
        @endforeach

      </table>
            
    @else
        <h5>No Classes Found!</h5> 
    @endif
</div>


<div id="staffResultSection" class="section hide">
    {{--<h5 class="center {{$school->themeColor ?: 'blue'}} white-text">Select Class</h5>--}}

    @if($stfSubjects)
            <div class="row" id="" >
              <ul class="collection with-header">
                <li class="collection-header center"><h5>View Results</h5></li>
                @foreach($stfSubjects as $sub)
                  <li class="collection-item">
                    <div><span id="classTitle">{{$sub->level.$sub->suffix}}</span>
                      <a href="#" class="secondary-content staffResultPicker"
                        data-class="{{$sub->class_id}}"
                      >
                        <i class="material-icons">print</i>
                      </a>
                    </div>
                  </li>
                @endforeach
            </div>
    @else
        <h5>No Classes Found!</h5> 
    @endif
</div>

    <div class="progress hide">
      <div class="indeterminate  {{$school->themeColor}}"></div>
    </div>

    <button class="btn {{$school->themeColor}} hide" id="backSelectBtn">Back to Select Class</button class="btn" id="homebtn">

    <table class=" display responsive-table z-depth-1 hide mb-5" id="scoreTable">
        <form method="" action="" id="">
            <thead class="white-text center-align {{ $school->themeColor }}" id="scoreTableHead">
            </thead>
            <tbody class='center' id="scoreTableBody">
            </tbody>
            <tfoot class="center">
                <tr>
                    <td><button type="submit" class="btn btn-default {{$school->themeColor}}"  id="submitScores">Submit</button></td>
                    <td colspan='4'>
                        <div class="progress hide">
                            <div class="indeterminate {{$school->themeColor}}"></div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </form>
    </table>

    <div id="successModal" class="modal modal-fixed-footer">

        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>

        <div class="modal-content">

            <div class="row">
                <!-- Header -->
                <div class="valign-wrapper mb-5">
                   <h2 class="center">Scores Submitted Successfully</h2>
                </div>
            </div>
            <div class="row">
                <div class="valign-wrapper mb-5">
                   <h4 class="center">Use the<span class="{{$school->themeColor}}-text "> Update Buttons </span>to make Future Changes</h4>
                </div>
            </div>
        </div>
    </div>

{{--<input type="hidden" id="schoolId" value="{{ $school->id}}">
<input type="hidden" id="schoolColor" value="{{ $school->themeColor}}">--}}
                      
<input type="hidden" id="termId" value="{{$school->current_term_id}}">
<input type="hidden" id="sessionId" value="{{$school->academic_session_id}}">
<input type="hidden" id="schoolId" value="{{$school->id}}">

<script type="module" src="{{ asset('assets/js/resultsManager.js') }}"></script>
<script type="module" src="{{ asset('assets/js/loginManager.js') }}"></script>
<script type="module" src="{{ asset('assets/custom/scoresManager.js') }}"></script>
@endsection