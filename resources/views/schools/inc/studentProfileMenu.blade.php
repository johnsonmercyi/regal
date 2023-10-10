
<?php $sch_col = $school->themeColor ?: 'blue'; ?>

{{--<ul id="menuSide" class="sidenav {{ $school->themeColor ?: 'blue' }}  lighten-4">--}}
<ul id="menuSide" class="sidenav colCode" style="background-color:{{ $school->themeColor ?: 'blue' }} !important;">
    {{--<li class="center" style="color: {{ $school->themeColor ?: 'blue' }}"><h6>MENU</h6></li>--}}
    <div id="sideHead" class='valign-wrapper'>        
        <!-- Nav Bar Logo -->
        <div id='badgeDiv'>
            <img src="/storage/images/photo/school/{{$school->logo}}" id="schoolBadge" alt="logoImage" class="circle" />
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
                <span class='menuText'>My Results</span>
                <span class='right'><i class='material-icons right'>expand_more</i></span>
            </a>
            <div class="collapsible-body">
                <ul>
                <li class="{{ $sch_col }}  lighten-1"><a href="/{{ $school->id }}/students" >View Results</a></li>
                </ul>
            </div>
            </li>
        </ul>
    </li>
    
</ul>
<input type="hidden" id="schColor" value="{{ $sch_col }}" />
