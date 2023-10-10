{{-- @extends('layout') --}}
@extends('schools.layout.schoollayout')

@section('title', 'Subject Order')

@section('content')

    <!-- <div class="container"> -->

    <div class="row center">
        <h5 class="col l12">Set Subject Order For Results</h5>
    </div>

        <table  class="display white z-depth-1" id="orderTable" style="border-radius: 2px;" width="100%">
            <thead class="center-align">
                <tr>
                    <th class="center">Subject</th>
                    <th class="center">Order</th>
                </tr>

            </thead>

            <tbody>

                @foreach ($subjects as $sub)
                    <tr>
                        <td class="subjectCell" data-id="{{$sub->id}}">{{ $sub->title }}</td>
                        <td><input type='number' class="center numInput orderValue" value="{{$sub->result_order}}"/></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="center">
                <tr>
                    <td>
                        <div class="progress hide">
                            <div class="indeterminate {{$school->themeColor}}"></div>
                        </div>
                    </td>
                    <td><button type="submit" class="btn btn-default colCode darken-2"  id="submitSubjectOrder">SUBMIT</button></td>
                </tr>
            </tfoot>
        </table>
    </section>


    

    @include('includes.layout.confirmModal')


    <!-- </div> -->
<script src="{{asset('assets/js/subjectsManager.js')}}"></script>
@endsection

@section('dialog')
    @include('includes.guardians.showGuardian')
@endsection 

