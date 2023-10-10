@extends('schools.layout.schoollayout')

@section('title', 'Inventory Manager')

@section('content')

    
    <h5 class="center {{$school->themeColor ?: 'blue'}} white-text">INVENTORY - MANAGE CATEGORIES</h5>

<section class="section center z-depth-1 borderRound" id="catTable">
    <h5>CATEGORY LIST</h5>
    <button class="btn colCode" id="showCatForm">CREATE NEW CATEGORY</button>
    <div class="row center">
        <div class="col offset-m2 m8 s12">
            @if(count($existCat) > 0)
                <table>
                    <tr>
                        <th>S/No.</th>
                        <th>Category Name</th>
                        <th>Category Description</th>
                        <th>Category Prefix</th>
                    </tr>
                    <?php $sn = 1; ?>
                @foreach($existCat as $cat)
                    <tr>
                        <td>{{$sn++}}</td>
                        <td>{{$cat->name}}</td>
                        <td>{{$cat->description}}</td>
                        <td>{{$cat->prefix}}</td>
                    </tr>
                @endforeach
                </table>
            @else
                <h6>No Existing Category!</h6>
            @endif
        </div>
    </div>
</section>

<section class="section hide center" id="catForm">
    <h5>CREATE CATEGORY</h5>
    <form action="" method="">

        <div class="row  d-flex justify-content-center">
            <div class="input-field col s12 m6" >
                <label class="form-label">Category Name</label>
                <input type="text" id="catName" class="form-control inform ">
            </div>
            <div class="input-field col s12 m6" >
                <label class="form-label">Category Description</label>
                <input type="text" id="catDesc" class="form-control inform ">
            </div>
        </div>

        <div class="row  d-flex justify-content-center">
            <div class="input-field col s12 m6">
                <label class="form-label">Category Prefix</label>
                <input type="text" id="catPrefix" class="form-control inform">
            </div>
        </div>

        <div class="row">
            <div class="col s12 center"><button class="btn colCode" id="createCatBtn">SUBMIT</button></div>
        </div>

        <div class="progress hide">
            <div class="indeterminate colCode"></div>
        </div>

    </form>
</section>


    <div id="catModal" class="modal">
        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>

        <div class="modal-content">
            <h4 class="green-text">Category Created Successfully!</h4>
                
            <hr>
        </div>
    </div>

<script type="module" src="{{asset('assets/js/inventoryManager.js')}}"></script>

@endsection