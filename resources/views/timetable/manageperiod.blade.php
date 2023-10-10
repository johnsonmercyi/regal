        @extends('schools.layout.schoollayout')

        @section('title', 'Timetable')

        @section('content')

            <h4 class="center">Add Period</h4>
          
            <form method="POST" action="" id="periodForm" class='greyBack'>
                {{-- @csrf --}}
                <div id="periodsContainer">
                @if(count($viewPeriod)>1)
                  @foreach ($viewPeriod as $period)
                    <div class="row" >
                      <div class="input-field col s4 period_name" >
                        {{ $period->period_name }}
                        {{-- <label for="class">Select Period</label> --}}
                      </div>
                      <div class="input-field col s4">
                        <input type="time" name="" id="class" class="start_time" value="{{$period->start_time}}">
                        <label for="subject">Start Time</label>
                      </div>
                      <div class="input-field col s4">
                        <input type="time" name="" id="class" class="end_time" value="{{$period->end_time}}">
                        <label for="subject">End Time</label>
                      </div>  
                    </div>
                
                  @endforeach
                @else
                    <div class="row" >
                      <div class="input-field col s4 period_name">
                        First Period
                        {{-- <label for="class">Select Period</label> --}}
                      </div>
                      <div class="input-field col s4">
                        <input type="time" name="" id="class" class="start_time">
                        <label for="subject">Start Time</label>
                      </div>
                      <div class="input-field col s4">
                        <input type="time" name="" id="class" class="end_time">
                        <label for="subject">End Time</label>
                      </div>  
                    </div>
                @endif                
              </div>
                
                <div class="input-field">
                  <input type="button" value="Add Period" class="btn green" id="newPeriodRow">
                </div>
                
                <div class="input-field" style="margin-right: 240px; float: right;">
                  <input type="button" value="Submit" class="btn blue" id="submitPeriods">
                </div> 
          </form>
            
        <script src="{{ asset('assets/js/timeTableManager.js') }}" ></script>

        @endsection