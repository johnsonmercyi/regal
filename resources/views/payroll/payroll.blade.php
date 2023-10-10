@extends('schools.layout.schoollayout')

@section('title', 'Payroll')

@section('content')

<div class="section center">
    <h5>Create Monthly Payroll</h5>    
</div>

<section id='staffSection' class='center mb-5'>
    
<div class="section">
    <div class="row z-depth-1 borderRound center" >
        <form method="POST" action="" id="payrollCheckForm">
            
            <div class="col offset-m4 s12 m4 input-field">
                <b>Choose Month:</b>
                <input type="month" name="payrollMonth">
            </div>
            
            <div class="col s12 m4 center input-field">
                <button type="submit" id="getPayrollStaff" class="btn btn-default {{ $school->themeColor }}">Load</button>
            </div>
        </form>
    </div>
    <h5 class="center" id="subjectClassInfo"></h5>

</div>
</section>

    <div id="payrollTableDiv" class="hide">
        <div class="row greyBack">
            <button class="btn colCode" id="generalModalBtn">Add General Values</button>
        </div>
        <h5 class="center">Payroll as at <span id="reportDate"></span> </h5>

        <table class=" display responsive-table z-depth-1 mb-5" id="payrollTable">
            <form method="" action="" id="">
                <thead class="white-text center-align {{ $school->themeColor }}" >
                </thead>
                <tbody class='center' id="scoreTableBody">
                </tbody>
                <tfoot class="center">
                    <tr>
                        <td  colspan="100%" class="center">
                            <button type="submit" class="btn btn-default {{$school->themeColor}}"  id="submitPayrollBtn">Submit</button>
                        </td>
                    </tr>
                </tfoot>
            </form>
        </table>
    </div>

    
    <div id="generalValuesModal" class="modal modal-fixed-footer">

        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>

        <div class="modal-heaader">
            <h5 class="center ">GENERAL VALUES</h5>
        </div>

        <div class="modal-content">

            <div class="row">
                <!-- Header -->
                <form action="" id="generalValuesForm">
                <table>
                    <tbody></tbody>
                    <tfoot></tfoot>
                </table>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn colCode" id="applyGeneralBtn">Apply Values</button>
        </div>
    </div>


    
    <div id="selectStaff">
        @include('includes.layout.confirmModal')
    </div>

<!-- </div> -->

<script type="module" src="{{ asset('assets/js/payrollManager.js') }}" ></script>
@endsection