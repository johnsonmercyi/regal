{{-- @extends('layout') --}}
@extends('schools.layout.schoollayout')

@section('title', 'Grading Format')

@section('content')

    <div class="container">

        <div class="center">
            <h4>Incomplete</h4>
            <h4>Edit Grading Format</h4>
        </div>

        <!-- Add Classs Button -->
        <!-- <div class="col s12 row mt-5">
            <a href="/class/create" class="col s12 m3 l2 btn waves-effect waves-light red lighten-1 modal-trigger" data-target="addClassModal">Add Class</a>
        </div> -->

        <div class="section row">
            <div class="offset-l2 col m12 l8 center" style='margin-bottom:30px;'>

                <table class="display white centered z-depth-1" id="editGradeTable" style="margin-bottom:10px;" width="100%">
                    <thead class="center-align">
                        <tr>
                            <th class="center">Description</th>
                            <th class="center">Grade</th>                    
                            <th class="center">Min. Score</th>                    
                            <th class="center">Max. Score</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($gradeFormat as $format)
                            <tr>
                                <td><input  class="center" type='text'  style='background-color:#ddd;border-radius:5px' value="{{ $format->description }}" ></td>
                                <td><input  class="center" type='text'  style='background-color:#ddd;border-radius:5px' value="{{ $format->grade }}" ></td>
                                <td><input  type='number' class='numInput center' value="{{ $format->minScore }}" ></td>
                                <td><input  type='number' class='numInput center' value="{{ $format->maxScore }}" ></td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button class='btn colCode' id="updateGrade">Submit</button>
            </div>
        </div>
    </div>

@endsection

