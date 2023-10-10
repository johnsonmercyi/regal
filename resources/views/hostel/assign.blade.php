<!-- VERIFY REG NO -->
<!-- CHECK STUDENT CURRENT HOSTEL -->
<!-- CHECK HOSTEL ROOM STATUS -->
<!--  -->

@extends('schools.layout.schoollayout')

@section('title', 'Hostel Manager')

@section('content')

    <h5 class="center {{$school->themeColor ?: 'blue'}} white-text">ASSIGN HOSTEL ROOM</h5>

    <section class="row z-depth-1 valign-wrapper borderRound" >
        <div class="input-field offset-m2 col s12 m6">
            <label class="form-label">VERIFY STUDENT</label>
            <input type="text" id="studRegNo" class="form-control inform ">
        </div>
        <div class="input-field col s12 m2">
            <button class="btn colCode" id="verifyBtn">VERIFY</button>
        </div>
        
    </section>

    <div class="progress hide">
        <div class="indeterminate colCode"></div>
    </div>

    <section class="hide borderRound" id="verifiedSection">
        <!-- <h6 class="center">STUDENT DETAILS</h6> -->
    <h5 class="center {{$school->themeColor ?: 'blue'}} white-text">STUDENT DETAILS</h5>
        <!-- <legend><span>STUDENT DETAILS</span></legend> -->
        <table>
            <tr><th>Student Name:</th> <td id="stdName"></td></tr>
            <tr><th>Reg No.:</th> <td id="stdReg"></td></tr>
            <tr><th>Department:</th> <td id="stdDept"></td></tr>
            <tr><th>Level:</th> <td id="stdLevel"></td></tr>        
        </table>
    </section>

<section id="invoiceSection" class="hide" style="border:0.08rem black dashed;">
    <h5 class="center bottomdash" >INVOICE</h5>
    
    <div class="row">
        <div class="col m6 bottomdash">
            <!-- <label class="form-label">Class</label> -->
            <span>Student Name: </span><span id="selectedStudentName"></span>
        </div>
        <div class="offset-m2 col m4 bottomdash">
            <!-- <label class="form-label">Class</label> -->
            <span>Reg. No: </span><span id="studentReg"></span>
        </div>    
    </div>

    <div class="row">
        <div class="col m6 bottomdash">
            <!-- <label class="form-label">Class</label> -->
            <span>Class Name: </span><span id="selectedClass"></span>
        </div>
        <div class="offset-m2 col m4 bottomdash">
            <span>Date: </span><span id="currentDate"></span>
            <!-- <label class="form-label">Class</label> -->
        </div>    
    </div>

    <table class="browser-default" id="invoiceTable">
        <thead>
            <tr class="grey white-text" style="border:0.1rem solid #333;">
                <th>Item No.</th>
                <th>Item Name</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            <tr class="bottomdash">
                <td class="itemNum thinrow"></td>
                <td class="itemName thinrow">
                    <select id="invoiceItemSelect" class="grey lighten-1 browser-default invoiceItemSelect">
                        <option value=""> SELECT ITEM </option>
                        
                    </select></td>
                <td class="uPrice thinrow"></td>
                <td class="qty thinrow">
                    <input type='number' style='width:50px;background-color:#ddd;border-radius:5px;margin:0px'
                            class='itemQuant center' >
                </td>
                <td class="thinrow">
                    <span class="itemTotal"></span>
                    <button class="btn-floating btn-small white removeItem right">
                        <i class="material-icons red-text">close</i>
                    </button>
                </td>
            </tr>
            
        </tbody>
        <tfoot>
            <tr class="grey white-text" style="border:0.1rem solid #333;">
                <td></td><th>SUM TOTAL::</th><td></td><td></td><td><span id="invoiceTotal">0.00</span></td>
            </tr>
            <tr>
                <td colspan="50%">
                    <button class="btn btn-floating right" id="invoiceAddBtn">
                        <i class="material-icons">add</i>
                    </button>
                </td>
            </tr>
        </tfoot>
    </table>
    
    <div class="row">
        <div class="progress hide">
            <div class="indeterminate colCode"></div>
        </div>
        <div class="col s12 center"><button class="btn colCode" id="invoiceSubmitBtn">SUBMIT</button></div>
    </div>
</section>

    <div id="invoiceModal" class="modal">
        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>

        <div class="modal-content center">
            <h4 class="green-text">Invoice Submitted Successfully!</h4>
                
            <hr>
            <button class="btn blue" id="printInvoiceBtn">PRINT INVOICE</button>
            <button class="btn green" id="salesDoneBtn">DONE</button>
        </div>
    </div>

<script type="module" src="{{asset('assets/js/hostelManager.js')}}"></script>
@endsection