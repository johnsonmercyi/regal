@extends('schools.layout.schoollayout')

@section('title', 'Termly Newsletter')

    @section('content')

    <div class="section">
        <div class="row z-depth-1 borderRound">
        </div>    
    </div>
    <h5 class="center">Upload Termly Newsletter</h5>    

    <div class="row z-depth-1  borderRound" style="padding:20px 0 !important">

        <form id="newsletterForm">

        <div class="row">
            <div class="col offset-m3 m6 s12">
                <h6 class="center" style="border-bottom:solid  {{ $school->themeColor ?: 'blue' }}">SELECTED CLASS(ES)</h6>
                <ul class="collection" id="assignClassList">
                    <li class="collection-item center">All Classes Selected.</li>
                </ul>
                <div class="input-field col s12 m12 center">
                    <div class="btn" style="background-color:  {{ $school->themeColor ?: 'blue' }}" id="classPick">
                        <span>Choose Class</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="file-field input-field col s12 m12">
                <div class="btn" style="background-color:  {{ $school->themeColor ?: 'blue' }}">
                    <span>Upload File</span>
                    <input type="file" accept=".pdf" name="newsletterFile" id="newsletterFile" >
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text" placeholder="Newsletter">
                </div>
            </div>
        </div>                            
            
            <!--File Uploader goes here-->
            <div class="row center input-field">
                <div class="progress hide colCode">
                    <div class="indeterminate"></div>
                </div>
                <button class="btn waves-effect waves-light"  
                    style="background-color:{{ $school->themeColor ?: 'blue' }}" type="submit" id="submitNewsletter">Submit</button>
                <!-- <input type="submit" class="btn btn-default" id="addRecordSubmit" name="actionStaff" value="SUBMIT" /> -->
            </div>
        </form>
    </div>

    <div id="newsletterModal" class="modal modal-fixed-footer">
        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>
        <div class="modal-content " class="center">
            <h4 class="green-text">Select Classes</h4><hr>
            <table id="newsletterClasses">
                <thead>
                    <th>Class</th>
                    <th>Select</th>
                </thead>
                <tbody>
                    <tr data-id="allClass">
                        <td>All Classes</td>
                        <td>
                            <button class="btn btn-floating btn-small black newsClassCheck" 
                            data-check="true" data-id="All" 
                            data-name="All Classes">
                                <i class="material-icons">check</i>
                            </button>
                        </td>
                    </tr>
                    <tr data-id="allClass">
                        <td>All Junior Classes</td>
                        <td>
                            <button class="btn btn-floating btn-small black newsClassCheck" 
                            data-check="false" data-id="JSS" 
                            data-name="All Junior Classes">
                                <i class="material-icons">check</i>
                            </button>
                        </td>
                    </tr>
                    <tr data-id="allClass">
                        <td>All Senior Classes</td>
                        <td>
                            <button class="btn btn-floating btn-small black newsClassCheck" 
                            data-check="false" data-id="SS" 
                            data-name="All Senior Classes">
                                <i class="material-icons">check</i>
                            </button>
                        </td>
                    </tr>
                    @foreach($allClass as $klass)
                        <tr data-id="{{$klass->id}}">
                            <td>{{ $klass->level.' '.$klass->suffix }}</td>
                            <td>
                                <button class="btn btn-floating btn-small black newsClassCheck" 
                                data-check="false" data-id="{{$klass->id}}" 
                                data-name="{{ $klass->level.' '.$klass->suffix }}">
                                    <i class="material-icons">check</i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button class="btn green right" id="chooseDoneBtn">DONE</button>
        </div>
    </div>
    

    <script type="module" src="{{asset('assets/js/newsletterManager.js')}}"></script>


    @endsection
    
    <!--This extends to the layout for other Js Usages-->
    
@section('dialog')
@endsection