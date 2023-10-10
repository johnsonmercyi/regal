@extends('schools.layout.schoollayout')

@section('title', 'Promotion')

@section('content')

@if($checkPromotion)
<div class="section center">
    <div class="row z-depth-1 borderRound" >

            <h5> Promotion is no longer available for this session! </h5>          
            
    </div>
</div>

@else

<div class="section center">
    <div class="row z-depth-1 borderRound" >

        @if($currentSession && $nextSession)
            <h5>Promote Students from {{$currentSession->sessionName}} to {{$nextSession->sessionName}} </h5>        
        @endif

    </div>
</div>

<!-- <div class="section display responsive-table z-depth-1 hide" id="scoreTable"> -->
<section id='promotionSection' class='center mb-5'>   

    <table class="display centered" id="promotionTable">
        <thead class="white-text colCode">
            <th>Current Class</th>
            <th>New Class</th>
            <th></th>
        </thead>

        <tbody>
            @foreach($promotableClass as $classroom)
                <tr data-classid="{{ $classroom->id }}">
                    <td>{{ $classroom->level.' '.$classroom->suffix }}</td>
                    <td >
                        <select class="browser-default" >
                            <option value="">CHOOSE NEW CLASS</option>
                            @foreach($allClass as $cls)

                                @if($classroom->level == 'JSS3' && $cls->level == 'SS1')
                                    <option  value="{{$cls->id}}">{{$cls->level.' '.$cls->suffix}}</option>
                                @else
                                    @if(substr($classroom->level, 0, 1) == substr($cls->level, 0, 1))
                                        @if( ( (int) substr($classroom->level, strlen($classroom->level)-1) + 1 ) == (int) substr($cls->level, strlen($cls->level)-1) )
                                            <option  value="{{$cls->id}}">{{$cls->level.' '.$cls->suffix}}</option>
                                        @endif
                                    @endif
                                @endif

                            @endforeach
                        </select>                        
                    </td>
                    <td>
                        <button class="btn chooseStudentBtn colCode" data-classid="{{ $classroom->id }}" >Choose Students</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>
    
    <div class='row greyBack padBott'>
        <div class='col m12 center' >
            <button class="btn colCode" id="promoteBtn">PROMOTE</button>
        </div>
        <input type="hidden" value="{{ $students }}" id="studentsInfo" />
    </div>
    
@endif

       
<div id="chooseStudentsModal" class="modal ">

    <button class="modal-close waves-effect waves-light btn-flat right" id="close">
        <i class="material-icons">close</i>
    </button>

    <div class="modal-heaader">
        <h5 class="center " id="studentName"></h5>
    </div>

    <div class="modal-content">

        <table id="chooseStudentsTable">
            <thead>
                <th>S/No.</th>
                <th>Name</th>
                <th>New Class</th>
            </thead>
            <tbody>

            </tbody>
        </table>
        
    </div>

    <div class="modal-footer">
        <button class="btn colCode" id="saveChosenStudentsBtn">Save</button>
    </div>

</div>


    
    <div id="selectStudents">
        @include('includes.layout.confirmModal')
    </div>

<!-- </div> -->

<script src="{{ asset('assets/js/promotionManager.js') }}" ></script>
@endsection