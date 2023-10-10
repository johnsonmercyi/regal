@extends('schools.layout.schoollayout')

@section('title', 'Timetable')

@section('content')

<div class="section">
  <h4 style="text-align: center">View Class Timetable</h4>
  <div class="row z-depth-1 borderRound" >
      <form action="" id="">
              <div class="col s12 m3 input-field">
                  <select id="classId" class="browser-default">
                  <option value=""> Choose Class </option>
                  @foreach($allClass as $classroom)
                      <option value="{{ $classroom->id }}">{{ $classroom->level.$classroom->suffix}}</option>
                  @endforeach
                  </select>
                  <!-- <label for="classroom" >Choose Class</label> -->
              </div>
  
              <div class="col s12 m3 input-field">
                  <select id="sessionIdSelect" class="browser-default">
                  <option value=""> Choose Session </option>
                  @foreach($acadSession as $acadSess)
                      <option value="{{ $acadSess->id }}">{{ $acadSess->sessionName}}</option>
                  @endforeach
                  </select>
              </div>
  
              <div class="col s12 m3 center input-field">
                  <button type="submit" id="viewclasstimetable" class="btn btn-default colCode">Load</button>
              </div> 
          </form>
      </div>
  </div>

  <div class="classtimetable toggleclasstable">
    <table id="viewClassTimeTable" class="table">
       @if (count($periodTable)>1)
      <thead>
        <tr>
          <th style="background-color: maroon; color: white;">Days/Periods</th>
          @foreach ($periodTable as $periodtime)
          <td style="background-color: maroon; color: white;">
            {{$periodtime->start_time}}<br/>
            {{$periodtime->end_time}}    
          </td>
            @endforeach
          @else
          <th></th>
          @endif
        </tr>
      </thead>
      {{-- <tbody>
        <tr>
          <td>Monday</td>
          <td>English</td>
          <td>Igbo</td>
          <td>French</td>
          <td>Chemistry</td>
          <td>Biology</td>
          <td>French</td>
          <td>Chemistry</td>
          <td>Biology</td>
          <td>Chemistry</td>
          <td>Biology</td>
          <td>Chemistry</td>
          <td>Biology</td>
        </tr>
        <tr>
          <td>Tuesday</td>
          <td>Emma</td>
          <td>Emma</td>
          <td>Emma</td>
          <td>Emma</td>
          <td>Emma</td>
          <td>French</td>
          <td>Chemistry</td>
          <td>Biology</td>
          <td>Chemistry</td>
          <td>Biology</td>
          <td>Chemistry</td>
          <td>Biology</td>
        </tr>
        <tr>
          <td>Wednesday</td>
          <td>Emma</td>
          <td>Emma</td>
          <td>Emma</td>
          <td>Emma</td>
          <td>Emma</td>
          <td>French</td>
          <td>Chemistry</td>
          <td>Biology</td>
          <td>Chemistry</td>
          <td>Biology</td>
          <td>Chemistry</td>
          <td>Biology</td>
        </tr>
        <tr>
          <td>Thursday</td>
          <td>Emma</td>
          <td>Emma</td>
          <td>Emma</td>
          <td>Emma</td>
          <td>Emma</td>
          <td>French</td>
          <td>Chemistry</td>
          <td>Biology</td>
          <td>Chemistry</td>
          <td>Biology</td>
          <td>Chemistry</td>
          <td>Biology</td>
        </tr>
        <tr>
          <td>Friday</td>
          <td>Emma</td>
          <td>Emma</td>
          <td>Emma</td>
          <td>Emma</td>
          <td>Emma</td>
          <td>French</td>
          <td>Chemistry</td>
          <td>Biology</td>
          <td>Chemistry</td>
          <td>Biology</td>
          <td>Chemistry</td>
          <td>Biology</td>
        </tr>
      </tbody> --}}
      <tbody></tbody>
    </table>
  </div>

<script src="{{ asset('assets/js/timeTableManager.js') }}" ></script>

@endsection