@extends('schools.layout.schoollayout')

@section('title', 'Payroll Structure')

@section('content')

<div class="section center">
    <h5>Manage Payroll Structure</h5>    
</div>

<div class="row greyBack">
    <button class="btn white-text colCode left" id="createStructureBtn">Create New Structure</button>
</div>

<section class="section center" id="formatTablesSection">
    @if(count($allStructures) != 0)
        @foreach($allStructures as $structure)
        <div class="row center structureDiv form-section borderRound" >
            <div class="col offset-m2 m8 s12">
                @if($structure[0]->status == '0')
                    <button class="btn green activateBtn right" data-structure="{{$structure[0]->structure_id}}" >Activate</button>
                @endif
                <h6>Structure {{ $structure[0]->structure_id }}: <span class="structureStatus">{{ $structure[0]->status == '1' ? 'Active' : 'Inactive' }}</span></h6>
                <table style="border:1px solid #333;">
                    <tr style="border:1px solid #333;">
                        <th>S/No.</th>
                        <th>Field Name</th>
                        <th>Field Action</th>
                    </tr>
                    <?php $serNum = 1; ?>
                    @foreach($structure as $structureItem)
                        <tr style="border:1px solid #333;">
                            <td>{{$serNum++}}</td>
                            <td>{{$structureItem->name}}</td>
                            <td>{{$structureItem->field_action}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @endforeach
    @else
    <h5 class="red-text">No Structure Created</h5>
    @endif
</section>

    <div id="newStructureModal" class="modal modal-fixed-footer">

        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>

        <div class="modal-heaader">
            <h5 class="center ">New Payroll Structure</h5>
        </div>

        <div class="modal-content">

            <div class="row">
                <!-- Header -->
                <button class="waves-effect waves-light btn-floating right" id="addStructureField">
                    <i class="material-icons">add</i>
                </button>
                <form action="" id="newStructureForm">
                    <table id="newStructureTable">
                        <thead>
                            <tr>
                                <th>S/No.</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn yellow" id="clearStructureBtn">Clear</button>
            <button class="btn red" id="cancelStructureBtn">Cancel</button>
            <button class="btn green" id="submitStructureBtn">Submit</button>
        </div>
    </div>

<script type="module" src="{{ asset('assets/js/payrollManager.js') }}" ></script>
@endsection