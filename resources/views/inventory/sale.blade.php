@extends('schools.layout.schoollayout')

@section('title', 'Inventory Manager')

@section('content')

    <h5 class="center {{$school->themeColor ?: 'blue'}} white-text">INVENTORY - SELL ITEM</h5>

    <section class="row z-depth-1 valign-wrapper borderRound">
        <div class="input-field col s12 m6">
            <!-- <label class="form-label">Class</label> -->
            <select id="salesPickClass" class="browser-default">
                <option value=""> Choose Class </option>
                @foreach($allClass as $classroom)
                    <option value="{{ $classroom->id }}">{{ $classroom->level.$classroom->suffix}}</option>
                @endforeach
                </select>
        </div>
        <div class="input-field col s12 m6">
            <!-- <label class="form-label">Class</label> -->
            <select id="salesPickStudent" class="browser-default">
                <option value=""> Select Student </option>
            </select>
        </div>
        
    </section>

    <div class="progress hide">
        <div class="indeterminate colCode"></div>
    </div>

<section id="invoiceSection" class="" style="border:0.08rem black dashed;">
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
            <tr class="{{$school->themeColor ?: 'blue'}} white-text" style="border:0.1rem solid #333;">
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
                    <select id="invoiceItemSelect" class="browser-default invoiceItemSelect" style="background-color:#e9e8e8">
                        <option value=""> SELECT ITEM </option>
                        @foreach($invent as $item)
                        <option data-qty="{{$item->quantity}}" data-price="{{$item->current_price}}" value="{{$item->id}}"> {{strtoupper($item->name)}} </option>
                        @endforeach
                    </select></td>
                <td class="uPrice thinrow"></td>
                <td class="qty thinrow">
                    <input type='number' style='width:50px;background-color:#e9e8e8;border-radius:5px;margin:0px'
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
            <tr class="{{$school->themeColor ?: 'blue'}} white-text" style="border:0.1rem solid #333;">
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

<script type="module" src="{{asset('assets/js/inventoryManager.js')}}"></script>
@endsection