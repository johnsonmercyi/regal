@extends('schools.layout.schoollayout')

@section('title', 'Remove Staff')

@section('content')

<div class="section center">
    <h5>Remove/Delete Staff </h5>
    
</div>

<section id='staffSection' class='center mb-5'>
    <div class="section display z-depth-1 hide" id="tableError">
    </div>

    <div class="row greyBack">
        <div class="col offset-m4 s12 m4 input-field">
            <button class="btn colCode" id="submitRemovedStaff" style="height:auto">Remove Selected Staff</button>
        </div>
    </div>
    
    <h5 id='classInfo'></h5>
    
    <div class='row greyBack'>
        <div class='col offset-l1 l10 s12'>
            <table class=" display centered z-depth-1 mb-5 mt-5" id="removeStaffTable">
                    <thead class="white-text colCode" id="">
                        <th>S/No.</th>
                        <th>Reg. No.</th>
                        <th>Staff Name</th>
                        <th>Select</th>
                    </thead>
                    <!-- <button class='btn btn-floating' ><i class='material-icons'>check</i></button> -->
                    <tbody class='center' id="">
                        @if(count($allStaff) > 0)
                            <?php $num=1; ?>
                            @foreach($allStaff as $staff)
                                <tr id="staff{{$staff->id}}">
                                    <td>{{$num++}}</td>
                                    <td>{{$staff->regNo}}</td>
                                    <td>{{$staff->lastName.' '.$staff->firstName}}</td>
                                    <td>
                                        <button class="btn btn-floating deleteStaff grey" data-id="{{$staff->id}}">
                                            <i class="material-icons">check</i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
            </table>
        </div>
    </div>
</section>


    
    <div id="selectStaff">
        @include('includes.layout.confirmModal')
    </div>

<!-- </div> -->

<script type="module" src="{{ asset('assets/js/staffManager.js') }}" ></script>
@endsection