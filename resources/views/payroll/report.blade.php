@extends('schools.layout.schoollayout')

@section('title', 'Payroll Report')

@section('content')

<div class="section center">
    <h5>Monthly Payroll Reports</h5>    
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
                <button type="submit" id="getPayrollReport" class="btn btn-default {{ $school->themeColor }}">Load Report</button>
            </div>
        </form>
    </div>
</div>
</section>

    <div id="profileDiv" class="hide">
        <button class="btn btn-floating colCode right" id="printProfile" title="Print">
            <i class="material-icons">print</i>
        </button>
        <h5 style="text-align:center">Payroll Report as at <span id="reportDate"></span> </h5>
        <h5 style="text-align:center">Total Salary: <span id="totalSalarySpan"></span></h5>
        <table class=" display responsive-table z-depth-1 mb-5" id="payrollReport" style="margin-bottom: 3rem;">
            <thead class="white-text center-align {{ $school->themeColor }}" >
                <tr>
                </tr>
            </thead>
            <tbody class='center'>
            </tbody>
            <tfoot class="center">
                <tr>
                    <td  colspan="100%" class="center">
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>


    
    <div id="selectStaff">
        @include('includes.layout.confirmModal')
    </div>

<!-- </div> -->

<script type="module" src="{{ asset('assets/js/payrollManager.js') }}" ></script>
@endsection