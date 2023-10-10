@extends('schools.layout.schoollayout')

@section('title', 'Schools Trait Assessment Format')

@section('content')

@if(count($ratingFormat) < 1)
<div class="section row greyBack">
    <div class="offset-l2 col m12 l8">
    <h5 class="center">Create Traits Rating Format</h5>
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


@else

<!-- SECTION TO SHOW IF TRAITS RATINGS EXIST -->

    <div class="section row greyBack" id="traitIndexTable">
    <div class="offset-l2 center col m12 l8">
        <h5 class="center">Schools Trait Rating/Assessment Format</h5>
        <table class="display table white z-depth-1" style="border-radius: 2px;" width="100%">
            <thead class="center-align {{$school->themeColor}}">
                <tr>
                    <th>Rating Description</th>
                    <th>Rating Value</th>
                </tr>

            </thead>

            <tbody>

                @foreach ($ratingFormat as $rating)
                    <tr>
                        <td>{{ $rating->description }}</td>
                        <td>{{ $rating->rating }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button 
        style='margin-top: 8px'
        class="btn {{$school->themeColor}} darken-2" id="editTraitRating">Change Format</button>
    </div>
    </div>

    <div class="section row greyBack hide" id="traitEditDiv">
        <div class="offset-l2 center col m12 l8">
            <h5 class="">Edit Schools Trait Rating/Assessment Format</h5>
            <table class="display table white z-depth-1 centered" id="traitEditTable" style="border-radius: 2px;" width="100%">
                <thead class="center-align {{$school->themeColor}}">
                    <tr>
                        <th>Rating Description</th>
                        <th>Rating Value</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach ($ratingFormat as $rating)
                        <tr data-id="{{$rating->id}}">
                            <td><input type="text" class="desc" style='width:250px;background-color:#ddd;border-radius:5px' value="{{ $rating->description }}" /></td>
                            <td class="center"><input type="text" class="center rating" style='width:50px;background-color:#ddd;border-radius:5px' value="{{ $rating->rating }}" /></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <button class="btn {{$school->themeColor}} darken-2" id="addRatingBtn">ADD ROW</button>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <button
            style='margin-top: 8px'
             class="btn {{$school->themeColor}} darken-2" id="storeEditedTraitRating">Submit</button>
        </div>
    </div>

    @endif


@endsection

