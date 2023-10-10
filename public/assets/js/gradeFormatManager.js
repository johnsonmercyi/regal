document.addEventListener('DOMContentLoaded', function(){
    //
    let addRowBtn = document.querySelector('#addGradeFormatRow');
    let submitGradesBtn = document.querySelector('#submitGradeFormat');
    let schoolId = document.querySelector('#schoolId').value;
    let confirmDialog = document.querySelector('#confirmModal');
    let successDialog = document.querySelector('#successModal');
    let confirmModal = M.Modal.init(confirmDialog);
    let gradesArr = [];
    let count = 2;
    let createBtn = document.querySelector('#createNewFormat');
    let assignBtn = document.querySelectorAll('.assignGrade');
    
    addRowBtn.addEventListener('click', function(e){
        e.preventDefault();
        let gradesTable = document.querySelector('#gradeTableBody');
        let rowContent = `<td>${count++}</td>
                    <td><input type='text' class="center description" style='background-color:#ddd;border-radius:5px' /></td>
                    <td><input type='text' class="center numInput grade" /></td>
                    <td><input type='number' class="center numInput minScore"/></td>
                    <td><input type='number' class="center numInput maxScore"/></td>
                    `;
        if(count<12){
            let newRow = gradesTable.insertRow(gradesTable.rows.length);
            newRow.innerHTML = rowContent;
        }
    });

    submitGradesBtn.addEventListener('click', function(e){
        e.preventDefault();
        if(!checkEmpty()){
            alert('Please Fill in the Missing Values!');
            return;
        }
        confirmModal.open();
    });


    createBtn && createBtn.addEventListener('click', function(e){
        e.preventDefault();
        document.querySelector('#existFormatSection').classList.add('hide');
        document.querySelector('#gradeAssignSection').classList.add('hide');
        document.querySelector('#createFormatSection').classList.remove('hide');
    })


    assignBtn && assignBtn.forEach(btn=>{
        btn.addEventListener('click', function(e){
            e.preventDefault();
            document.querySelector('#gradeAssignSection .progress').classList.remove('hide');
            let assignRow = btn.parentElement.parentElement;
            let sectionId = btn.getAttribute('data-id');
            let gradeAssignObj = {
                sectionId,
                schoolId,
                gradingId: assignRow.querySelector(`#assess${sectionId}`).value,
            }
            // console.log(gradeAssignObj);
            AJAXJS(gradeAssignObj, 'POST', '/grade/format/assign', function(resp){
                if(resp.updated){
                    M.toast({html:"<h5>Grade Format Updated Successfully!", classes:"green"}, 3000);
                    document.querySelector('#gradeAssignSection .progress').classList.add('hide');
                    assignRow.querySelector('.gradeForm').innerHTML = `Format ${gradeAssignObj.gradingId}`;
                    // setTimeout(window.location.assign(`/${schoolId}/grades/create`), 3000);
                } else {
                    M.toast({html:"<h5>Unable to Update Grade Format!", classes:"red"}, 3000);
                    document.querySelector('#gradeAssignSection .progress').classList.add('hide');
                }
            })
        })
    })

/******************FUNCTION TO CONFIRM SUBMISSION OF FORMAT****************************** */
    document.querySelector('#yesConfirm').addEventListener('click', function(){
        //
        packGrade();
        AJAXJS(gradesArr, 'POST', `/${schoolId}/grades/store/`, submitSuccess);
        // console.log(gradesArr)
        document.querySelector('.progress').classList.remove('hide');
        confirmModal.close();
        submitGradesBtn.setAttribute('disabled', 'true');
    })

/******************FUNCTION TO CANCEL SUBMISSION****************************** */
    document.querySelector('#noConfirm').addEventListener('click', function(){
        confirmModal.close();
    })

/******************FUNCTION TO CHECK EMPTY VALUES BEFORE SUBMIT THE FORMAT****************************** */
    function checkEmpty(){
        let confirm = true;
        const gradeRows = document.querySelectorAll('#gradeTableBody tr');
        gradeRows.forEach(row =>{
            let rowInputs = row.querySelectorAll('input');
            for(const inp of rowInputs){
                // console.log(inp.value)
                if(inp.value == ''){
                    confirm = false;
                    break;
                }
            }
        });
        return confirm;
    }


/*************FUNCTION TO PACK THE GRADE FORMAT******** */
    function packGrade(){
        const gradeRows = document.querySelectorAll('#gradeTableBody tr');
        gradeRows.forEach(row =>{
            let gradeObj = {
                school_id: schoolId,
                description: row.querySelector('.description').value,
                grade: row.querySelector('.grade').value,
                minScore: row.querySelector('.minScore').value,
                maxScore: row.querySelector('.maxScore').value,
                status: '1',
            }
            gradesArr.push(gradeObj);
        });
    }

/*************FUNCTION TO CREATE ACTION AFTER SUCCESSFUL SUBMISSION*********************** */
    function submitSuccess(resp){
        if(resp.successInfo){
            document.querySelector('.progress').classList.add('hide');
            console.log(resp)
            let successModal = M.Modal.init(successDialog);
            successModal.open();
            setTimeout(window.location.assign(`/${schoolId}/grades/create`), 3000);
        }
    }


});

/*****************AJAX FUNCTION TO SELECT THE CHOSEN CLASS, SESSION AND TERM FROM DB****************** */
function AJAXJS(formObj, actionMET, actionURL, successFxn){
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