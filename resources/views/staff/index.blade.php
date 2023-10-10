{{-- @extends('layout') --}}
@extends('schools.layout.schoollayout')

@section('title', 'Staff List')

@section('content')

    <div class="datatableContainer">

        <div>
            <h5>Staff List</h5>

        <table class="display responsive-table white z-depth-1" id="staffData" style="border-radius: 2px;" width="100%">
            <thead class="center-align">
                <tr>
                    <th>S/No.</th>
                    <th>Photo</th>
                    <th>Reg. No.</th>
                    <th>Staff Name</th>
                    <th>Gender</th>
                    <th>Phone Number</th>
                    <th>Staff Address</th>
                    <th>Action</th>
                </tr>

            </thead>

            <tbody>
                <?php $num=1; ?>
                @foreach ($allStaff as $staff)
                    <tr>
                        <td>{{ $num++ }}</td>
                        <td><img class="circle" style="border:2px solid black;height:60px;width:60px" src="/storage/images/{{$school->prefix}}/passports/{{ $staff->imgFile ?: 'no_image.jpg' }}" /></td>
                        <td>{{ $staff->regNo }}</td>
                        <td><a href="/{{ $school->id }}/staff/{{ $staff->id }}/details">{{ strtoupper($staff->firstName.' '.$staff->otherNames.' '.$staff->lastName) }}</a></td>
                        <td>{{ strtoupper($staff->gender) }}</td>
                        <td>{{ strtoupper($staff->phoneNo) }}</td>
                        <td>{{ strtoupper($staff->homeAddress) }}</td>
                        <td class="center-align">
                             {{-- <a href="javacript:void(0)" data-target="showStaffsModal" class="btn btn-floating tooltipped waves-effect waves-light colCode lighten-1 col s6 modal-trigger viewStaffs" id="{{ 'viewStaffs'. $Staff->id }}" data-id="{{ $Staff->id }}"> --}}
                             <a href="/{{ $school->id }}/staff/{{ $staff->id }}/details" data-target="showStaffsModal" class="btn btn-floating colCode waves-effect waves-light colCode lighten-1 col s6 modal-trigger viewStaffs" id="{{ 'viewStaffs'. $staff->id }}" title="View Staff">
                                <i class="material-icons">pageview</i>
                            </a>
                            <a href="/{{ $school->id }}/staff/{{ $staff->id }}/edit" data-id="{{ $staff->id }}" class="btn btn-floating colCode waves-effect waves-light colCode lighten-1 col s6 editStaff" title="Edit Staff">
                                <i class="material-icons">edit</i>
                            </a>
                            {{--<a href="javacript:void(0)" class="btn btn-floating tooltipped colCode waves-effect waves-light red lighten-1 col s6 modal-trigger deleteStaff" data-id="{{ $staff->id }}" id="{{ 'deleteStaff'. $staff->id }}" data-target="showStaffModal">
                                <i class="material-icons">delete</i>
                            </a>--}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection

@section('dialog')
    @include('includes.guardians.showGuardian')
@endsection 

