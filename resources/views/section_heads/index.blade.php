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
      <div class="header">Create Section Heads</div>
      <form action="" id="sectionHeadForm">
        <div class="main">
          <select id="sectionSelect" name="sectionSelect" class="browser-default section-head-element">
            <option value="">Select School Section</option>
            @foreach($sections as $key => $section)
            <option value="{{$section->id}}">{{$section->sectionName}}</option>
            @endforeach
          </select>

          <div class="row">
            <div class="input-field col s12">
              <input type="text" name="sectionHead" id="sectionHead" class="validate">
              <label for="otherName">Name of Section Head</label>
            </div>
            <div class="file-field input-field col s12">
              <div class="btn {{ $school->themeColor }} lighten-1">
                <span>Upload Signature</span>
                <input type="file" accept="image/*" name="sectionHeadSign" id="sectionHeadSign">
              </div>
              <div class="file-path-wrapper">
                <input class="file-path validate" type="text" placeholder="Signature of Section Head">
              </div>
            </div>
            <div class="error-message"></div>
          </div>
        </div>
        <div class="footer">
          <div class="button-wrapper">
            <span class="submit-btn">Submit</span>
            <span class="cancel-btn">Cancel</span>
          </div>
        </div>
      </form>
    </div>
  </section>

  <!-- ⚠️TODO: -->
  <!-- 1. Create update dialog -->
    <!-- copy the create dialog and do the needful in css and here -->
  <!-- 2. Do the delete dialog -->
  <!-- 3. Modify the comment section now to include the name of the id of the commenter -->

  <input type="hidden" id="schoolId" value="{{ $school->id}}">
  <input type="hidden" id="sessionId" value="{{ $school->academic_session_id}}">
  <input type="hidden" id="termId" value="{{ $school->current_term_id}}">
  <script src="{{ asset('assets/js/sectionHeads.js')}}"></script>
</main>

@endsection