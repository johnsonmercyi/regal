{{-- @extends('layout') --}}
@extends('schools.layout.schoollayout')

@section('title', 'Students List')

@section('content')
<section class="shw_section shw_section_1">
    <div class="shw_title_bar">
        <h5 class="center">Students Height and Wieght</h5>
    </div>
</section>

<section class="shw_section shw_section_2">
    <select id="shw_academic_session_id" name="" class="browser-default">
        <option value="">Academic Session</option>
        @foreach($academic_sessions as $key => $as)
        <option value="{{$as->id}}" {{($as->id === $term_and_academic_session_ids[0]->academic_session_id) ? 'selected' : ''}}>{{$as->sessionName}}</option>
        @endforeach
    </select>
    <select id="shw_term_id" name="" class="browser-default">
        <option value="">Term</option>
        <option value="1" {{(1 === $term_and_academic_session_ids[0]->current_term_id) ? 'selected' : ''}}>First</option>
        <option value="2" {{(2 === $term_and_academic_session_ids[0]->current_term_id) ? 'selected' : ''}}>Second</option>
        <option value="3" {{(3 === $term_and_academic_session_ids[0]->current_term_id) ? 'selected' : ''}}>Third</option>
    </select>
</section>

<section class="shw_section shw_section_3">
    <div class="shw_search_container">
        <div class="shw_input_container">
            <input id="shwSearchField" type="text" class="validate" placeholder="Search">
        </div>
        <div class="shw_search_btn_container">
            <div class="shw_search_btn">
                <span class='center'><i class='material-icons center search_btn' title="Search records">search</i></span>
            </div>
        </div>
    </div>
    <div class="add_btn_container">
        <div class="add_btn" title="Add record">+</div>
    </div>
</section>

<section class="shw_section shw_section_4">

    @foreach ($studentsHwData as $key => $st)
    <div class="shw_data_card_container shw_data_card_container_{{$st->id}}">
        <div class="st_details">
            <div class="st_name">{{$st->name}}</div>
            <div class="st_utility_bar" data-as-id="{{$term_and_academic_session_ids[0]->academic_session_id}}" data-tm-id="{{$term_and_academic_session_ids[0]->current_term_id}}" data-st-name="{{$st->name}}" data-st-id="{{$st->id}}" data-st-h1="{{$st->h1}}" data-st-h2="{{$st->h2}}" data-st-w1="{{$st->w1}}" data-st-w2="{{$st->w2}}">
                <span class="st_gender">{{$st->gender === "M" ? "Male" : "Female"}}</span>
                <span class="vertical_bar">|</span>
                <span class="st_class">{{$st->student_class}}</span>
                <span class='right'><i class='material-icons right edit_btn' title="Edit record">edit</i></span>
                <span class='right'><i class='material-icons right delete_btn' title="Delete record">delete</i></span>
            </div>
            <div class="st_utility_bar shw_bar">
                <span class='shw_data shw_data_h1'><strong>H1</strong>: {{$st->h1 === "N/A"? $st->h1 : $st->h1."ft"}}</span>
                <span class='shw_data shw_data_h2'><strong>H2</strong>: {{$st->h2 === "N/A"? $st->h2 : $st->h2."ft"}}</span>
                <span class='shw_data shw_data_h3'><strong>W1</strong>: {{$st->w1 === "N/A"? $st->w1 : $st->w1."kg"}}</span>
                <span class='shw_data shw_data_h4'><strong>W2</strong>: {{$st->w2 === "N/A"? $st->w2 : $st->w2."kg"}}</span>
            </div>
        </div>
    </div>
    @endforeach
</section>

<section class="shw_dialog_container">
    <div class="shw_dialog">
        <div class="header">Create Record</div>
        <div class="body">
            <div class="shw_select_container shw_select_container_1">
                <select id="shw_as_select" name="" class="browser-default">
                    <option value="">Academic Session *</option>
                    @foreach($academic_sessions as $key => $as)
                    <option value="{{$as->id}}">{{$as->sessionName}}</option>
                    @endforeach
                </select>
                <select id="shw_term_select" name="" class="browser-default">
                    <option value="">Term *</option>
                    <option value="1">First</option>
                    <option value="2">Second</option>
                    <option value="3">Third</option>
                </select>
            </div>

            <div class="shw_select_container shw_select_container_2">
                <select id="shw_student_select" name="" class="browser-default">
                    <option value="">Student *</option>
                    @foreach($students as $key => $student)
                    <option value="{{$student->id}}">{{$student->name}}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" id="student_id" value="">
            <div class="shw_data_inputs">
                <div class="shw_height_input">
                    <input class="textfield" id="h1Input" type="number" class="validate" placeholder="Begining of term height *">
                    <input class="textfield" id="h2Input" type="number" class="validate" placeholder="End of term height">
                </div>

                <div class="shw_weight_input">
                    <input class="textfield" id="w1Input" type="number" class="validate" placeholder="Begining of term weight *">
                    <input class="textfield" id="w2Input" type="number" class="validate" placeholder="End of term weight">
                </div>
            </div>
        </div>
        <div class="footer">
            <div class="shw_btn shw_submit_btn"><span class="btn_txt">Submit</span><span class="submit_loader"></span><span class="submit_loader_done">L</span></div>
            <div class="shw_btn shw_cancel_btn">Cancel</div>
        </div>
    </div>
</section>

<section class="shw_delete_dialog_container">
    <div class="delete_dialog">
        <div class="header">⚠️ Attention!</div>
        <div class="body"></div>
        <div class="footer">
            <div class="ok_btn"><span class="btn_txt">Delete</span><span class="submit_loader"></span><span class="submit_loader_done">L</span></div>
            <div class="cancel_btn">Cancel</div>
        </div>
    </div>
</section>

<section class="utility_page_loader">
    <div class="loader-wrapper">
        <div class="loader_spinner"></div>
    </div>
    <span>Searching...</span>
</section>

<input type="hidden" id="schoolId" value="{{ $school->id}}">
<input type="hidden" id="sessionId" value="{{ $school->academic_session_id}}">
<input type="hidden" id="termId" value="{{ $school->current_term_id}}">
<script src="{{ asset('assets/js/studentsHw.js')}}"></script>
@endsection

<!-- @section('dialog')
    @include('includes.guardians.showGuardian')
@endsection  -->