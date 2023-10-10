@extends('schools.layout.schoollayout')

@section('title', 'Trait Format')

@section('content')
  

    <div class="progress hide">
        <div class="indeterminate {{$school->themeColor}}"></div>
    </div>

    <!-- <input type="hidden" id="sectionId" name="sectionId" value="2" /> -->
<!-- <div class="section display responsive-table z-depth-1 hide" id="scoreTable"> -->
<div class="section row">
    <div class="offset-l2 col m12 l8">
    <h4 class="center">Create Traits Rating Format</h4>
    <table class="display table z-depth-1  mb-5" id="traitFormatTable">
            <thead class="white-text center-align {{ $school->themeColor }}" id="assessmentFormatTableHead">
                <th >S/No.</th>
                <th >Description</th>
                <th >Rating Value</th>
            </thead>
            <tbody class='center' id="assessTableBody">
                <tr id="EXAM">
                    <td>1</td>
                    <td><input type="text" class="description" style='background-color:#ddd;border-radius:5px' value="Very Good"></td>
                    <td><input type='number' class="center rating" value='5' style='width:50px;background-color:#ddd;border-radius:5px' /></td>
                </tr>
            </tbody>
            <tfoot class="center">
                <tr>
                    <td></td>
                    <td><button type="submit" class="btn btn-default {{$school->themeColor}} darken-2"  id="submitTraitFormat">Submit</button></td>
                    <td><button type="submit" class="btn btn-default {{$school->themeColor}} darken-2 " style="height:auto" id="addTraitFormatRow">Add Rating</button></td>
                </tr>
            </tfoot>
    </table>
            <div class="progress hide colCode">
                <div class="indeterminate colCode"></div>
            </div>
    </div>
</div>

    <div id="successModal" class="modal">

        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>

        <div class="modal-content">

            <div class="row">
                <!-- Header -->
                <div class="valign-wrapper mb-5">
                <h2 class="center"><span id="successText"></span> Submitted Successfully</h2>
                </div>
            </div>
        </div>
    </div>
<!-- </div> -->

@endsection