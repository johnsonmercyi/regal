@extends('schools.layout.schoollayout')

@section('title', 'Students List')

@section('content')

<main class="section-heads">
  <section class="shd-section shd-section_1">
    <div class="title-bar">
      School Section Heads
    </div>
    <div class="add-new-button-bar">
      <span class="add-new-btn"></span>
    </div>
  </section>

  <section class="shd-section shd-section-2">
    <?php
    if (count($sectionHeads)) {
      foreach ($sectionHeads as $sectionHead) {
        echo '<div class="data-bar">
          <span class="name">' . $sectionHead->name . '</span> 
          <span class="section">' . strtolower($sectionHead->section_name) . '</span> 
          <span class="separator">|</span>
          <div class="action-btns">
            <i class="material-icons center edit-btn" title="Edit Record">
              edit
            </i>
            <span class="separator">|</span>
            <i class="material-icons center delete-btn" title="Delete Record">
              delete
            </i>
          </div>
        </div>';
      }
    } else {
      echo '<div class="empty-data-set">No data found!🫠</div>';
    }
    ?>
  </section>

  <section class="shd-section create-dialog-wrapper">
    <div class="create-dialog">
      Dialog!
    </div>
  </section>

  <input type="hidden" id="schoolId" value="{{ $school->id}}">
  <input type="hidden" id="sessionId" value="{{ $school->academic_session_id}}">
  <input type="hidden" id="termId" value="{{ $school->current_term_id}}">
  <script src="{{ asset('assets/js/sectionHeads.js')}}"></script>
</main>

@endsection