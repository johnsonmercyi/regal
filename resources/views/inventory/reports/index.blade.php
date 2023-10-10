@extends('schools.layout.schoollayout')

@section('title', 'Inventory Manager')

@section('content')

    <h4 class="center">INVENTORY SYSTEM REPORTS</h4>
    <ul class="collection">
        <li class="collection-item"><a href="/{{$school->id}}/inventory/reports/daily">DAILY SALES REPORT</a></li>
        <li class="collection-item"><a href="/{{$school->id}}/inventory/reports/stock">INVENTORY STOCK REPORT</a></li>
        <li class="collection-item"><a href="/{{$school->id}}/inventory/reports/invoice">SALES INVOICE REPORTS</a></li>
        <li class="collection-item"><a href="#">PURCHASE REPORTS</a></li>
        <li class="collection-item"><a href="#">SALES REPORT FOR EACH ITEM</a></li>
        <li class="collection-item"><a href="#">PLACEHOLDER</a></li>
        <li class="collection-item"><a href="#">PLACEHOLDER</a></li>
        <li class="collection-item"><a href="#">PLACEHOLDER</a></li>
    </ul>
@endsection