import {buildResult, suffixer} from './resultsManager.js';
document.addEventListener('DOMContentLoaded', ()=>{
    let loadBtn = document.querySelector('#loadClassStudentsComments');
    let schoolColor = document.querySelector('#schoolColor').value;
    const classChoiceForm = {};
    let editFloatBtn = `<button class='btn btn-small btn-floating editComment right colCode'><i class='material-icons'>mode_edit</i></button>`;
    let commentEditObj = {};
    let editNum = 0;
    let commentSelect = document.querySelector("#commentSelect");
    let packAssessmentBtn = document.querySelector('#packcommentAssessment');
    let schoolId = document.querySelector('#schoolId').value;

/*******************BUTTON FUNCTION TO COLLECT SELECTED DATA AND EXECUTE AJAX FETCH FUNCTION********************* */
    loadBtn && loadBtn.addEventListener('click', function(e){
        e.preventDefault();
        
        classChoiceForm.classId = document.querySelector('#classId').value,
        classChoiceForm.sessionId = document.querySelector('#sessionIdSelect').value,
        classChoiceForm.termId =  document.querySelector('#termIdSelect').value,
        classChoiceForm.schoolId =  schoolId;
        
        for(const [key, value] of Object.entries(classChoiceForm)){
            if(value == ''){
                alert(`Please Select a ${key.substring(0, key.length-2).toUpperCase()}`);
                return;
            }
        }
        document.querySelector('#commentsTable').classList.add('hide');
        document.querySelector('.progress').classList.remove('hide');

        if(classChoiceForm.termId == '4'){
            AJAXJS(classChoiceForm, 'POST', `/class/result/${classChoiceForm.classId}`, fillAnnualCommentTable);
        } else {
            AJAXJS(classChoiceForm, 'POST', `/comments/class/${classChoiceForm.classId}`, fillCommentTable);
        }
    })

/****************FUNCTION TO FILL UP THE COMMENTS TABLE WITH DATA RECEIVED FROM AJAX REQUEST******************** */
    function fillCommentTable(studentsDetails){
        document.querySelector('.progress').classList.add('hide');
        let classResults = studentsDetails.scores.length > 0 ? buildResult(studentsDetails.scores, classChoiceForm.termId) : [];
        let serialNum = 1;
        let commentRow = ``;
         studentsDetails.students.forEach(student => { 
            let stdScores = studentsDetails.scores.length > 0 
                ? 
                studentsDetails.scores.filter(sco => sco.student_id == student.id && (sco.code == 'ENG' || sco.code == 'MATHS')) 
                : [];
            let stdRes = studentsDetails.scores.length > 0 ? classResults.filter(res=> res.id == student.id) : [];
            // console.log(stdScores)
            commentRow += `
            <tr id=${student.id}><td>${serialNum++}</td>
            <td class='studentName'>${(student.lastName+' '+student.firstName+' '+student.otherName).toUpperCase()}</td>
            ${commentCells(student.id, studentsDetails, stdScores, stdRes)}</tr>
        `});
        document.querySelector('#commentsTable tbody').innerHTML = commentRow;
        document.querySelector('#commentsTable').classList.remove('hide');
        editAction();
        document.querySelectorAll('.commentBtn').forEach(btn => btn.onclick = function(e){
            e.preventDefault(); 
            let stdInfo = {
                name: btn.parentElement.parentElement.querySelector('.studentName').innerHTML,
                id: btn.parentElement.parentElement.getAttribute('id'),
                commentType: btn.parentElement.getAttribute('id').substr(0, 5)
            };
            inputComment(stdInfo);
        })
        // console.log()
    }


/***************** FUNCTION TO FILL THE ANNUAL COMMENTS TABLE ******************** */
    function fillAnnualCommentTable(studentsDetails) {
        document.querySelector('.progress').classList.add('hide');
        let classResults = studentsDetails.resultRes.length > 0 ? buildResult(studentsDetails.resultRes, classChoiceForm.termId) : [];
        let serialNum = 1;
        let commentRow = ``;
        let englishId = studentsDetails.subjectRes.find(sub => sub.code == 'ENG');
        englishId = !englishId ? 0 : englishId.id;
        let mathId = studentsDetails.subjectRes.find(sub => sub.code == 'MATHS');
        mathId = !mathId ? 0 : mathId.id;
        // console.log(studentsDetails.resultRes, classResults)
         studentsDetails.studentRes.forEach(student => { 
             let stdOldComments = studentsDetails.classComments.length > 0 
                ? 
                {oldComments: studentsDetails.classComments.filter(comm => comm.student_id == student.id)}
                : {oldComments: []};

            // let stdScores = studentsDetails.resultRes.length > 0 
            //     ? 
            //     studentsDetails.classScores.filter(sco => sco.student_id == student.id && (sco.subject_id == englishId || sco.subject_id == mathId)) 
            //     : [];
            let englishScores = englishId ? studentsDetails.classScores.filter(sco => sco.student_id == student.id && sco.subject_id == englishId) : []; 
            let mathScores = mathId ? studentsDetails.classScores.filter(sco => sco.student_id == student.id && sco.subject_id == mathId) : [];
            let stdScores = [
                {code: 'ENG', TOTAL: englishScores.length ? englishScores.reduce((acc,sco)=>{return acc + +sco.TOTAL},0)/englishScores.length: 0},
                {code: 'MATHS', TOTAL: mathScores.length ? mathScores.reduce((acc,sco)=>{return acc + +sco.TOTAL},0)/mathScores.length : 0}
            ]

            let stdRes = studentsDetails.resultRes.length > 0 ? classResults.filter(res=> res.id == student.id) : [];
            // console.log(stdScores, stdRes)
            commentRow += `
            <tr id=${student.id}><td>${serialNum++}</td>
            <td class='studentName'>${(student.lastName+' '+student.firstName+' '+student.otherName).toUpperCase()}</td>
            ${commentCells(student.id, stdOldComments, stdScores, stdRes)}</tr>`
        });

        document.querySelector('#commentsTable tbody').innerHTML = commentRow;
        document.querySelector('#commentsTable').classList.remove('hide');
        editAction();
        document.querySelectorAll('.commentBtn').forEach(btn => btn.onclick = function(e){
            e.preventDefault(); 
            let stdInfo = {
                name: btn.parentElement.parentElement.querySelector('.studentName').innerHTML,
                id: btn.parentElement.parentElement.getAttribute('id'),
                commentType: btn.parentElement.getAttribute('id').substr(0, 5)
            };
            inputComment(stdInfo, classChoiceForm.termId);
        })
    }

/*******************FUNCTION TO BUILD THE COMMENTS CELLS *************** */
    function commentCells(stdId, studentsDetails, scores, result){
        // let [mathem, engl] = scores.length > 0 ? scores : [null, null];
        let mathem = scores.filter(score=> score.code == 'MATHS')[0];
        let engl = scores.filter(score=> score.code == 'ENG')[0];
        // console.log(scores);
        let noComm = `<button class='btn commentBtn colCode' style='height:auto'><small>Comment</small></button>`;
        let noComments = `
        <td>${result[0] ? suffixer(result[0].Position) : '-' }</td><td>${result[0] ? (result[0].Average).toFixed(2) : '-'}</td><td>${engl ? engl.TOTAL : '-'}</td><td>${mathem ? mathem.TOTAL : '-'}</td>
        <td style='max-width:300px' id='formTComment${stdId}' data-maker='formT'>${noComm}</td>
        <td style='max-width:300px' id='headTComment${stdId}' data-maker='headT'>${noComm}</td><td class='remarkCell'><span id='remark${stdId}'></span></td>`;

        let oldCommCell = (oldComments, commentMaker)=>`<span class='commentSpan'>${oldComments[0][commentMaker]}</span>
        ${editFloatBtn}`;

        if(Object.entries(studentsDetails.oldComments).length == 0){
            return noComments;
        } else {
            let oldComments = studentsDetails.oldComments.filter(comm => comm.student_id == stdId );
            if(Object.entries(oldComments).length == 0){
                return noComments;
            } else {
                // let oldComments = studentsDetails.oldComments.filter(comm => comm.studentId == stdId );
                // console.log(oldComments)
                return `
                <td>${result[0] ? suffixer(result[0].Position) : '-' }</td><td>${result[0] ? (result[0].Average).toFixed(2) : '-'}</td><td>${engl ? engl.TOTAL : '-'}</td><td>${mathem ? mathem.TOTAL : '-'}</td>
                <td style='max-width:300px' id='formTComment${stdId}' data-maker='formT'>${oldComments[0].formTeacherComment ? oldCommCell(oldComments, 'formTeacherComment') : noComm}</td>
                <td style='max-width:300px' id='headTComment${stdId}' data-maker='headT'>${oldComments[0].headTeacherComment ? oldCommCell(oldComments, 'headTeacherComment') : noComm}</td>
                <td class='remarkCell'>
                <span id='remark${stdId}'>
                    ${classChoiceForm.termId == '4' ? 
                        (oldComments[0].promotedOrNotPromoted ?? '')
                        :
                        (oldComments[0].passOrFail ?? '')}
                </span></td>`
            }
        }
    }

/*********************FUNCTION FOR EDITING THE COMMENT ***************** */
    function editAction(){
        document.querySelectorAll('.editComment').forEach(btn =>{
            btn.onclick = function(e){
                e.preventDefault();
                editPF(btn);
                let oldComment = this.parentElement.querySelector('.commentSpan').textContent;
                //identify each old comment in an Object for easy retrieval
                commentEditObj[`${editNum}`] = oldComment;
                this.parentElement.querySelector('.commentSpan').setAttribute('editId', `${editNum++}`);
                // console.log(oldComment)
                this.parentElement.querySelector('.commentSpan').innerHTML = `<textarea class='materialize-textarea editCommentArea' 
                    style='font-size:13px;background-color:#ddd;border-radius:5px' maxlength='250'>${oldComment}
                    </textarea>
                    <button class='btn btn-small btn-floating submitEditedComment right colCode'><i class='material-icons'>send</i></button>
                    <button class='btn btn-small btn-floating closeComment right red'><i class='material-icons'>close</i></button>`
                this.classList.add('hide');
                // btn.classList.remove('editComment');
                submitEd();
                resetComment();
            }
        });
    }


/****************FUNCTION TO EDIT PASSORFAIL*********************** */
    function editPF(btn){
        if(btn.parentElement.getAttribute('data-maker') == 'headT'){
            let stdId = btn.parentElement.parentElement.getAttribute('id');
            let remarkCell = btn.parentElement.parentElement.querySelector('.remarkCell');
            let currRemark = remarkCell.textContent;
            if(classChoiceForm.termId == '4'){
                let remarkDiv = `<table >
                    <tr>
                        <th>PROMOTED</th>
                        <td><button class="btn btn-floating promoteBtn grey" data-promote="PROMOTED">
                            <i class="material-icons ">check</i></button>
                        </td>
                    </tr>
                    <tr>
                        <th>NOT PROMOTED</th>
                        <td><button class="btn btn-floating notPromotedBtn grey" data-promote="NOT PROMOTED">
                            <i class="material-icons ">check</i></button>
                        </td>
                    </tr>
                    <tr>
                        <th>PROMOTED ON TRIAL</th>
                        <td><button class="btn btn-floating trialBtn grey"data-promote="PROMOTED ON TRIAL">
                            <i class="material-icons ">check</i></button>
                        </td>
                    </tr>
                    </table>`
                ;                
                remarkCell.innerHTML = remarkDiv;
                remarkCell.setAttribute('data-remark', currRemark);
                promoButtons(remarkCell.querySelectorAll('button'), remarkCell);
                return;
            }
            let stdRemark = btn.parentElement.parentElement.querySelector(`#remark${stdId}`) ? 
                btn.parentElement.parentElement.querySelector(`#remark${stdId}`).textContent : '';
            // console.log(stdRemark)
            btn.parentElement.parentElement.querySelector(`.remarkCell`).setAttribute('data-remark',stdRemark);
            let remarkDiv = (passC, failC, fCheck)=> `<div class='remDiv' data-check=${fCheck}> 
                <span class="passFail" style='padding-right:4px'>PASS</span>
                <button class="btn-small btn-floating passBtn ${passC}"><i class="material-icons ">check</i></button></div>
                <div><span class="passFail" style='padding-right:4px'>FAIL</span>
                <button class="btn-small btn-floating failBtn ${failC}"><i class="material-icons ">close</i></button></div>`;
            // passBtn.addEventListener('click', function(e){e.preventDefault(); remarkRadio('grey', 'green', 'red', passBtn, failBtn)})
            if(stdRemark == 'PASS'){
                remarkCell.innerHTML = remarkDiv('green', 'grey', 'PASS');
            } else if (stdRemark == 'FAIL') {
                remarkCell.innerHTML = remarkDiv('grey', 'red', 'FAIL');
            } else {
                remarkCell.innerHTML = remarkDiv('grey', 'grey', '');
            }
            let remarkEdit = (mainBtn, otherBtn, chosenCol, removedCol, neutralCol, remarkD, remarkCheck)=>{
                mainBtn.classList.remove(neutralCol);
                mainBtn.classList.add(chosenCol);
                otherBtn.classList.remove(removedCol);
                otherBtn.classList.add(neutralCol);
                remarkD.setAttribute('data-check', remarkCheck);
            }
            let passBtn = remarkCell.querySelector('.passBtn');
            let failBtn = remarkCell.querySelector('.failBtn');
            let remDiv = remarkCell.querySelector('.remDiv');
            passBtn.addEventListener('click', function(e){e.preventDefault(); remarkEdit(passBtn, failBtn, 'green', 'red', 'grey', remDiv, 'PASS')})
            failBtn.addEventListener('click', function(e){e.preventDefault(); remarkEdit(failBtn, passBtn, 'red', 'green', 'grey', remDiv, 'FAIL')})
        }
    }

/*************FUNCTION TO SUBMIT EDITED COMMENT ***************** */
    function submitEd(){
        document.querySelectorAll('.submitEditedComment').forEach(btn=>{
            btn.onclick = function(e){
                e.preventDefault();
                // Activate progress bar
                const progressModal = document.querySelector('#progressModal');
                progressModal.querySelector('.progress').classList.remove('hide');
                const editModal = M.Modal.init(progressModal)


                let commCell = btn.parentElement;
                let editedComm = commCell.querySelector('.editCommentArea').value.trim();
                let editorInfo = this.parentElement.parentElement.getAttribute('id');
                let [commentMaker, stdId] = [editorInfo.substr(0, 12), editorInfo.substr(12, editorInfo.length)];
                let remarkValue = classChoiceForm.termId == '4' ? 
                    (commCell.parentElement.parentElement.querySelector('.remarkCell').dataset.remark ?? '')
                    :
                    commCell.parentElement.parentElement.querySelector('.remDiv') ? 
                    commCell.parentElement.parentElement.querySelector('.remDiv').getAttribute('data-check') : null;

                let showSuccessEdit = (editRes)=>{
                    editModal.close();
                    M.toast({html: '<h5>Comment Edited Successfully</h5>', classes: "green rounded"}, 3000);
                    document.querySelector('.progress').classList.add('hide');
                    commCell.parentElement.querySelector('.editComment').classList.remove('hide');
                    if(commentMaker == 'headTComment'){
                        commCell.parentElement.parentElement.querySelector('.remarkCell').innerHTML = `<span id='remark${stdId}'>${remarkValue}</span>`;
                    }
                    commCell.innerHTML = `${editedComm}`
                }
                if(commentMaker == 'formTComment'){
                    let formObj = buildCommentObj(stdId, 'formTeacherComment', editedComm);
                    editModal.open();
                    AJAXJS(formObj, 'POST', `/comments/submit/${formObj.studentId}`, showSuccessEdit);
                } else {
                    let formObj = buildCommentObj(stdId, 'headTeacherComment', editedComm);
                    if(classChoiceForm.termId != '4'){
                        formObj.passOrFail = remarkValue;
                    } else {
                        formObj.promotedOrNotPromoted = remarkValue;
                    }
                    editModal.open();
                    AJAXJS(formObj, 'POST', `/comments/submit/${formObj.studentId}`, showSuccessEdit);
                };
                // console.log(formObj, commentMaker);
            }
        });
    }

/********************FUNCTION FOR RESETTING COMMENT EDIT AREA ON FOCUSOUT********************** */
    function resetComment(){
        document.querySelectorAll('.closeComment').forEach(btn=>{
            btn.onclick = function(e){
                e.preventDefault();
                let commCell = this.parentElement.parentElement;
                let oldComment = commentEditObj[this.parentElement.getAttribute('editId')];
                commCell.querySelector('.editComment').classList.remove('hide');
                delete commentEditObj[this.parentElement.getAttribute('editId')];
                this.parentElement.innerHTML = `${oldComment}`
                let stdId = commCell.parentElement.getAttribute('id');
                if(commCell.getAttribute('data-maker') == 'headT' && classChoiceForm.termId != '4'){
                    let remarkValue = commCell.parentElement.querySelector('.remarkCell').dataset.remark ?? '';
                    commCell.parentElement.querySelector('.remDiv').setAttribute('data-check', remarkValue);
                    commCell.parentElement.querySelector('.remarkCell').innerHTML = `<span id='remark${stdId}'>${remarkValue}</span>`;
                } else if(commCell.getAttribute('data-maker') == 'headT'){
                    let remarkValue = commCell.parentElement.querySelector('.remarkCell').dataset.remark ?? '';
                    commCell.parentElement.querySelector('.remarkCell').innerHTML = `<span id='remark${stdId}'>${remarkValue}</span>`;
                }
            }
        })
    }

/*******************SELECT INPUT FUNCTION TO FILL THE TEXTAREA WITH SELECTED INPUT**************** */
    commentSelect && commentSelect.addEventListener('change', function(){
        document.querySelector('#commentBox').value = this.selectedOptions[0].innerHTML;
    })

/*******************MODAL BUTTON FUNCTION TO SUBMIT COMMENTS ADD CONTENTS TO THE COMMENT TABLE CELL************************ */
    packAssessmentBtn && packAssessmentBtn.addEventListener('click', function(e){
        e.preventDefault()
        let stdComment = document.querySelector('#commentBox').value;
        let modInsta = document.querySelector('#commentModal');
        let modalProgress = modInsta.querySelector('#modalProgress');

        if(stdComment == ''){
            alert("Please enter a comment!");
            return;
        }
        let stdId = document.querySelector('#commentModal').getAttribute('data-id');
        let commentMaker = document.querySelector('#commentModal').getAttribute('data-comment');
        if(commentMaker == 'formT'){
            let formObj = buildCommentObj(stdId, 'formTeacherComment', stdComment);
            modalProgress.classList.remove('hide');
            AJAXJS(formObj, 'POST', `/comments/submit/${formObj.studentId}`, showSuccess);
        } else {
            let formObj = buildCommentObj(stdId, 'headTeacherComment', stdComment);
            let headTeacherRemark = document.querySelector('#commentModal').dataset.remark ?? '';

            //Add Pass/Fail or Promoted/Not Promoted/On trial if annual
            if(classChoiceForm.termId != '4'){
                formObj.passOrFail = headTeacherRemark;
            } else {
                formObj.promotedOrNotPromoted = headTeacherRemark;
            }
            
            modalProgress.classList.remove('hide');
            AJAXJS(formObj, 'POST', `/comments/submit/${formObj.studentId}`, showSuccess);
            // putComment('headTComment', stdId, stdComment);

        }        
    })

/**************BUILD COMMENT SUBMISSION OBJECT*************** */
    function buildCommentObj(stdId, commentMaker, commentText){
        let commentObj = {};
        commentObj.sessionId = classChoiceForm.sessionId;
        commentObj.schoolId = schoolId;
        commentObj.termId = classChoiceForm.termId;
        commentObj.classId = classChoiceForm.classId;
        commentObj.studentId = stdId;
        commentObj[commentMaker] = commentText;
        // console.log(commentObj);
        return commentObj;
    }

    /**************FUNCTION TO GET THE CORRECT SPAN FOR COMMENT UPDATE************** */
    function putComment(TComment, stdId, stdComment){
        let commCell = document.querySelector(`#commentsTable tbody #${TComment}${stdId}`);
        commCell.innerHTML = `<span class='commentSpan'>${stdComment}</span>
        <button class='btn btn-small btn-floating editComment right colCode'><i class='material-icons'>mode_edit</i></button>`;
        editAction();
        // editPF(commCell.querySelector('.editComment'))
    }    

    /**************FUNCTION TO SHOW COMMENT SUBMISSION SUCCESS**************** */
    function showSuccess(res){
        let modInsta = document.querySelector('#commentModal');
        let modalProgress = modInsta.querySelector('#modalProgress');
        if(res.subCes){
            if(res.data.formTeacherComment){
                putComment('formTComment', res.stdId, res.data.formTeacherComment);
            }
            
            if(res.data.headTeacherComment){
                putComment('headTComment', res.stdId, res.data.headTeacherComment);
                
                // Insert pass/fail or Promoted/N remark
                const remarkVal = classChoiceForm
                    .termId != '4' ? 
                    (res.data.passOrFail ?? '') : 
                    (res.data.promotedOrNotPromoted ?? '');
                document.querySelector(`#commentsTable tbody #remark${res.stdId}`).textContent = remarkVal;
            }

            modalProgress.classList.add('hide');
            M.Modal.getInstance(modInsta).close();
            M.toast({html: `<h5>${res.subCes}</h5>`, classes: "green rounded"}, 3000);
            document.querySelector('.progress').classList.add('hide');
        }
    }


    let promotionBtns = document.querySelectorAll('#commentModal table button');
    /********Setup Buttons for Promotion in Annual Comments.**************************/
    function promoButtons(promoBtns, remarkCon = document.querySelector('#commentModal')){
        promoBtns.forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                promoBtns.forEach(allbtn => {allbtn.classList.remove('black'); allbtn.classList.add('grey');});
                btn.classList.add('black');
                remarkCon.setAttribute('data-remark', btn.dataset.promote);
                // console.log(btn.dataset.promote)            
            })
        })
    }
    promoButtons(promotionBtns);


})
/**********END OF DOMCONTENTLOADED************ */




/*********FUNCTION TO OPEN THE COMMENT MODAL FOR COMMENT ENTRY*********** */
function inputComment(studentInfo, termId){
    let modInsta = document.querySelector('#commentModal');
    document.querySelector('#commentModal .modal-content .stdInfo').textContent = `Name: ${studentInfo.name}`;
    modInsta.setAttribute('data-id', studentInfo.id);
    modInsta.setAttribute('data-comment', studentInfo.commentType);
    modInsta.querySelector('#commentBox').value = '';
    const modalPromotion = document.querySelector('#commentModal #promotion')
    // const modalPassFail = document.querySelector('#commentModal #passOrFail')

    if(studentInfo.commentType == 'headT' && termId == '4'){
        modalPromotion.classList.remove('hide');
        // modalPassFail.classList.add('hide');
    } else if(studentInfo.commentType == 'headT') {
        modalPromotion.classList.add('hide');
        // modalPassFail.classList.remove('hide');
    } else {
        modalPromotion.classList.add('hide');
        // modalPassFail.classList.add('hide');
    }
    resetRemark()
    // passOrFail()
    M.Modal.getInstance(modInsta).open();
}

/***************FUNCTION TO REMARK PASS OR FAIL ****************** */
// function passOrFail(){
    let passBtn = document.querySelector('#commentModal #passOrFail .passBtn')
    let failBtn = document.querySelector('#commentModal #passOrFail .failBtn')
    passBtn && passBtn.addEventListener('click', function(e){e.preventDefault(); remarkRadio('grey', 'green', 'red', passBtn, failBtn)})
    failBtn && failBtn.addEventListener('click', function(e){e.preventDefault(); remarkRadio('grey', 'red', 'green', failBtn, passBtn)})
// }


function remarkRadio(plain, main, other, mainBtn, otherBtn){
    // console.log(main);
    mainBtn.classList.remove(plain);
    mainBtn.classList.add(main);
    otherBtn.classList.remove(other);
    otherBtn.classList.add(plain);
    let passRemark = mainBtn.parentElement.querySelector('.passFail').textContent;
    document.querySelector('#commentModal').setAttribute('data-remark', passRemark);

}

function resetRemark(){
    // document.querySelector('#commentModal #passOrFail .passBtn').classList.add('grey')
    // document.querySelector('#commentModal #passOrFail .failBtn').classList.add('grey')
    document.querySelectorAll('#commentModal table button')
        .forEach(allbtn => {allbtn.classList.remove('black'); allbtn.classList.add('grey');});
    document.querySelector('#commentModal').setAttribute('data-remark', '');
}


/*****************AJAX FUNCTION TO SELECT THE CHOSEN CLASS, SESSION AND TERM FROM DB****************** */
function AJAXJS(formObj, actionMET, actionURL, successFxn){
    // document.querySelector('.progress').classList.remove('hide');
    let aj = new XMLHttpRequest();
    aj.open(actionMET, actionURL);
    aj.onload = ()=>{
        if(aj.status == 200){
            let returnObj = JSON.parse(aj.responseText);
            // console.log(returnObj);
            successFxn(returnObj);
        }
    };
    aj.setRequestHeader('Content-Type', 'application/json');
    aj.setRequestHeader('X-CSRF-Token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    aj.send(JSON.stringify(formObj));
}

export {AJAXJS};