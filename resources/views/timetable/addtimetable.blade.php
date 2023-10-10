@extends('schools.layout.schoollayout')

@section('title', 'Timetable') 

@section('content')


<div class="section center">
  <h4>Add Class Timetable</h4>
  <div class="row z-depth-1 " >
      <form method="" action="" id="">
          <div class="col s12 m3 input-field">
              <select id="classId" class="browser-default">
              <option value=""> Choose Class </option>
              @foreach($allClass as $classroom)
                  <option value="{{ $classroom->id }}">{{ $classroom->level.$classroom->suffix}}</option>
              @endforeach
              </select>
          </div>

          <div class="col s12 m3 input-field">
            <select id="acadId" class="browser-default">
            <option value=""> Choose Session </option>
            @foreach($acadSession as $sess)
                <option value="{{ $sess->id }}">{{ $sess->sessionName}}</option>
            @endforeach
            </select>
        </div>


          {{-- <div class="col s12 m2 center input-field">
              <button type="submit" id="loadAssignedSubjectStudents" class="btn btn-default {{ $school->themeColor }}">Load</button>
          </div> --}}
      </form>
  </div>
</div>

  <div class="progress hide">
      <div class="indeterminate {{$school->themeColor ?: 'blue' }}"></div>
  </div>

<div class="row">
    <fieldset>
        <table class="table-striped viewclass" id="timetabletable">
          @if(count($classTimeTable)>1)
          <tr style="border: 1px solid black;">
            <th></th>
          @foreach ($classTimeTable as $timetable)
              <td>
                {{$timetable->start_time}}<br/>
                {{$timetable->end_time}}
              </td>
              @endforeach
            </tr>
              @else
              <tr>
              </tr>
              @endif
            <tr style="border: 1px solid black;">
              <th>Monday</th>
            @foreach ($classTimeTable as $timetable)
              <td>
                <select name="" id="" class="browser-default"  data-day="Monday" data-period="{{$timetable->id}}">
                  <option value="">Subject</option>
                  @foreach ($subjectList as $subject)
                  <option value="{{$subject->id}}">{{$subject->title}}</option>
                  @endforeach 
                </select>
              </td>
            @endforeach
            </tr>
            <tr style="border: 1px solid black;">
              <th>Tuesday</th>
                  
              @foreach ($classTimeTable as $timetable)
              <td>
                <select name="" id="" class="browser-default" data-day="Tuesday" data-period="{{$timetable->id}}">
                  <option value="">Subject</option>
                  @foreach ($subjectList as $subject)
                  <option value="{{$subject->id}}">{{$subject->title}}</option>
                  @endforeach 
                </select>
              </td>
            @endforeach
            </tr>
            <tr style="border: 1px solid black;">
              <th>Wednesday</th>
              @foreach ($classTimeTable as $timetable)
              <td>
                <select name="" id="" class="browser-default" data-day="Wednesday" data-period="{{$timetable->id}}">
                  <option value="">Subject</option>
                  @foreach ($subjectList as $subject)
                  <option value="{{$subject->id}}">{{$subject->title}}</option>
                  @endforeach 
                </select>
              </td>
            @endforeach
            </tr>
            <tr style="border: 1px solid black;">
              <th>Thursday</th>
              @foreach ($classTimeTable as $timetable)
              <td>
                <select name="" id="" class="browser-default" data-day="Thursday" data-period="{{$timetable->id}}">
                  <option value="">Subject</option>
                  @foreach ($subjectList as $subject)
                  <option value="{{$subject->id}}">{{$subject->title}}</option>
                  @endforeach 
                </select>
              </td>
            @endforeach
            </tr>
            <tr style="border: 1px solid black;">
              <th>Friday</th>
              @foreach ($classTimeTable as $timetable)
              <td>
                <select name="" id="" class="browser-default" data-day="Friday" data-period="{{$timetable->id}}">
                  <option value="">Subject</option>
                  @foreach ($subjectList as $subject)
                  <option value="{{$subject->id}}">{{$subject->title}}</option>
                  @endforeach 
                </select>
              </td>
            @endforeach
            </tr>
            
        </table>
    </fieldset><br>

    <div class="row">
      <button class="btn" id="submitTimetable">Submit</button>
    </div>
</div>

<script src="{{ asset('assets/js/timeTableManager.js') }}" ></script>

@endsection