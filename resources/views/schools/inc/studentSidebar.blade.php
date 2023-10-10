<!-- Site Header -->
<header>

    <!-- Nav Bar -->
    <div class="navbar">
        <!-- <nav style="background-color: {{ $school->themeColor }}; padding-left:2rem;"> -->
        <nav class='white'>
            <div class="nav-wrapper" style='padding-left:20px'>
                <a href="#" id="schoolName" 
                 class="brand-logo black-text hide-on-med-and-down" style='font-size: 1.8rem'>{{ $school->name }}</a>

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

<?php $sch_col = 'darken-3 '.($school->themeColor ?: 'blue'); ?>

{{--<ul id="menuSide" class="sidenav {{ $school->themeColor ?: 'blue' }}  lighten-4">--}}
<ul id="menuSide" class="sidenav colCode darken-4 {{ $sch_col }}" >
    {{--<li class="center" style="color: {{ $school->themeColor ?: 'blue' }}"><h6>MENU</h6></li>--}}
    <div id="sideHead" class='valign-wrapper lighten-1 {{ $school->themeColor ?: "blue" }}'>        
        <!-- Nav Bar Logo -->
        <div id='badgeDiv'>
            <a href="#" class="">
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

    @if(Auth::user()->isStudent())
        <li class="{{ $sch_col }}  lighten-1 no-padding">
            <ul class="collapsible collapsible-accordion ">
                <li class="">
                    <a class="collapsible-header "><span class='left'><i class='material-icons left'>assignment_turned_in</i></span>
                        <span class='menuText'>Check Results</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="{{ $sch_col }}  lighten-1 no-padding">
            <ul class="collapsible collapsible-accordion ">
                <li class="">
                    <a class="collapsible-header "><span class='left'><i class='material-icons left'>assignment_ind</i></span>
                        <span class='menuText'>Assignments</span>
                    </a>
                </li>
            </ul>
        </li>
        
        <li class="{{ $sch_col }}  lighten-1 no-padding">
            <ul class="collapsible collapsible-accordion ">
                <li class="">
                    <a class="collapsible-header "><span class='left'><i class='material-icons left'>assignment_ind</i></span>
                        <span class='menuText'>Lesson Notes</span>
                    </a>
                </li>
            </ul>
        </li>
        
        <li class="{{ $sch_col }}  lighten-1 no-padding">
            <ul class="collapsible collapsible-accordion ">
                <li class="">
                    <a class="collapsible-header "><span class='left'><i class='material-icons left'>assignment_ind</i></span>
                        <span class='menuText'>Newsletters</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="{{ $sch_col }}  lighten-1 no-padding">
            <ul class="collapsible collapsible-accordion ">
                <li class="">
                    <a class="collapsible-header "><span class='left'><i class='material-icons left'>library_books</i></span>
                        <span class='menuText'>TutHub</span>
                    </a>
                </li>
            </ul>
        </li>
    @endif

    @if(Auth::user()->isStaff())
        <li class="{{ $sch_col }}  lighten-1 no-padding">
            <ul class="collapsible collapsible-accordion ">
                <li class="">
                    <a class="collapsible-header "><span class='left'><i class='material-icons left'>spellcheck</i></span>
                        <span class='menuText'>Enter Scores</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="{{ $sch_col }}  lighten-1 no-padding">
            <ul class="collapsible collapsible-accordion ">
                <li class="">
                    <a class="collapsible-header "><span class='left'><i class='material-icons left'>assignment_turned_in</i></span>
                        <span class='menuText'>Subject Results</span>
                    </a>
                </li>
            </ul>
        </li>

    @endif

</ul>
