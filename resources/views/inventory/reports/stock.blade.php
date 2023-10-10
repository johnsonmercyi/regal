@extends('schools.layout.schoollayout')

@section('title', 'Inventory Manager')

@section('content')

<section class="">
    <h5 class="center">INVENTORY STOCK REPORT</h5>
</section>


<section class="z-depth-1 borderRound" id="stockTableSection" >
    <h5 class="center borderBot" id="dateHead">Stock </h5>
    <table id="stockReportTable">
        <thead>
            <tr class="borderBot">
                <th>Item No.</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allStock as $item)
            <tr class="rowBord">
                <td>{{$item->id}}</td>
                <td>{{strtoupper($item->name)}}</td>
                <td>{{$item->quantity}}</td>
                <td>{{$item->current_price}}</td>
            </tr>
            @endforeach
        </tbody>
        
    </table>
</section>

<script type="module" src="{{asset('assets/js/inventoryManager.js')}}"></script>


@endsection