@extends('schools.layout.schoollayout')

@section('title', 'Subject Students')

@section('content')

<div class="section center">
    <h4>Manage Nursery Subject</h4>
    <div class="row z-depth-1 " >
        {{-- <form method="" action="" id="">
            <div class="col s12 m3 input-field">
                <select id="classId" class="browser-default">
                </select>
            </div>

            <div class="col s12 m4 input-field">
              <input type="text" class="form-group" placeholder="Enter Subject">
            </div>

            <div class="col s12 m2 center input-field">
                <button type="submit" id="addNurserySubject" class="btn btn-default {{ $school->themeColor }}">Load</button>
            </div>
        </form> --}}


        <div class="col s12 m6 center input-field">
            <button data-target="modal1" id="addNurserySubjectCategory" class=" btn modal-trigger waves-effect waves-light btn-large {{ $school->themeColor }}">Create Subject Category</button>
        </div>
        <div class="col s12 m6 center input-field">
            <button data-target="subjectModal" id="addNurserySubject" class="btn btn-large  modal-trigger {{ $school->themeColor }}">Add Nursery Subject</button>
        </div>
    </div>
</div>

<section id='sectionAssignTableSection' class='center mb-5'>
  <h5 id='classInfo'></h5>
  
  <div class='row'>
      <div class='col m5'>
          <h5>Subject Categories</h5>
          <table class=" display centered z-depth-1 mb-5 mt-5" id="sectionAssignedSubjectsTable">
                  <thead class="white-text colCode">
                    <th>S/N</th>
                      <th>Subject Category</th>
                      <th>Date Added</th>
                  </thead>

                  @foreach ($subjectCategory as $category)
                  <tbody class='center'>
                        
                    <tr>
                      <td>{{$category->id}}</td>
                      <td style="text-transform: uppercase">{{$category->title}}</td>
                      <td>{{$category->created_at}}</td>
                    </tr>
                   
                  </tbody>
                  @endforeach
          </table>
      </div>

      <div class="col m7">
          <h5>Subjects</h5>
          <table class=" display centered z-depth-1 mb-5 mt-5" id="sectionUnassignedSubjectsTable">
                  <thead class="white-text colCode">
                    <td>S/N</td>
                      <th>Subject Title</th>
                      {{-- <th>Date Added</th> --}}
                  </thead>
                  @foreach ($nurserySubjects as $subjects)
                  <tbody class='center'>
                        
                    <tr>
                      <td>{{$subjects->id}}</td>
                      <td style="text-transform: uppercase">{{$subjects->title}}</td>
                      {{-- <td>{{$category->created_at}}</td> --}}
                    </tr>
                   
                  </tbody>
                  @endforeach
          </table>
      </div>
  </div>
</section>

    <div id="selectStudents">
        @include('includes.layout.confirmModal')
    </div>

  <!--Category Modal Start -->
  <div id="modal1" class="modal">
    <div class="modal-content">
      <h4 style="color: gray; text-align:center; font-weight:bolder">ADD SUBJECT CATEGORY</h4>
      <div class="row">
        <form class="col s12" id="subjectCateForm">
          <div class="row">
            <div class="input-field col s12">
              <input id="subject_Category" type="text" class="validate" >
              <label for="Subject Category">SUBJECT CATEGORY</label>
            </div>
          </div>
          @csrf
        </form>
      </div>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close btn btn-alert">Close</a>
      <button class="btn btn-success" id="nurSubjectCategory" style="float: left"> SUBMIT</button>
    </div>
  </div>
  <!--Category Modal Start -->

  <!--Suject Modal Start -->
  <div id="subjectModal" class="modal">
    <div class="modal-content">
      <h4 style="color: gray; text-align:center; font-weight:bolder">CREATE SUBJECT</h4>
      <div class="row">
        <form class="col s12" id="subjectForm">
          <div class="row" id="createSubject">
            <div class="input-field col s5">
              <select id="categoryID">
                <option value="">Select Subject Category</option>
                @foreach ($subjectCategory as $category)
                <option value="{{$category->id}}" style="text-transform: uppercase">{{$category->title}}</option>
                @endforeach
              </select>            
            </div>
            <div class="input-field col s7">
              <input id="nurserysubject" type="text" class="validate" />
              <label for="Subject">ENTER SUBJECT</label>
            </div>
          </div>
          @csrf
        </form>
      </div>
    </div>
    <div class="modal-footer">
      <!-- <a href="#!" class="btn btn-alert" id="add_row">ADD ROW</a> -->
      {{-- <button class="btn btn-success" id="createNurSubject" style="float: left">CREATE SUBJECT</button> --}}
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close btn btn-alert">Close</a>
      <button class="btn btn-success" id="createNurSubject" style="float: left">CREATE SUBJECT</button>
    </div>
  </div>
  <!--Subject Modal Start -->


<!-- </div> -->
<script src="{{ asset('assets/js/subjectsManager.js') }}"></script>
@endsection