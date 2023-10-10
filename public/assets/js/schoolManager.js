import AJAXJS from './ajaxModule.js';
document.addEventListener('DOMContentLoaded', function(){

    let sessionChangeBtn = document.querySelector('#sessionChangeBtn');
    let schoolId = document.querySelector('#schoolId').value;
    let sessionId = document.querySelector('#sessionId').value;
    let termId = document.querySelector('#termId').value;
    // let backupBtn = document.querySelector('#dbBackUpBtn');
    let submitResumeDateBtn = document.querySelector('#submitResumeDate');
    let changeSchoolDetails = document.querySelector('#changeSchoolDetails');
    let schoolDetailsForm = document.querySelector('#schoolDetailsForm');
    let progElem = document.querySelector('#progressModal');

    sessionChangeBtn.addEventListener('click', function(e){
        e.preventDefault();
        let sessionChangeObj = {
            currentSessionId: document.querySelector('#sessionChangeForm #sessionIdSelect').value,
            currentTermId: document.querySelector('#sessionChangeForm #termIdSelect').value,
        }
        for(const [key, val] of Object.entries(sessionChangeObj)){
            if(val == ''){
                alert('Please Select a '+key.substr(7, key.length-9).toUpperCase());
                return false;
            }
        }
        document.querySelector("#sessionChgSection .progress").classList.remove('hide');

        let submitObj = {
            academic_session_id: sessionChangeObj.currentSessionId,
            current_term_id: sessionChangeObj.currentTermId,
        };
        // console.log(sessionChangeObj);
        AJAXJS(JSON.stringify(submitObj), 'PUT', `/schools/${schoolId}`, false, function(resp){
            document.querySelector("#sessionChgSection .progress").classList.add('hide');
            if(resp.success){
                M.toast({html:'<h5>School Update Successful!</h5>', classes:'green'}, 2000);
                setTimeout(window.location.assign(`/schools/${schoolId}`), 2000);
            } else {
                M.toast({html:'<h5>Unable to Save Changes!</h5>', classes:'red'}, 4000);
            }
        })
    })
    
    changeSchoolDetails.addEventListener('click', function(e){
        e.preventDefault();

        let schoolChangeObj = new FormData(schoolDetailsForm)

        for(let [key, val] of schoolChangeObj.entries()){
            if(val == ''){
                M.toast({html:"<h5>Please Fill in all Fields</h5>", classes:"red rounded"})
                return;
            }
        }

        progElem.querySelector('.progress').classList.remove('hide');
        M.Modal.getInstance(progElem).open()

        AJAXJS(schoolChangeObj, 'POST', `/schools/update/${schoolId}`, true, function(resp){
            M.Modal.getInstance(progElem).close()

            if(resp.success){
                M.toast({html:'<h5>School Update Successful!</h5>', classes:'green'}, 2000);
                setTimeout(window.location.assign(`/schools/${schoolId}/edit`), 2000);

            } else {
                M.toast({html:'<h5>Unable to Save Changes!</h5>', classes:'red'}, 4000);
            }
        })
    })


    submitResumeDateBtn && submitResumeDateBtn.addEventListener('click', function(e){
        e.preventDefault();
        let startDate = document.querySelector('#resumeDate').value;
        if(!startDate){alert('Please select a date!'); return;}

        let dateObj = {
            startDate,
            schoolId,
            termId,
            sessionId,
        }
        document.querySelector('#resumeSection .progress').classList.remove('hide');
        console.log(dateObj);
        AJAXJS(JSON.stringify(dateObj), 'POST', `/schools/term/resume`, false, function(resp){
            document.querySelector("#resumeSection .progress").classList.add('hide');
            if(resp.updated){
                document.querySelector('#nextTermDate').innerHTML = startDate;
                M.toast({html:'<h5>Resumption Date Saved Successfully!</h5>', classes:'green'}, 2000);
            } else {
                M.toast({html:'<h5>Unable to Save Changes!</h5>', classes:'red'}, 4000);
            }
        })
    })


    // backupBtn && backupBtn.addEventListener('click', function(e){
    //     e.preventDefault();
    //     alert('Backup');
    //     AJAXJS({}, 'POST', `/schools/database/backup`, function(resp){
    //         //
    //     })
    // })

})