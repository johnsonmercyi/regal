@extends('schools.layout.schoollayout')

@section('title', "$school->name")

@section('content')

  <div class="row greyBack" style="margin-top:3rem">    
    <div class="col m12">
      <h5>Admin Dashboard</h5>
    </div>
  </div>

<section class="section section-icons center" style="margin-top:2rem">
  <!-- <div class="container"> -->
    <div class="row greyBack">    
      <div class="col m6 l3 s12">
        <div class="card-panel" style="display:flex; justify-content:space-between;">
          <div class="blue lighten-4 circle" style="height:5rem; width:5rem;display:flex; justify-content:center;align-items:center;">
            <i class="material-icons medium blue-text" style="">wc</i>
          </div>
          <div>
            <h6 style="color:#a8a8a8">Students</h6>
            <span style="font-size:1.5rem;" id="studentCount">0</span>
          </div>
        </div>
      </div>

      <div class="col m6 l3 s12">
        <div class="card-panel" style="display:flex; justify-content:space-between;">
          <div class="teal accent-1 circle" style="height:5rem; width:5rem;display:flex; justify-content:center;align-items:center;">
            <i class="material-icons medium teal-text">group</i>
          </div>
          <div>
            <h6 style="color:#a8a8a8">Teachers</h6>  
            <span style="font-size:1.5rem;" id="teacherCount">0</span>
          </div>
        </div>
      </div>

      <div class="col m6 l3 s12">
        <div class="card-panel" style="display:flex; justify-content:space-between;">
          <div class="yellow lighten-4 circle" style="height:5rem; width:5rem;display:flex; justify-content:center;align-items:center;">
            <i class="material-icons medium yellow-text">people_outline</i>
          </div>
          <div>  
            <h6 style="color:#a8a8a8">Male Students</h6>
            <span style="font-size:1.5rem;" id="maleCount">0</span>
          </div>
        </div>
      </div>
      
      <div class="col m6 l3 s12">
        <div class="card-panel" style="display:flex; justify-content:space-between;">
          <div class="orange lighten-4 circle" style="height:5rem; width:5rem;display:flex; justify-content:center;align-items:center;">
            <i class="material-icons medium orange-text">people_outline</i>
          </div>
          <div>
            <h6 style="color:#a8a8a8">Female Students</h6>
            <span style="font-size:1.5rem;" id="femaleCount">0</span>
          </div>
        </div>
      </div>
    </div>


    <div class="row greyBack">
    
      <a href="/{{ $school->id }}/students/create">
        <div class="col m4 s12">
          <div class="card-panel flexspace">
            <div class="blue lighten-4 circle pagecut">
              <i class="material-icons small blue-text">person_add</i>
            </div>
            <div class="black-text">
              <h6 class="left">Register Student</h6>
            </div>
          </div>
        </div>
      </a>

      <a href="/{{ $school->id }}/result">
        <div class="col m4 s12">
          <div class="card-panel flexspace">
            <div class="teal accent-1 circle pagecut">
              <i class="material-icons small teal-text">done</i>
            </div>
            <div class="black-text">
              <h6>Enter Scores</h6>
            </div>
          </div>
        </div>
      </a>

      <a href="/{{$school->id}}/results/students">
        <div class="col m4 s12">
          <div class="card-panel flexspace">
            <div class="yellow lighten-4 circle pagecut">
              <i class="material-icons small yellow-text">print</i>
            </div>
            <div class="black-text">
              <h6>Print Results</h6>
            </div>
          </div>
        </div>
      </a>

    </div>
  <!-- </div> -->
  
  <!-- <div class="container"> -->
    <div class="row greyBack">

      <a href="/{{ $school->id }}/students/class">
        <div class="col m4 s12">
          <div class="card-panel flexspace">
            <div class="blue lighten-4 circle pagecut">
              <i class="material-icons small blue-text">group_add</i>
            </div>
            <div class="black-text">
              <h6>Manage Class Members</h6>
            </div>
          </div>
        </div>
      </a>

      <a href="/{{$school->id}}/subjects/assign">
        <div class="col m4 s12">
          <div class="card-panel flexspace">
            <div class="teal accent-1 circle pagecut">
              <i class="material-icons small teal-text">library_books</i>
            </div>
            <div class="black-text">
              <h6>Select Subject Members</h6>
            </div>
          </div>
        </div>
      </a>

      <a href="/{{$school->id}}/class/results">
        <div class="col m4 s12">
          <div class="card-panel flexspace">
            <div class="yellow lighten-4 circle pagecut">
              <i class="material-icons small yellow-text">grid_on</i>
            </div>
            <div class="black-text">
              <h6>View Class Broadsheet</h6>
            </div>
          </div>
        </div>
      </a>

    </div>
  <!-- </div> -->
</section>

@endsection