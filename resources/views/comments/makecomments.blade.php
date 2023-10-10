@extends('schools.layout.schoollayout')

@section('title', 'Make Comments')

@section('content')

<div class="section">
<div class="row z-depth-1 borderRound" >
    <form action="" id="makeCommentClass">
            <div class="col s12 m3 input-field">
                <select id="classId" class="browser-default">
                <option value=""> Choose Class </option>
                @foreach($allClass as $classroom)
                    <option value="{{ $classroom->id }}">{{ $classroom->level.$classroom->suffix}}</option>
                @endforeach
                </select>
                <!-- <label for="classroom" >Choose Class</label> -->
            </div>

            <div class="col s12 m3 input-field">
                <select id="sessionIdSelect" class="browser-default">
                <option value=""> Choose Session </option>
                @foreach($acadSession as $acadSess)
                    <option value="{{ $acadSess->id }}">{{ $acadSess->sessionName}}</option>
                @endforeach
                </select>
                <!-- <label for="session" >Choose Session</label> -->
            </div>

            <div class="col s12 m3 input-field">
                <select id="termIdSelect" class="browser-default">
                <option value="">Choose Term </option>
                <option value="1"> First Term </option>
                <option value="2"> Second Term </option>
                <option value="3"> Third Term </option>
                <option value="4"> Annual </option>
                </select>
                <!-- <label for="term" >Choose Term</label> -->
            </div>

            <div class="col s12 m3 center input-field">
                <button type="submit" id="loadClassStudentsComments" class="btn btn-default colCode">Load</button>
            </div>
            
            
        </form>
    </div>
</div>

    <div class="progress hide colCode">
        <div class="indeterminate colCode"></div>
    </div>

<!-- <div class="section display responsive-table z-depth-1 hide" id="scoreTable"> -->
    <div class="section display z-depth-1 hide" id="tableError">
    <h3 class='center'>Unable to fetch selected class.</h3>
    </div>
    
    <table class=" display responsive-table z-depth-1 hide mb-5" id="commentsTable">
        <form method="" action="" id="">
            <thead class="white-text center-align colCode" id="commentTableHead">
                <th>S/No.</th>
                <th>Name</th>
                <th>Position</th>
                <th>Average</th>
                <th>English</th>
                <th>Maths</th>
                <th>Form Teacher's Comments</th>
                <th>Head Teacher's Comments</th>
                <th>Remark</th>
            </thead>
            <tbody class='center' id="commentTableBody">
            </tbody>
        </form>
    </table>

    <div id="commentModal" class="modal ">

        <button class="modal-close waves-effect waves-light btn-flat right" id="close">
            <i class="material-icons">close</i>
        </button>

        <div class="modal-content">
            <h5><span class="stdInfo center"></span></h5>
            <hr>
            <div id="modalProgress" class="progress hide">
                <div class="indeterminate {{$school->themeColor}}"></div>
            </div>
            <div class="row" style="text-align:center;">
                <select id="commentSelect" name="" class="browser-default">
                    <option value=""> --- SELECT A COMMENT ---</option>
                    <option>Good result.</option>
                    <option>Poor result. Work harder!</option>
                    <option>Excellent result! Keep it up.</option>
                    <option>More effort is needed.</option>
                    <option>Needs to pay more attention in class.</option>
                    <option>She is very intelligent but lacks concentration.</option>
                    <option>He is very intelligent but lacks concentration.</option>
                    <option>He lacks concentration.</option>
                    <option>She lacks concentration.</option>
                    <option>A respectful and obedient child.</option>
                    <option>He is not active in class</option>
                    <option>She is not active in class</option>
                    <option>He is a dull child.</option>
                    <option>She is very humble and polite.</option>
                    <option>He is very distractive.</option>
                    <option>She sleeps a lot in class.</option>
                    <option>He sleeps a lot in class.</option>
                    <option>She is punctual to school.</option>
                    <option>He is punctual to school.</option>
                    <option>She is not punctual to school.</option>
                    <option>He is not punctual to school.</option>
                    <option>He is always dirty and untidy.</option>
                    <option>She is always dirty and untidy.</option>
                    <option>She is always late to school.</option>
                    <option>He is not communicative in class.</option>
                    <option>She participates actively in class activities.</option>
                    <option>Always cheerful and kind.</option>
                    <option>Always absent from school.</option>
                    <option>Always mindful of his/her studies in class.</option>
                    <option>She plays a lot in class.</option>
                    <option>Very obedient reliable. </option>
                    <option>Sometimes tells lies.</option>
                    <option>She loiters during class hour.</option>
                    <option>He loiters during class hour.</option>
                    <option>She is caring and always ready to help.</option>
                    <option>She is a truant.</option>
                    <option>He is a truant.</option>
                    <option>She is troublesome.</option>
                    <option>He is troublesome.</option>
                    <option>She eats too much and sleeps in the class.</option>
                    <option>He eats too much and sleeps in the class.</option>
                    <option>She keeps a lot of friends so should be monitored. </option>
                    <option>She is smart and always ready to work.</option>
                    <option>He is smart and always ready to work.</option>
                    <option>She keeps bad company, attention should be given to her movement.</option>
                    <option>He keeps bad company, attention should be given to his movement.</option>
                    <option>She is kind-hearted and always happy.</option>
                    <option>He is kind-hearted and always happy.</option>
                    <option>She is industrious and creative.</option>
                    <option>He is industrious and creative.</option>
                    <option>She is a secret talker.</option>
                    <option>A notorious noise maker.</option>
                    <option>Always quarrelsome.</option>
                    <option>She is quiet and jovial.</option>
                    <option>He is quiet and jovial.</option>
                    <option>Always neat and smart.</option>
                    <option>Always looks unkempt.</option>
                    <option>She plays roughly and injures others.</option>
                    <option>He plays roughly and injures others.</option>
                    <option>She is very insulting and stubborn.</option>
                    <option>He is very insulting and stubborn.</option>
                    <option>Always late to school.</option>
                    <option>She is an extrovert but stingy.</option>
                    <option>She has the spirit of co-operation.</option>
                    <option>He has the spirit of co-operation.</option>
                    <option>She is a vulgar.</option>
                    <option>He is a vulgar.</option>
                    <option>Always distracts other students.</option>
                    <option>She is dull and lazy.</option>
                    <option>He is dull and lazy.</option>
                    <option>Does not participate in class activities. </option>
                    <option>Always busy with her class work.</option>
                    <option>Always busy with his class work.</option>
                    <option>She is getting fat, what and how she eats.</option>
                    <option>He is getting fat, what and how she eats.</option>
                    <option>She is selfish and greedy.</option>
                    <option>He is selfish and greedy.</option>
                    <option>She is very truthful and trustworthy.</option>
                    <option>Very gentle and humble.</option>
                    <option>She is always eager to learn.</option>
                    <option>He is always eager to learn.</option>
                    <option>She is very hardworking.</option>
                    <option>He is very hardworking.</option>
                    <option>She is a good organizer and speaker.</option>
                    <option>He is a good organizer and speaker.</option>
                </select>
                <span>Or type below.</span>
                <textarea class='materialize-textarea' style='background-color:#ddd;border-radius:5px' maxlength='200' type='text' id="commentBox"></textarea>
            </div>
            <!-- <div class="row hide center" id="passOrFail">
                <div class='col m6'><span class="passFail" style='padding-right:4px'>PASS</span><button class="btn btn-floating passBtn grey"><i class="material-icons ">check</i></button></div>
                <div class='col m6'><span class="passFail" style='padding-right:4px'>FAIL</span><button class="btn btn-floating failBtn grey"><i class="material-icons ">close</i></button></div>
            </div> -->
            <div class="hide center" id="promotion">
                <table>
                    <tr>
                        <th>PROMOTED</th>
                        <td><button class="btn btn-floating promoteBtn grey" data-promote="PROMOTED"><i class="material-icons ">check</i></button></td>
                    </tr>
                    <tr>
                        <th>NOT PROMOTED</th>
                        <td><button class="btn btn-floating notPromotedBtn grey" data-promote="NOT PROMOTED"><i class="material-icons ">check</i></button></td>
                    </tr>
                    <tr>
                        <th>PROMOTED ON TRIAL</th>
                        <td><button class="btn btn-floating trialBtn grey"data-promote="PROMOTED ON TRIAL"><i class="material-icons ">check</i></button></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" id="packcommentAssessment">SUBMIT</button>
        </div>
    </div>

<!-- </div> -->
<script type="module" src="{{ asset('assets/js/commentsManager.js')}}"></script>

@endsection