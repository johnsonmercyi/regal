@extends('schools.layout.schoollayout')

@section('title', 'Manage Traits')

@section('content')

<div class="section">
    <h3 class="center">Manage School Traits</h3>
    <div class="row z-depth-1 " >
        <table class="table responsive-table" >
            <tr class="white-text {{ $school->themeColor}}">
                <th>S/No.</th>
                <th>Trait Category</th>
                <th>Description</th>
                <th>Actions</th>
            <tr>
            @foreach($traitCat as $cat)
                <tr>
                    <td>{{$cat->id}}</td>
                    <td>{{$cat->name}}</td>
                    <td>{{$cat->description}}</td>
                    <td>
                        <button type="submit" id="cat{{$cat->id}}" class="btn {{ $school->themeColor }} darken-1" >Add/Remove Traits</button>
                    </td>
                </tr>
            @endforeach

        </table>
        
    </div>
</div>

<div class="section hide" id="socialSection">
    <h5 class="center">Manage Social Behaviour Traits</h5>
    <div class="row ">
        <div class="center offset-l2 col m12 l8">
            <table class="table z-depth-1" id="socialTB">
                <tr class="white-text {{ $school->themeColor}}">
                    <th>S/No.</th>
                    <th>Name</th>
                    <th>Select</th>
                </tr>
               <?php $num = 1;?>
                @foreach($socialTraits as $trait)
                    <tr  id="{{$trait->id}}">
                        <td>{{$num++}}</td>
                        <td>{{ $trait->name}}</td>
                        @if(in_array($trait->id, $existTraits))
                            <td><button class="btn btn-floating black checkbtn" data-check="true">
                                <i class="material-icons">check</i></button>
                            </td>
                        @else
                            <td><button class="btn btn-floating grey checkbtn" data-check="false">
                                <i class="material-icons">check</i></button>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </table>
            <div class="progress hide colCode">
                <div class="indeterminate colCode"></div>
            </div>
            <button type="submit" class="mt-2 btn {{ $school->themeColor}}" id="socialBtn">SUBMIT</button>
        </div>
    </div>
</div>

<div class="section hide" id="motorSection">
    <h5 class="center">Manage Motor Skills Traits</h5>
    <div class="row ">
        <div class="center offset-l2 col m12 l8">
            <table class="table z-depth-1" id="motorTB">
                <tr class="white-text {{ $school->themeColor}}">
                    <th>S/No.</th>
                    <th>Name</th>
                    <th>Select</th>
                </tr>
               <?php $num = 1;?>
                @foreach($motorTraits as $trait)
                    <tr id="{{$trait->id}}">
                        <td>{{$num++}}</td>
                        <td>{{ $trait->name}}</td>
                        @if(in_array($trait->id, $existTraits))
                            <td><button class="btn btn-floating black checkbtn" data-check="true">
                                <i class="material-icons">check</i></button>
                            </td>
                        @else
                            <td><button class="btn btn-floating grey checkbtn" data-check="false">
                                <i class="material-icons">check</i></button>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </table>
            <div class="progress hide colCode">
                <div class="indeterminate colCode"></div>
            </div>
            <button type="submit" class="mt-2 btn center {{ $school->themeColor}}" id="motorBtn">SUBMIT</button>
        </div>
    </div>
</div>

<div id="successModal" class="modal">
    <button class="modal-close waves-effect waves-light btn-flat right" id="close">
        <i class="material-icons">close</i>
    </button>

    <div class="modal-content">
        <div class="row">
            <!-- Header -->
            <div class="valign-wrapper mb-5">
                <h2 class="center"><span id="successText"></span>Submitted Successfully</h2>
            </div>
        </div>
    </div>
</div>


<!-- </div> -->

@endsection