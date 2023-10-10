// import {AJAXJS} from './subjectsManager.js';
document.addEventListener('DOMContentLoaded', function(){

    let loadMoveClassBtn = document.querySelector('#loadCurrentClassStudents');
    let loadStudentsStatusBtn = document.querySelector('#loadStudentsStatusBtn');
    let loadRemoveClassBtn = document.querySelector('#loadRemoveClassStudents');
    let removeStudentsBtn = document.querySelector('#submitRemovedStudents');
    let selectFormTeacherBtn = document.querySelectorAll('.selectFormTeacher');
    let sessionId = document.querySelector('#sessionId').value;
    let schoolId = document.querySelector('#schoolId').value;
    let termId = document.querySelector('#termId').value;
    let submitFormTeacher = document.querySelector('#submitAssignedFormTeacher');
    let formTMod = document.querySelector('#formAssignModal');
    let selectModal = formTMod ? M.Modal.init(formTMod) : null;
    let moveStudentsBtn = document.querySelector('#submitMovedStudents');
    let selStdModalObj = document.querySelector('#selectStudents #confirmModal');
    let selStdModal = selStdModalObj ? M.Modal.init(selStdModalObj) : null;
    let progModal = document.querySelector('#progressModal');
    let movedStudentsArr = [];
    let removedStudentsArr = [];
    let oldClassId = null;

    $('#classData').DataTable();
    $('#studentData').DataTable();

    // let loadMoveClassBtn = document.querySelector('loadCurrentClassStudents');

    loadMoveClassBtn && loadMoveClassBtn.addEventListener('click', function(e){
        e.preventDefault()
        return loadClassTable();
    });
    
    loadRemoveClassBtn && loadRemoveClassBtn.addEventListener('click', function(e){
        e.preventDefault()
        return loadClassTable();
    });

    function loadClassTable(){
        let checkVal = true;
        let classObj = {
            classId: document.querySelector('#classId').value,
            sessionId,
            termId,
            schoolId
        };
        checkVal = classObj.classId == '' ? false : true;

        if(checkVal){
            // console.log(classObj);
            oldClassId = classObj.classId;
            document.querySelector('#classStudentsSection').classList.add('hide');
            AJAXJS(classObj, 'POST', `/class/${classObj.classId}/members/move`, getClassMembers);
        } else {
            alert('Please Select a Class!');
            return false;
        }       
    }

    function getClassMembers(resp){
        document.querySelector('.progress').classList.add('hide');
        if(resp.members.length == 0){
            M.toast({html:"<h5>Selected Class has No Members!", classes:"red"}, 4000);
            return false;
        }
        let membsTable = ``;
        let selectedClass = document.querySelector('#classId').selectedOptions[0].textContent;
        let snum =1;
        resp.members.forEach(student=>{
            let id = student.student_id;
            membsTable += `<tr data-id='${id}' data-check='false' id='std${id}'><td>${snum++}</td><td>${student.regNo}</td>
            <td>${(student.lastName+' '+student.firstName+' '+student.otherName).toUpperCase()}</td>
            <td><button class="btn btn-floating grey"><i class="material-icons ">check</i></button></td>`
        })
        document.querySelector('#classStudentsBody').innerHTML = membsTable;
        document.querySelector('#classInfo').innerHTML = selectedClass;
        document.querySelector('#classStudentsSection').classList.remove('hide');
        document.querySelectorAll('#classStudentsBody tr button').forEach(btn=> {
            btn.addEventListener('click', function(e){e.preventDefault(); makeRadio(btn)})
        });
        // $("#classStudentsTable").DataTable({paging: false});
    }

    selectFormTeacherBtn && selectFormTeacherBtn.forEach(btn=> {btn.addEventListener('click', function(e){
        e.preventDefault();
        let classInfo = btn.parentElement.parentElement.querySelector('.classInfoName').textContent;
        let classIdInfo = btn.parentElement.parentElement.getAttribute('id');
        formTMod.setAttribute('data-id', classIdInfo);
        formTMod.querySelector('#classInfo').textContent = classInfo;
        formTMod.querySelector('#formAssignForm').reset();
        selectModal.open();

        });
    });


/*************FUNCTION TO SUBMIT SELECTED FORM TEACHER***************************** */
    submitFormTeacher && submitFormTeacher.addEventListener('click', function(e){
        e.preventDefault();
        let teacherId = formTMod.querySelector('#formAssignForm #formTeacherSelect').value;
        let teacherName = formTMod.querySelector('#formAssignForm #formTeacherSelect').selectedOptions[0].textContent;
        if(!teacherId){
            formTMod.querySelector('#showAssignModalError').classList.remove('hide');
            return false;
        }
        let classFormObj = {
            sessionId,
            schoolId,
            classId: formTMod.getAttribute('data-id'),
            teacherId,
        }
        // console.log(classFormObj)
        document.querySelector('#formAssignModal .progress').classList.remove('hide');

        AJAXJS(classFormObj, 'POST', `/class/formTeacher/store`, function(resp){
            if(resp){
                M.toast({html:'<h5>Form Teacher Assigned Successfully</h5>', classes:'green'}, 3000) 
                document.querySelector(`#classData tbody .class${classFormObj.classId} .teacherInfoName`).textContent = teacherName;
                document.querySelector('.progress').classList.add('hide');
                document.querySelector('#formAssignModal .progress').classList.add('hide');
                selectModal.close()
            }
            else{M.toast({html:'<h5>Unable to Assign Form Teacher</h5>', classes:'red'}, 3000) }
        });
        formTMod.querySelector('#showAssignModalError').classList.add('hide');
    })

    formTMod && formTMod.querySelector('#close').addEventListener('click', function(e){
        e.preventDefault()
        selectModal.close();
    })


/*******************FUNCTION TO SUBMIT STUDENTS TO NEW CLASS************************* */
    moveStudentsBtn && moveStudentsBtn.addEventListener('click', function(e){
        e.preventDefault();
        movedStudentsArr = [];
        let classId = document.querySelector('#newClassId').value;
        let newClass = document.querySelector('#newClassId').selectedOptions[0].innerHTML;
        let stdObj = {sessionId, schoolId};
        if(classId == ''){ alert('Please Select New Class!'); return false;}
        if(oldClassId == classId){ alert('Please Select a Different Class!'); return false;}
        document.querySelectorAll('#classStudentsBody tr').forEach(tRow=>{
            if(tRow.getAttribute('data-check') == 'true'){
                let studentId = tRow.getAttribute('data-id');
                movedStudentsArr.push({...stdObj, studentId, classId});
            }
        })
        let arrlen = movedStudentsArr.length;
        if(arrlen < 1) return false;
        selStdModalObj.querySelector('#confirmQuery').textContent = `You are moving ${arrlen} student${arrlen >1 ? 's' : ''} to ${newClass}. Continue?`
        selStdModal.open();
    })

    selStdModalObj && selStdModalObj.querySelector('#noConfirm').addEventListener('click', function(e){
        e.preventDefault()
        selStdModal.close()
    })

    selStdModalObj && selStdModalObj.querySelector('#yesConfirm').addEventListener('click', function(e){
        e.preventDefault()
        selStdModalObj.querySelector('.progress').classList.remove('hide');
        // console.log(movedStudentsArr)
        moveStudentsBtn && AJAXJS(movedStudentsArr, 'POST', '/class/students/move', function(resp){
            moveStudentsResponseFxn(resp)
        });
        
        removeStudentsBtn && AJAXJS(removedStudentsArr, 'POST', '/students/remove', function(resp){
            removeStudentsResponseFxn(resp)
        });
    });

    function moveStudentsResponseFxn(resp){
        if(resp.movedStudents[0].length > 0){
            selStdModalObj.querySelector('.progress').classList.add('hide');
            document.querySelector('.progress').classList.add('hide');
            M.toast({html:"<h5>Students moved to new class successfully</h5>", classes:"green"}, 4000);
            selStdModal.close()
            resp.movedStudents.forEach(student=>{
                document.querySelector(`#classStudentsTable #std${student}`).classList.add('hide')
            })
            // Array.from(document.querySelectorAll('#classStudentsTable tr'))
            //     .filter(tRow=> resp.movedStudents.includes(tRow.getAttribute('data-id')))
            //     .forEach(tRow=>{
            //         let rIndx = tRow.rowIndex;
            //         document.querySelector('#classStudentsTable').deleteRow(rIndx);
            //     })
        } else {
            M.toast({html:"<h5>Unable to change class!</h5>", classes:"red"}, 4000);
        }
    }

    function removeStudentsResponseFxn(resp){
        if(resp.removed[0].length > 0){
            selStdModalObj.querySelector('.progress').classList.add('hide');
            document.querySelector('.progress').classList.add('hide');
            M.toast({html:"<h5>Student(s) removed successfully</h5>", classes:"green"}, 4000);
            selStdModal.close()
            resp.removed.forEach(student=>{
                document.querySelector(`#classStudentsTable #std${student}`).classList.add('hide')
            })
            // 794671
        } else {
            M.toast({html:"<h5>Unable to remove student!</h5>", classes:"red"}, 4000);
        }
    }

    selStdModalObj && selStdModalObj.querySelector('#close').addEventListener('click', function(e){
        e.preventDefault()
        selStdModal.close()
    })

    removeStudentsBtn && removeStudentsBtn.addEventListener('click', function(e){
        e.preventDefault();

        removedStudentsArr = [];
        // let removeReason = document.querySelector('#removeReasonId').selectedOptions[0].innerHTML;
        let stdObj = {sessionId, schoolId};
        document.querySelectorAll('#classStudentsBody tr').forEach(tRow=>{
            if(tRow.getAttribute('data-check') == 'true'){
                let studentId = tRow.getAttribute('data-id');
                removedStudentsArr.push({...stdObj, studentId});
            }
        })
        let arrlen = removedStudentsArr.length;
        if(arrlen < 1) return false;
        selStdModalObj.querySelector('#confirmQuery').textContent = `You are removing ${arrlen} student${arrlen >1 ? 's' : ''}. Continue?`
        selStdModal.open();
    })


    /***********MANAGE STUDENTS TERMLY STATUS********** */
    let submitStudentsStatus = document.querySelector('#submitStudentsStatus');

    loadStudentsStatusBtn && loadStudentsStatusBtn.addEventListener('click', (e) => {
        e.preventDefault();

        let classId = document.querySelector('#classId').value;
        let termId = document.querySelector('#termIdSelect').value;
        let sessionId = document.querySelector('#sessionIdSelect').value;

        if(classId == '' || termId == '' || sessionId == ''){
            alert("Please select all fields");
            return;
        }

        let fetchObj = {classId, termId, sessionId};

        progModal.querySelector('.progress').classList.remove('hide');
        M.Modal.getInstance(progModal).open();

        AJAXJS(fetchObj, 'POST', '/students/status/fetch', (res) => {
            
            M.Modal.getInstance(progModal).close();
            
            if(res.members){

                if(res.members.length > 0){
                    let membsTable = ``;
                    let selectedClass = document.querySelector('#classId').selectedOptions[0].textContent;
                    
                    res.members.forEach((student, i)=>{
                        let id = student.student_id;
                        membsTable += `<tr data-id='${id}' data-check='${student.term_status == '1' ? '1' : '0'}' id='std${id}'><td>${++i}</td><td>${student.regNo}</td>
                        <td>${(student.lastName+' '+student.firstName+' '+student.otherName).toUpperCase()}</td>
                        <td><button class="btn btn-floating ${student.term_status == '1' ? 'green' : 'grey'}"><i class="material-icons ">check</i></button></td>`
                    })

                    document.querySelector('#studentsStatusTable tbody').innerHTML = membsTable;
                    document.querySelector('#studentsStatusTable').setAttribute('data-term', res.term_id);
                    document.querySelector('#studentsStatusTable').setAttribute('data-session', res.session_id);
                    document.querySelector('#classInfo').innerHTML = selectedClass;
                    document.querySelector('#studentsStatusSection').classList.remove('hide');

                    document.querySelectorAll('#studentsStatusTable tbody tr').forEach(rw => {

                        let btn = rw.querySelector('button');
                        btn.addEventListener('click', function(e){
                            e.preventDefault(); 
                            
                            let rowStatus = rw.dataset.check;
                            if(rowStatus == '1'){
                                rw.setAttribute('data-check', '0');
                                btn.classList.remove('green');
                                btn.classList.add('grey');
                            } else {
                                rw.setAttribute('data-check', '1');
                                btn.classList.remove('grey');
                                btn.classList.add('green');
                            }
                        })

                    });

                }

            } else {
                M.toast({html: "<h5>Unable to fetch!", classes: "red-text rounded"});
            }
        })

    })

    submitStudentsStatus && submitStudentsStatus.addEventListener('click', function(e) {
        e.preventDefault()
        let studentsStatusTable = document.querySelector('#studentsStatusTable');
        let term_id = studentsStatusTable.dataset.term;
        let session_id = studentsStatusTable.dataset.session;
        let statusArr = [];

        studentsStatusTable.querySelectorAll('tbody tr').forEach(rw => {
            let student_id = rw.dataset.id;
            let status = rw.dataset.check;
            statusArr.push({student_id, status});
        })

        progModal.querySelector('.progress').classList.remove('hide');
        M.Modal.getInstance(progModal).open();

        AJAXJS({schoolId, term_id, session_id, statusArr}, 'POST', '/students/status/change', (res) => {

            M.Modal.getInstance(progModal).close();
            if(res.status){
                M.toast({html: "<h5>Students status updated successfully!</h5>", classes: "rounded white green-text"});

            } else {
                M.toast({html: "<h5>Unable to change status!</h5>", classes: "rounded white red-text"});
            }

        })

        // console.log(statusArr);
    })
    
});

/****************FUNCTION FOR CALLBACK ON AJAX REQUEST********************* */
    function studentsMoved(resp){
        //
    }

/*************FUNCTION TO MAKE BUTTON RADIO************* */
function makeRadio(btn){
    let check = btn.parentElement.parentElement.getAttribute('data-check')
    if(check == 'true'){
        btn.parentElement.parentElement.setAttribute('data-check', 'false')
        btn.classList.remove(...['darken-2', 'green']);
        btn.classList.add('grey');
    } else {
        btn.parentElement.parentElement.setAttribute('data-check', 'true')
        btn.classList.remove('grey');
        btn.classList.add(...['darken-2', 'green']);}
}


/*****************AJAX FUNCTION TO SELECT THE CHOSEN CLASS, SESSION AND TERM FROM DB****************** */
function AJAXJS(formObj, actionMET, actionURL, successFxn){
    document.querySelector('.progress').classList.remove('hide');
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
/*************END OF AJAX************** */