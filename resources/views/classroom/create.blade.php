@extends('schools.layout.schoollayout')

@section('title', 'Create Class')

    @section('content')
        
            <h5 class="center">Create Class</h5>

            <div class="row z-depth-1 ">


                <form method="POST" action="" id="addForm">
                {{-- @csrf --}}
                <input type="hidden" name="schoolId" id="schoolId" value="{{ $school->id }}" />
                <input type="hidden" name="status" id="status" value="ACTIVE" />

                <div class="row">
                    <div class="input-field col s12 m6">
                        <select class="browser-default" id="classLevel">
                            <option>Choose Class Level</option>
                            <option value="PRIMARY 1">PRIMARY 1</option>
                            <option value="PRIMARY 2">PRIMARY 2</option>
                            <option value="PRIMARY 3">PRIMARY 3</option>
                            <option value="PRIMARY 4">PRIMARY 4</option>
                            <option value="PRIMARY 5">PRIMARY 5</option>
                            <option value="PRIMARY 6">PRIMARY 6</option>
                            <option value="NURSERY 1">NURSERY 1</option>
                            <option value="NURSERY 2">NURSERY 2</option>
                            <option value="NURSERY 3">NURSERY 3</option>
                            <option value="PLAY GROUP">PLAY GROUP</option>
                        </select>
                    </div>

                    <div class="input-field col s12 m6">
                        <input type="text" name="classSuffix"  id="classSuffix" class="validate">
                        <label>Class Suffix <small>(eg. A, B, Silver, Diamond)</small></label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12 m6">
                        <select class="browser-default" id="sectionSelect">
                            <option>Choose School Section</option>
                            @if(!empty($sections))
                                @foreach($sections as $sect)
                                    <option value="{{$sect->id}}">{{ $sect->sectionName }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="input-field col s12 m6 center">
                        <button class="btn waves-effect waves-light colCode" 
                            type="submit" id="classSubmit">Submit</button>
                    </div>
                </div>
                    
                </form>
            </div>

            <script type="module" src="{{ asset('assets/js/classroomManager.js')}}"></script>

    @endsection
