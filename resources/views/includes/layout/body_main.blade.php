<!-- Site Main Body -->
<main class="">
    <div class="main-container padSide">
        <input type="hidden" id="termId" value="{{ $school->current_term_id}}">
        <input type="hidden" id="sessionId" value="{{ $school->academic_session_id}}">
        <input type="hidden" id="schoolId" value="{{ $school->id}}">
        <input type="hidden" id="schoolColor" value="{{ $school->themeColor}}">
        @yield('content')

        @yield('dialog')
    </div>
</main>
