<!-- Site Header -->
<header>

  <!-- Nav Bar -->
  <div class="navbar">
    <!-- <nav style="background-color: {{ $school->themeColor }}; padding-left:2rem;"> -->
    <nav class='white'>
      <div class="nav-wrapper" style='padding-left:20px'>
        <a href="/schools/{{ $school->id }}" id="schoolName" style='font-size: 1.8rem;height:inherit;overflow:hidden;' class="brand-logo black-text hide-on-med-and-down">{{ $school->name }}</a>

        <!-- Side nav Trigger -->
        <a data-target="mobile-demo" href="#" class="sidenav-trigger">
          <i class="material-icons">menu</i>
        </a>

        <!-- Items on the nav bar -->
        <ul class="right">
          <li>
            <a href="/logout" class="black-text">Logout</a>
          </li>
        </ul>

      </div>
      <!-- <div id="schoolInfo" class=""> -->
      <!-- </div> -->
    </nav>
  </div>
</header>

<?php $sch_col = 'darken-3 ' . ($school->themeColor ?: 'blue'); ?>

{{--<ul id="menuSide" class="sidenav {{ $school->themeColor ?: 'blue' }} lighten-4">--}}
<ul id="menuSide" class="sidenav colCode darken-4 {{ $sch_col }}">
  {{--<li class="center" style="color: {{ $school->themeColor ?: 'blue' }}"><h6>MENU</h6>
  </li>--}}
  <div id="sideHead" class='valign-wrapper lighten-1 {{ $school->themeColor ?: "blue" }}'>
    <!-- Nav Bar Logo -->
    <div id='badgeDiv'>
      <a href="/schools/{{ $school->id }}" class="">
        <img src="/storage/images/{{$school->prefix}}/photo/school/{{$school->logo}}" id="schoolBadge" alt="logoImage" class="circle" />
      </a>
    </div>
    <div class='side-info hide-on-med-and-down center'>
      <span id="schoolCurrentTerm" data-id="{{$school->current_term_id}}">{{$school->Term($school->current_term_id)}}</span><br>
      <span id="schoolCurrentSession" data-id="{{$school->academic_session_id}}">{{$school->AcademicSession->sessionName}} Session</span>
    </div>
    <div id="menuSideBtn">
      <i class='material-icons right'>menu</i>
    </div>
  </div>

  <li class="no-padding">
    <ul class="collapsible collapsible-accordion ">
      <li class="">
        <a class="collapsible-header "><span class='left'><i class='material-icons left'>wc</i></span>
          <span class='menuText'>Student Manager </span>
          <span class='right'><i class='material-icons right'>expand_more</i></span>
        </a>
        <div class="collapsible-body">
          <ul>
            <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/students/create">Register Student</a></li>
            <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/students/capture">Capture Student Photo </a></li>
            <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/students">View Students</a></li>
            <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/students/hw/read">Students Height & Weight</a></li>
          </ul>
        </div>
      </li>
    </ul>
  </li>

  <li class="no-padding">
    <ul class="collapsible collapsible-accordion">
      <li class="">
        <a class="collapsible-header "><span class='left'><i class='material-icons left'>assignment_ind</i></span>
          <span class='menuText'>Staff Manager </span>
          <span class='right'><i class='material-icons right'>expand_more</i></span>
        </a>
        <div class="collapsible-body">
          <ul>
            <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/staff/create">Register Staff</a></li>
            <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/staff/capture">Staff Photo and Signature</a></li>
            <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/staff">View Staff</a></li>
            <!-- <li class="{{ $sch_col }}  lighten-1"><a href="#!" >Manage Staff</a></li> -->
          </ul>
        </div>
      </li>
    </ul>
  </li>

  <li class="no-padding">
    <ul class="collapsible collapsible-accordion ">
      <li class="">
        <a class="collapsible-header "><span class='left'><i class='material-icons left'>group</i></span>
          <span class='menuText'>Parents </span>
          <span class='right'><i class='material-icons right'>expand_more</i></span>
        </a>
        <div class="collapsible-body">
          <ul>
            <li class="{{ $sch_col }}  lighten-1"><a href="/schools/{{ $school->id }}/guardians">View Parents</a></li>
            <!-- <li class="{{ $sch_col }}  lighten-1"><a href="#!" >Create Parents</a></li> -->
            <!-- <li class="{{ $sch_col }}  lighten-1"><a href="#!" >Manage/Search Parents</a></li> -->
          </ul>
        </div>
      </li>
    </ul>
  </li>

  <li class="no-padding">
    <ul class="collapsible collapsible-accordion ">
      <li class="">
        <a class="collapsible-header "><span class='left'><i class='material-icons left'>view_module</i></span>
          <span class='menuText'>Classes </span>
          <span class='right'><i class='material-icons right'>expand_more</i></span>
        </a>
        <div class="collapsible-body">
          <ul>
            <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/students/class">Manage Class Members</a></li>
            <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/class">Assign Form Teacher</a></li>
            {{--<li class="{{ $sch_col }} lighten-1"><a href="#!">Create Classes</a>
      </li>--}}
      <!-- <li class="{{ $sch_col }}  lighten-1"><a href="#!" >Manage/Search Classes</a></li> -->
      <!-- <li class="{{ $sch_col }}  lighten-1"><a href="#!" >Fourth</a></li> -->
    </ul>
    </div>
  </li>
</ul>
</li>

<li class="no-padding">
  <ul class="collapsible collapsible-accordion ">
    <li class="">
      <a class="collapsible-header "><span class='left'><i class='material-icons left'>library_books</i></span>
        <span class='menuText'>Subject Manager </span>
        <span class='right'><i class='material-icons right'>expand_more</i></span>
      </a>
      <div class="collapsible-body">
        <ul>
          <!-- <li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/subjects/class/assign" disabled>Assign Subjects to Section</a></li> -->
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/subjects/class/list">Assign Subjects to Class</a></li>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/subjects/assign">Select Subject Members</a></li>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/subjects/order">Set Subject Order</a></li>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/subjects/nurserysubjects">Manage Nursery Subjects</a></li>


        </ul>
      </div>
    </li>
  </ul>
</li>

<li class="no-padding">
  <ul class="collapsible collapsible-accordion ">
    <li class="">
      <a class="collapsible-header "><span class='left'><i class='material-icons left'>spellcheck</i></span>
        <span class='menuText'>Scores </span>
        <span class='right'><i class='material-icons right'>expand_more</i></span>
      </a>
      <div class="collapsible-body">
        <ul>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/result">Enter Scores</a></li>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/nursery_score">Enter Nursery Scores</a></li>
        </ul>
      </div>
    </li>
  </ul>
</li>



<li class="no-padding">
  <ul class="collapsible collapsible-accordion ">
    <li class="">
      <a class="collapsible-header "><span class='left'><i class='material-icons left'>chat</i></span>
        <span class='menuText'>Comment Manager </span>
        <span class='right'><i class='material-icons right'>expand_more</i></span>
      </a>
      <div class="collapsible-body">
        <ul>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/makecomments">Make Comments</a></li>
          <!-- <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/performance" >Performance</a></li> -->
        </ul>
      </div>
    </li>
  </ul>
</li>

<li class="no-padding">
  <ul class="collapsible collapsible-accordion ">
    <li class="">
      <a class="collapsible-header "><span class='left'><i class='material-icons left'>chat</i></span>
        <span class='menuText'>Trait Manager </span>
        <span class='right'><i class='material-icons right'>expand_more</i></span>
      </a>
      <div class="collapsible-body">
        <ul>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/trait/assessment">Make Trait Assessment</a></li>
        </ul>
      </div>
    </li>
  </ul>
</li>

<!-- <li class="no-padding">
    <ul class="collapsible collapsible-accordion ">
        <li class="">
            <a class="collapsible-header "><span class='left'><i class='material-icons left'>person</i></span>
                <span class='menuText'>School Trait Assessment </span>
                <span class='right'><i class='material-icons right'>expand_more</i></span>
            </a>
            <div class="collapsible-body">
                <ul>

                </ul>
            </div>
        </li>
    </ul>
</li> -->

<li class="no-padding">
  <ul class="collapsible collapsible-accordion ">
    <li class="">
      <a class="collapsible-header "><span class='left'><i class='material-icons left'>assignment_turned_in</i></span>
        <span class='menuText'>Result Manager </span>
        <span class='right'><i class='material-icons right'>expand_more</i></span>
      </a>
      <div class="collapsible-body">
        <ul>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/class/results">Class Broadsheet</a></li>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/results/students">View Results</a></li>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/nursery_results/students_view">View Nursery Results</a></li>
        </ul>
      </div>
    </li>
  </ul>
</li>

<!-- {{-- Time table manager starts here --}} -->
<li class="no-padding">
  <ul class="collapsible collapsible-accordion ">
    <li class="">
      <a class="collapsible-header "><span class='left'><i class='material-icons left'>build</i></span>
        <span class='menuText'>Timetable Manager </span>
        <span class='right'><i class='material-icons right'>expand_more</i></span>
      </a>
      <div class="collapsible-body">
        <ul>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/manageperiod">Set Subject Period</a></li>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/addtimetable">Add Class Timetable</a></li>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/viewtimetable">View Timetable</a></li>
        </ul>
      </div>
    </li>
  </ul>
</li>
{{-- Time table manager ends here --}}

<li class="no-padding">
  <ul class="collapsible collapsible-accordion ">
    <li class="">
      <a class="collapsible-header "><span class='left'><i class='material-icons left'>grid_on</i></span>
        <span class='menuText'>School Sections </span>
        <span class='right'><i class='material-icons right'>expand_more</i></span>
      </a>
      <div class="collapsible-body">
        <ul>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/sections">View Sections</a></li>
          <!-- <li class="{{ $sch_col }}  lighten-1"><a href="#!" >Manage/Search School Sections</a></li> -->
        </ul>
      </div>
    </li>
  </ul>
</li>

{{--<li class="no-padding">
    <ul class="collapsible collapsible-accordion ">
        <li class="">
        <a class="collapsible-header "><span class='left'><i class='material-icons left'>person</i></span>
            <span class='menuText'>School Assessment Formats </span>
            <span class='right'><i class='material-icons right'>expand_more</i></span>
        </a>
        <div class="collapsible-body">
            <ul>
            </ul>
        </div>
        </li>
    </ul>
    </li>--}}

{{--<li class="no-padding">
    <ul class="collapsible collapsible-accordion ">
        <li class="">
        <a class="collapsible-header "><span class='left'><i class='material-icons left'>person</i></span>
            <span class='menuText'>School Grading Formats </span>
            <span class='right'><i class='material-icons right'>expand_more</i></span>
        </a>
        <div class="collapsible-body">
            <ul>
            </ul>
        </div>
        </li>
    </ul>
    </li>--}}

<li class="no-padding">
  <ul class="collapsible collapsible-accordion ">
    <li class="">
      <a class="collapsible-header "><span class='left'><i class='material-icons left'>shopping_cart</i></span>
        <span class='menuText'>Inventory Manager </span>
        <span class='right'><i class='material-icons right'>expand_more</i></span>
      </a>
      <div class="collapsible-body">
        <ul>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/inventory/categories">Manage Categories</a></li>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/inventory/create">Create New Item</a></li>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/inventory/sale">Sell Item</a></li>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/inventory/stock">Restock Item</a></li>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/inventory/reports/index">View Reports</a></li>
        </ul>
      </div>
    </li>
  </ul>
</li>

<li class="no-padding">
  <ul class="collapsible collapsible-accordion ">
    <li class="">
      <a class="collapsible-header "><span class='left'><i class='material-icons left'>hotel</i></span>
        <span class='menuText'>Hostel Manager </span>
        <span class='right'><i class='material-icons right'>expand_more</i></span>
      </a>
      <div class="collapsible-body">
        <ul>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/hostel/assign">Assign Room to Student</a></li>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/inventory/create">View Hostel Members</a></li>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/inventory/sale">Manage Hostel Furniture</a></li>
        </ul>
      </div>
    </li>
  </ul>
</li>

<li class="no-padding">
  <ul class="collapsible collapsible-accordion ">
    <li class="">
      <a class="collapsible-header "><span class='left'><i class='material-icons left'>payment</i></span>
        <span class='menuText'>Payroll Manager </span>
        <span class='right'><i class='material-icons right'>expand_more</i></span>
      </a>
      <div class="collapsible-body">
        <ul>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/staff/salary">Manage Staff Salary</a></li>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/payroll/create">Create Monthly Payroll</a></li>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/payroll/report">View Payroll Reports</a></li>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/payroll/structure">Manage Payroll Structure</a></li>
        </ul>
      </div>
    </li>
  </ul>
</li>

<li class="no-padding">
  <ul class="collapsible collapsible-accordion ">
    <li class="">
      <a class="collapsible-header "><span class='left'><i class='material-icons left'>settings</i></span>
        <span class='menuText'>School Admin </span>
        <span class='right'><i class='material-icons right'>expand_more</i></span>
      </a>
      <div class="collapsible-body">
        <ul>
          <li class="{{ $sch_col }}  lighten-1"><a href="/schools/{{ $school->id }}/edit">School Configuration</a></li>
          <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/trait">Manage School Traits</a></li>
          {{--<li class="{{ $sch_col }} lighten-1"><a href="/{{ $school->id }}/trait/format">Trait Assessment Format</a>
    </li>--}}
    <li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/trait/format/view">Manage Trait Assessment Formats</a></li>
    <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/assessformat/create">Manage Assessment Format</a></li>
    {{--<li class="{{ $sch_col }} lighten-1"><a href="/{{$school->id}}/sections">View Assessment Formats</a>
</li>--}}
<li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/grades/create">Manage Grading Format</a></li>
<li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/students/remove">Remove Student</a></li>
<li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/staff/remove">Remove Staff</a></li>
<li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/section/create">Create School Section</a></li>
<li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/section_heads/create">Manage Section Heads</a></li>
<li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/class/create">Create Class</a></li>
<li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/student/term/status">Manage Active Students</a></li>
<li class="{{ $sch_col }}  lighten-1"><a href="/{{$school->id}}/results/manage">Manage Results</a></li>
<li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/newsletter/create">Upload Newsletter</a></li>
<li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/archive">Archive</a></li>
<li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/promotion">Student Promotion</a></li>
<li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/passwords">Generate Passwords</a></li>
<li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/user/password">Change/Add Password</a></li>
<li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/sync">Synchronize Data</a></li>
{{--<li class="{{ $sch_col }} lighten-1"><a href="/{{$school->id}}/grades/index">View Grading Formats</a></li>--}}
</ul>
</div>
</li>
</ul>
</li>


</ul>