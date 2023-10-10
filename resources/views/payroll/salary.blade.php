@extends('schools.layout.schoollayout')

@section('title', 'Staff Salary')

@section('content')

<div class="section center">
    <h5>Manage Staff Salary</h5>
    
</div>

<section id='staffSection' class='center mb-5'>
    <div class="section display z-depth-1 hide" id="tableError">
    </div>
    
    <h5 id='classInfo'></h5>
    
    <div class='row greyBack'>
        <div class='col offset-l1 l10 s12'>
            <table class=" display centered z-depth-1 mb-5 mt-5" id="salaryTable">
                    <thead class="white-text colCode" id="">
                        <th>S/No.</th>
                        <th>Reg. No.</th>
                        <th>Staff Name</th>
                        <th>Basic Salary (<span class="naira"></span>)</th>
                    </thead>
                    <!-- <button class='btn btn-floating' ><i class='material-icons'>check</i></button> -->
                    <tbody class='center' id="">
                        @if(count($staffSal) > 0)
                            <?php $num=1; ?>
                            @foreach($staffSal as $staff)
                                <tr class="staff{{$staff->id}}" id="{{$staff->id}}">
                                    <td>{{$num++}}</td>
                                    <td>{{$staff->regNo}}</td>
                                    <td>{{$staff->lastName.' '.$staff->firstName.' '.$staff->otherNames}}</td>
                                    <td>
                                        <input type='number' style='background-color:#ddd;border-radius:5px'
                                            class='center salary' name='' value="{{$staff->basic_salary}}">
                                    </td>
                                </tr>
                            @endforeach
                        @else
                        <?php $num=1; ?>
                            @foreach($staff as $staff)
                                <tr class="staff{{$staff->id}}" id="{{$staff->id}}">
                                    <td>{{$num++}}</td>
                                    <td>{{$staff->regNo}}</td>
                                    <td>{{$staff->lastName.' '.$staff->firstName}}</td>
                                    <td>
                                        <input type='number' style='background-color:#ddd;border-radius:5px'
                                            class='center salary' name=''>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="center" colspan="100%"><button id="submitSalary" class="btn colCode">Submit Salary Data</button></td>
                        </tr>
                    </tfoot>
            </table>
            
        </div>
    </div>
</section>


    
    <div id="selectStaff">
        @include('includes.layout.confirmModal')
    </div>

<!-- </div> -->

<script type="module" src="{{ asset('assets/js/payrollManager.js') }}" ></script>
@endsection