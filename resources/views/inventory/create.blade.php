@extends('schools.layout.schoollayout')

@section('title', 'Inventory Manager')

@section('content')


<div id="" class="section ">
    
    <h5 class="center {{$school->themeColor ?: 'blue'}} white-text" style="border-radius:5px">INVENTORY - CREATE ITEM</h5>

    <form action="" method="" id="itemCreateForm">

        <div class="row  d-flex justify-content-center">
            <div class="input-field col s12 m6" >
                <label class="form-label">Item Name</label>
                <input type="text" id="itemName" class="form-control inform ">
            </div>
            <div class="input-field col s12 m6">
                <select name="" id="itemCat" class="browser-default">
                    <option value="">SELECT ITEM CATEGORY</option>
                    @foreach($existCat as $cat)
                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row  d-flex justify-content-center">
            <div class="input-field col s12 m6" >
                <label class="form-label">Item Price</label>
                <input type="number" id="itemPrice" class="form-control inform ">
            </div>
            <div class="input-field col s12 m6">
                <label class="form-label">Quantity in Stock</label>
                <input type="number" id="itemQty" class="form-control inform">
            </div>
        </div>

        <div class="row">
            <div class="col s12 center"><button class="btn colCode" id="createItemBtn">SUBMIT</button></div>
        </div>

    </form>

    <div class="progress hide">
        <div class="indeterminate colCode"></div>
    </div>


    <div id="itemModal" class="modal">
        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>

        <div class="modal-content center">
            <h4 class="green-text">Item Created Successfully!</h4>
                
            <hr>
            <button class="btn blue" id="createAnotherItemBtn">CREATE ANOTHER ITEM</button>
            <button class="btn green" id="createItemDoneBtn">DONE</button>
        </div>
    </div>

<script type="module" src="{{asset('assets/js/inventoryManager.js')}}"></script>
@endsection