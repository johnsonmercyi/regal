import AJAX_OPERATION, { initMaterializeComponents, OPERATION_TYPES, AJAX_REQUEST_METHODS } from '../util/utility.js';

$(document).ready(function() {

initMaterializeComponents();

const scoreformObj = {};
const allScores = new Array();
const schoolColor = document.querySelector("#schoolColor") ? document.querySelector("#schoolColor").value : '';
let backBtn = document.querySelector('#backSelectBtn');
let stdDetails = [];
let emptyStudentsModal = document.querySelector('#emptyStudentsModal');
const loadNurserySubjectStudents = document.querySelector('#loadNurserySubjectStudents');


//button click action for fetching students list for selected variables
$('#loadSubjectStudents').on('click', function(e){
    e.preventDefault();
    // allScores.length = 0;
    // GET THE VARIABLES TO SELECT A CLASS FOR SCORE ENTRY
    const form = $('#enterScores');    
    const form_data = form.serializeArray();
    $.each(form_data, function(i, field){                       
        scoreformObj[field.name] = field.value;
    });
    
    // CHECK THE SELECTED VARIABLES (CLASS, SESSION, TERM, SUBJECT) AND THROW ERROR
    let check = true;
    // $.each(scoreformObj, function(i, field){
    //     if(field === ""){
    //         alert(`Please select a ${(i.substring(0, i.length-2)).toUpperCase()}!`);
    //         check = false;
    //         return;        
    //     } 
    //     // $("#scoreTable").removeClass("hide");
    // });
    for(const [key, value] of Object.entries(scoreformObj)){
        if(value == ''){
            alert(`Please Select a ${key.substring(0, key.length-2).toUpperCase()}`);
            check = false;
            break;
        }
    }


    if(check){
        $('.progress').removeClass('hide');
        $("#scoreTable").addClass("hide");
        getSubjectStudents('/result/students/'+scoreformObj.classId , scoreformObj);
        $('#submitScores').attr("disabled", false);
        $('#submitScores').removeClass("hide");
    }

});

loadNurserySubjectStudents && loadNurserySubjectStudents.addEventListener('click', (e) => {
    e.preventDefault();
    // allScores.length = 0;
    // GET THE VARIABLES TO SELECT A CLASS FOR SCORE ENTRY
    const form = $('#enterNurseryScores');    
    const form_data = form.serializeArray();
    $.each(form_data, function(i, field){                       
        scoreformObj[field.name] = field.value;
    });
    
    // CHECK THE SELECTED VARIABLES (CLASS, SESSION, TERM, SUBJECT) AND THROW ERROR
    let check = true;
    // $.each(scoreformObj, function(i, field){
    //     if(field === ""){
    //         alert(`Please select a ${(i.substring(0, i.length-2)).toUpperCase()}!`);
    //         check = false;
    //         return;        
    //     } 
    //     // $("#scoreTable").removeClass("hide");
    // });
    for(const [key, value] of Object.entries(scoreformObj)){
        if(value == ''){
            alert(`Please Select a ${key.substring(0, key.length-2).toUpperCase()}`);
            check = false;
            break;
        }
    }


    if(check){
        $('.progress').removeClass('hide');
        $("#scoreTable").addClass("hide");
        getSubjectStudents('/result/nursery_students/'+scoreformObj.classId , scoreformObj);
        $('#submitScores').attr("disabled", false);
        $('#submitScores').removeClass("hide");
    }
});


/***************END OF BUTTON FOR STUDENT LOAD**************** */


    // Activate submit and cancel buttons for empty modal
    let emptySubmitBtn = document.querySelector('#emptySubmitBtn')
    let emptyCancelBtn = document.querySelector('#emptyCancelBtn')
    emptySubmitBtn && emptySubmitBtn.addEventListener('click', (e) => {
        e.preventDefault()
        submitFinalScores(packScoresFxn()[0])
        M.Modal.getInstance(emptyStudentsModal).close()
    })
    emptyCancelBtn && emptyCancelBtn.addEventListener('click', (e) => {
        e.preventDefault()
        M.Modal.getInstance(emptyStudentsModal).close()
    })

//button action for sending the students score records to the database
$('#submitScores').on('click', function(e){
    e.preventDefault();
    
    let [submitScores, checkOver, emptyStudents] = packScoresFxn()

    let hasEmpty = checkEmptyScores(emptyStudents)

    console.log(submitScores);
        
    if(!checkOver || hasEmpty){
        return false;
    } else {
        $(this).attr('disabled', true);
        $('#scoreTable .progress').removeClass('hide');
        submitFinalScores(submitScores)
    }
});
/***********END OF BUTTON FOR SUBMITTING ALL STUDENT SCORES********** */

/****FXN TO PACK SCORES TO ARRAY**** */
function packScoresFxn(){
    let submitScores = [...allScores];
    let checkOver = true, emptyStudents = [];

    submitScores.forEach((score, i)=>{
        if(score.TOTAL > 100){
            alert(`TOTAL CANNOT EXCEED 100%! AT STUDENT NO. ${i+1}`);
            checkOver = false;
            return false;
        }
        
        // CHECK FOR EMPTY SCORES
        if(score.TOTAL <= 0){
            let allEmpty = true;
            for (const [k, v] of Object.entries(score)) {
                if(k.substring(0, 2) == 'CA' && v == '0') {
                    allEmpty = false;
                }
            }
            if(allEmpty) {
                emptyStudents.push(score.student_id);
            }
        }
    });

    submitScores = submitScores.filter(sco => !emptyStudents.includes(sco.student_id) )

    return [submitScores, checkOver, emptyStudents];
}

function submitFinalScores(submitScores){ 
    if(submitScores.length == 0){
        alert('Cannot submit empty scores!');
        return false;
    }else {
        let progModal = document.querySelector('#progressModal');
        progModal.querySelector('.progress').classList.remove('hide');
        M.Modal.getInstance(progModal).open()
        AJAX_OPERATION('/scores/network/check', AJAX_REQUEST_METHODS.GET, {},
            function (response, status, xhr) {
                if(response.success){
                    M.Modal.getInstance(progModal).close()
                    console.log(submitScores, submitScores.length);
                    insertScores(`/${submitScores[0].school_id}/result`, submitScores);
                }
            },
            function(response, status, xhr){
                M.Modal.getInstance(progModal).close()
                $('#submitScores').attr('disabled', false);
                $('#scoreTable .progress').addClass('hide');
                alert('Network Error: Unable to connect to server. \n Check your network and try again.');
            }
        );
    }
}

/****** SHOW MODAL FOR STUDENTS WITH EMPTY SCORES **/
function checkEmptyScores(emptyStudents){
    if(emptyStudents.length > 0){
        // console.log(emptyStudents)
        let emptyList = '<ol>'
        stdDetails.filter(std => emptyStudents.includes((std.id).toString())).forEach(std => {
            emptyList += `<li>${std.lastName+' '+std.firstName+' '+std.otherName}</li>`;
        });
        emptyList += '</ol';

        emptyStudentsModal.querySelector('.modal-content #emptyStudentsList').innerHTML = emptyList;
        M.Modal.getInstance(emptyStudentsModal).open()
        return true;
    }
    return false;
}

/***********FUNCTion to update a student's scores****************/
function updateStudentScore(){
    $(this).on('click', function(e){
        e.preventDefault();
        let id = $(this).attr('data-id');
        // for(const stdObj of allScores){
        //     if(stdObj.student_id === id){
        //         stdScore.push(stdObj);
        //     }
        // }
        // modified 20/1/2020

        const stdScore = allScores.filter(sco=> sco.student_id === id);

        if(stdScore[0].TOTAL > 100){
            totalOverflow($(this));
            return false;
        } else {
            $(this).parents('tr').find('.totalScore').removeClass('red');
            $(this).parents('tr').find('.totalScore').removeClass('white-text');
        }
        $(this).parents('tr').find('.totalScore').text('Wait...');
        console.log(stdScore)
        updateScoreAjax(`/${stdScore[0].school_id}/result`, stdScore);
    });
}
/*****************END OF UPDATE STUDENT SCORE FUNCTION************ */

/***********************FUNCTION TO HANDLE SCORE ENTRY FROM STAFF PROFILE************************ */
let classBtn = document.querySelectorAll('.staffScorePicker');
classBtn && classBtn.forEach(btn => btn.addEventListener('click', function(e){
    e.preventDefault();
    // allScores.length = 0;

    let classObj = {
        sessionId: document.querySelector('#sessionId').value,
        termId: document.querySelector('#termId').value,
        subjectId: btn.getAttribute('data-subject'),
        classId: btn.getAttribute('data-class')       
    } 
    scoreformObj.classId = classObj.classId;
    scoreformObj.termId = classObj.termId;
    scoreformObj.sessionId = classObj.sessionId;
    scoreformObj.subjectId = classObj.subjectId;
//    console.log(classObj);
   document.querySelector('#scoresSection').classList.add('hide');
   $('.progress').removeClass('hide');
   getSubjectStudents('/result/students/'+classObj.classId , classObj);

}))

backBtn && backBtn.addEventListener('click', function(e){
    e.preventDefault()
    $("#scoreTable").addClass("hide");
    backBtn.classList.add('hide');
   document.querySelector('#scoresSection').classList.remove('hide');

})
/************************************************************* */

/**********************AJAX operation for fetching students from the database*****/
const getSubjectStudents =  (actionURL, scoreformObj)=>{
    AJAX_OPERATION(actionURL, AJAX_REQUEST_METHODS.POST, JSON.stringify(scoreformObj),
    function (response, status, xhr) {
        // Initialize show Dialog Components and Display
        // showGuardianDetailsAfterOperation(response, operationType);
        $('.progress').addClass('hide');
        $("#tableError").addClass("hide");
        $('#subjectError').addClass('hide');
        allScores.length = 0;

        fillTable(response.students, response.assessFormat, response.oldScores);
        stdDetails = [...response.students];
        const tblrows = $('#scoreTable tbody tr');
        tblrows.each(calcScore);
        //find update buttons and set their function
        const upBtn = $('#scoreTable tbody tr button');
        upBtn.each(updateStudentScore);
        // console.log(allScores);
        let sInfo = {
            selClass: document.querySelector('#selectedClass') ? document.querySelector('#selectedClass').selectedOptions[0].innerHTML : document.querySelector('#classTitle'),
            selSession: document.querySelector('#selectedSession') ? document.querySelector('#selectedSession').selectedOptions[0].innerHTML : document.querySelector('#currentSession'),
            selTerm: document.querySelector('#selectedTerm') ? document.querySelector('#selectedTerm').selectedOptions[0].innerHTML : document.querySelector('#currentTerm'),
            selSubject: document.querySelector('#selectedSubject') ? document.querySelector('#selectedSubject').selectedOptions[0].innerHTML : document.querySelector('#subjectName'),
        }
        backBtn && backBtn.classList.remove('hide');
        $('#subjectClassInfo').html(`${sInfo.selSubject} for ${sInfo.selClass} ${sInfo.selTerm}, ${sInfo.selSession}`)
        
    }, function (xhr, status, error) {
        $('.progress').addClass('hide');
        console.log("Server Error Response:\n" + error);
        /**HANDLE ERROR HERE... */
        $("#tableError").removeClass("hide");
    }
    );
}
/**********END OF AJAX FUNCTION FOR LOADING SUBJECT STUDENTS****************** */

/**********AJAX FUNCTION FOR INSERTING SCORES INTO DB ************************* */
const insertScores =  (actionURL, scoreObj)=>{
    AJAX_OPERATION(actionURL, AJAX_REQUEST_METHODS.PUT, JSON.stringify(scoreObj),
    function (response, status, xhr) {
        // Initialize show Dialog Components and Display
        // $('#successModal').modal('show');
        // $('#successModal').removeClass('hide');
            
        // $('#submitScores').attr("disabled", true);
        if(response.exists){
            M.toast({html:"<h5>This Subject's Scores Already Exists! Please Reload!</h5>", classes:"red"}, 4000);
            return false;
        }
        if(response.insertScores){
            $('#submitScores').addClass("hide");
            $('#scoreTable .progress').addClass('hide');
            $('#updateAct').removeClass("hide");
            $('#successModal').modal('open');
            const updateTableRows = $('#scoreTable tbody tr');
            updateTableRows.each(function(){
                if(!$(this).has('button').length){
                    $(this).append(`
                    <td><button class="btn btn-default ${schoolColor} lighten-1 updateButt"
                    data-id=${$(this).attr('id')} type="submit">Update</button></td>`);  
                }
            });
            const updateBtn = $('#scoreTable tbody tr button');
            updateBtn.each(updateStudentScore);
            $('#successModal #close').on('click', function(e){
                $('#successModal').modal('close');
            });
        }
        
    }, function (xhr, status, error) {
        console.log("Server Error Response:\n" + error);
        M.toast({html:"<h5>Unable to insert scores! Contact Admin</h5>", classes:"red"}, 4000)
        /**HANDLE ERROR HERE... */
    }
    );
}
/**********END OF AJAX FUNCTION FOR INSERTING SCORES INTO DB ********************* */

//update student score
const updateScoreAjax = (actionURL, scoreJson)=>{
    return $.ajax({
            type:'PATCH', 
            url:actionURL, 
            dataType:'json', 
            data:{data:scoreJson}, 
            beforeSend: function (xhr, type) {
                if (!type.crossDomain) {
                    xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
                }
            },
            // error:function(e){$("#answe").append('error' + e.responseText + e.status);},
            success: function(response){
                let sumScore = 0;
                // let sumScore = Object.values(response.res.scores).reduce((sum, val)=> parseInt(sum) + !isNaN(parseInt(val)) ? parseInt(val) : 0 );
                // for(const value of Object.values(response.res.scores)){
                //     sumScore += !isNaN(parseInt(value)) ? parseInt(value) : 0;
                // }
                $('#'+ response.res.id).find('.totalScore').text(response.res.scores.TOTAL);
                // console.log(response.res.scores)
            }
        });
}
/**********END OF AJAX FUNCTION FOR UPDATING SCORES********************* */


//add scores to table
const fillScores = (assessFormat, oldScores, id)=>{
    const assessTD = new Array();
    let totalScore = 0;
    let updateBtn = ``;
    if(oldScores.length !== 0){
        $('#submitScores').attr("disabled", true);
        $('#submitScores').addClass("hide");
        // $('#updateAct').removeClass("hide");
        updateBtn += `<td><button 
            class="btn btn-default ${schoolColor} lighten-1 updateButt" 
            data-id=${id} type="submit">Update</button></td>`;
        const currentStudent = oldScores.filter(score => score.student_id === id);
        // const newAssessTD = assessFormat.foreach(assess => {
        if(currentStudent[0]){
            for(const assess of assessFormat){
                for(const[key, value] of Object.entries(currentStudent[0])){
                    if(assess.formatType === key){
                        totalScore += !isNaN(parseFloat(value)) ? parseFloat(value) : 0;
                        // value =${value ? value : ''} check out value
                        assessTD.push( `
                            <td style='height:51px;'>
                            <input type='number' style='width:50px;background-color:#ddd;border-radius:5px'
                            class='${assess.formatType} center' data-max=${assess.percentage}
                            value ='${!isNaN(parseFloat(value)) ? parseFloat(value) : ''}' 
                            name=${assess.formatType}></td>`
                        );
                    }
                }
            }
        } else {
            for(const assess of assessFormat){
                assessTD.push( `
                            <td style='height:51px;'>
                            <input type='number' style='width:50px;background-color:#ddd;border-radius:5px'
                            class='${assess.formatType} center' data-max=${assess.percentage}
                            name=${assess.formatType}></td>`
                        );
            }
        }
    } 
    else 
    {
        $('#submitScores').attr("disabled", false);
        $('#submitScores').removeClass("hide");

        assessFormat.forEach( assess => assessTD.push(`
            <td style='height:51px;'>
            <input type='number' style='width:50px;background-color:#ddd;border-radius:5px'
            class='${assess.formatType} center' data-max=${assess.percentage}
            name=${assess.formatType}></td>`)
        );
    }
    return [assessTD.toString(), totalScore, updateBtn];
}
/************* END FUNCTION FOR PRODUCING THE SCORE ROWS TO ADD TO  ENTRY TABLE**************************** */


/****************add students to table **************** *///
const fillTable = (stdData, assessFormat, oldScores)=>{
    if(stdData == false){
        $('#subjectError').removeClass('hide');
        return;
    }
    const assessHead = assessFormat.map( assess => `<th style='width:100px;'>${assess.name}<br>(${assess.percentage})</th>`);
    // const fillScoresVal = fillScores(assessFormat, oldScores, student.id);
    const tableHead = `<th>S/No.</th><th>Student Name</th>${assessHead.toString()}<th>Total</th><th class="hide" id="updateAct">Action</th>`;
    let serialNum = 1;
    const studentRows = stdData.map( student => {
            const stdInfo = fillScores(assessFormat, oldScores, student.id);
            return (`
            <tr id=${student.id}><td>${serialNum++}</td>
            <td>${(student.lastName+' '+student.firstName+' '+student.otherName).toUpperCase()}</td>
            ${stdInfo[0]}
            <td style='width:100px;' class='totalScore'>${stdInfo[1]}</td>
            ${stdInfo[2]}
            </tr>`);
        }
        ).toString();

    $("#scoreTableHead").html(tableHead);
    if(oldScores.length !== 0){
        $('#updateAct').removeClass("hide");
    }
    $("#scoreTableBody").html(studentRows);
    $("#scoreTable").removeClass("hide");
    $("td").addClass("center");
    $("th").addClass("center");
    console.log(allScores);
}
/***********END OF TABLE CONSTRUCTION FUNCTION************************* */

/***********FUNCTION FOR CALCULATING THE SCORES FOR EACH ROW******************** */
function calcScore(){
    $(this).on('focusin', function(){ $(this).addClass('green lighten-3')});
    $(this).on('focusout', function(){ $(this).removeClass('green lighten-3');});

    const studentScore = {};
    // for(const[key, value] of Object.entries(scoreformObj)){
    //     studentScore[key] = value;
    // }
    
    studentScore.class_id = scoreformObj.classId;
    studentScore.academic_session_id = scoreformObj.sessionId;
    studentScore.term_id = scoreformObj.termId;
    studentScore.subject_id = scoreformObj.subjectId;
    studentScore.student_id = $(this).attr('id');
    studentScore.school_id = $('#schoolId').val();

    const inputScores = $(this).find("input");
    const scoreArray = $(this).find("input").toArray();

    scoreArray.forEach(score=> studentScore[score.name] = score.value ? score.value : '-');
    //below line modified 29/9/2019
    studentScore['TOTAL'] = $(this).find(".totalScore").text();
    
    inputScores.each(function(){
        let totalScore = 0;
        $(this).on('focusin', function(e){
            e.preventDefault();
            return false;
        })
        $(this).on('focusout', function(e){
            e.preventDefault();
            catchOverflow($(this).val(), $(this).attr("data-max"), $(this));
            const calcValue = calcRow(scoreArray);
            totalScore = calcValue[0];
            for(const[key, value] of Object.entries(calcValue[1])){
                studentScore[key] = value;
            }
            studentScore.TOTAL = totalScore;
            // scoreArray.forEach( score => {
            //     if(!isNaN(parseInt(score.value))){
            //         studentScore[score.name] = parseInt(score.value);
            //         totalScore += parseInt(score.value);
            //     } else {
            //         studentScore[score.name] = 0;                    
            //     }
            // });
            
            if(!$(this).parents('tr').has('button').length && totalScore <= 100){
                $(this).parents('tr').find(".totalScore").text(totalScore);
                $(this).parents('tr').find('.totalScore').removeClass('red');
                $(this).parents('tr').find('.totalScore').removeClass('white-text');
            } else if(totalScore > 100){
                totalOverflow($(this));
            } else {
                $(this).parents('tr').find(".totalScore").text('');                
                $(this).parents('tr').find('.totalScore').removeClass('red');
            }
            totalScore = 0;
        });
    });
    
    allScores.push(studentScore);
}
/**********eND OF ROW CALC FXN**************************** */

//calculate the total input scores for each row
function calcRow (scoreArray){
    let totScore = 0;
    const studentScore = {};
    scoreArray.forEach( score => {
     if(!isNaN(parseFloat(score.value))){
         studentScore[score.name] = parseFloat(score.value);
         totScore += parseFloat(score.value);
        }
      else{
        studentScore[score.name] = '-';
      }
    });
    return [totScore, studentScore];
}
/*********END OF FUNCTION TO CALCULATE ROW SCORES*************************** */

function catchOverflow(inputVal, maxVal, inputSelf){
    if(+inputVal > +maxVal && inputSelf.attr('name') != 'EXAM'){
        inputSelf.val('');
        inputSelf.addClass('red lighten-3');
        alert("Overflow");
        // inputSelf.attr('style', function(){ return 'background-color:red'});
    }
    else if(+inputVal > +maxVal && inputSelf.attr('name') == 'EXAM'){
        alert("Overflow");
    }
    else {
        inputSelf.removeClass('red');
    }
}

function totalOverflow(elem){
    alert('TOTAL CANNOT EXCEED 100%!');
    elem.parents('tr').find('.EXAM').val('');
    elem.parents('tr').find('.totalScore').text('Total Error');
    elem.parents('tr').find('.totalScore').addClass('red');
    elem.parents('tr').find('.totalScore').addClass('white-text');
}

function clearArray(arra){
    for(let i=arra.length; i >= 0; i--){
        arra.pop();
    }
}

const checkThis = ()=>console.log($(this));

// $(this).parent.find(".totalScore").text(totalScore);

});