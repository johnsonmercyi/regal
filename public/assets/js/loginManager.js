document.addEventListener('DOMContentLoaded', function(){

    let uname = document.querySelector('#uname');
    let pword = document.querySelector('#pword');
    let subBtn = document.querySelector('#subBtn');
    let submitBtn = document.querySelector('#loginSubmit');
    let rePasswordBtn = document.querySelector('#rePasswordBtn');
    let resultSectionBtn = document.querySelector('#profileResults');
    let profileSection = document.querySelector('#profileSection');
    let resultSection = document.querySelector('#resultSection');
    let staffProfileScores = document.querySelector('#staffProfileScores');
    let staffProfileSection = document.querySelector('#staffProfileSection');
    let staffResultSection = document.querySelector('#staffResultSection');
    let staffResultBtn = document.querySelector('#staffResultBtn');
    let scoresSection = document.querySelector('#scoresSection');


    /******************SUBMIT BUTTON ACTION FOR LOGIN ************************* */

    submitBtn && submitBtn.addEventListener('click', function(e){
        e.preventDefault();

        let loginObj = {
            uname: document.querySelector('#username').value,
            pword: document.querySelector('#password').value
        }

        AJAXJS(loginObj, 'POST', '/login/user', function(resp){
            if(resp.success){
                window.location.href = resp.location;
            }
            if(resp.error){
                M.toast({html: '<h6>The username or password is incorrect</h6>', classes: 'red rounded'}, 6000);
                document.querySelector('#password').value = '';
            }
        })

    })
    
    rePasswordBtn && rePasswordBtn.addEventListener('click', function(e){
        e.preventDefault();
        let schoolId = document.querySelector('#schoolId').value;

        let passwordObj = {
            uname: document.querySelector('#uname').value,
            pword: document.querySelector('#pword').value,
            schoolId
        }

        AJAXJS(passwordObj, 'POST', '/user/repassword', function(resp){
            if(resp.success){
                M.toast({html: '<h6>Password changed successfully</h6>', classes: 'green rounded'}, 6000);
            }
            if(resp.error){
                M.toast({html: '<h6>Unable to change password</h6>', classes: 'red rounded'}, 6000);
            }
        })

    })


    // subBtn && subBtn.addEventListener('click', function(e){
    //     e.preventDefault();
    //     document.querySelector('.progress').classList.remove('hide');
    //     let loginObj = {
    //         uname: uname.value.split('/'),
    //         pword: pword.value
    //     }
    //     // console.log(loginObj)
    //     AJAXJS(loginObj, 'POST', '/login/user', function(resp){
    //         document.querySelector('.progress').classList.add('hide');

    //         if(resp.Authenticated){
    //             console.log('Correct!')
    //             document.querySelector("#loginError").classList.add('hide')
    //             // setTimeout(window.location.assign(`/students/profile/${resp.id}`), 2000);
    //             if(resp.role == "STD"){
    //                 window.location.assign(`/student/profile/${resp.id}`);
    //             } else if(resp.role == "ADMIN") {
    //                 window.location.assign(`/schools/${resp.school}`);
    //             } else if(resp.role == "STF") {
    //                 window.location.assign(`/staff/profile/${resp.id}`);
    //             } else {
    //                 window.location.assign(`/login`);
    //             }
    //         } else {
    //             // console.log('Incorrect!');
    //             document.querySelector("#loginError").classList.remove('hide')
    //         }
    //     })
    // })

    /*******************BUTTON TO REVEAL THE RESULT SECTION IN THE STUDENTS PROFILE******************* */
    resultSectionBtn && resultSectionBtn.addEventListener('click', function(e){
        e.preventDefault();
        profileSection.classList.add('hide');
        resultSection.classList.remove('hide');
    })

    /*******************BUTTON TO REVEAL THE SCORES SECTION IN THE STAFF PROFILE******************* */
    staffProfileScores && staffProfileScores.addEventListener('click', function(e){
        e.preventDefault();
        staffProfileSection.classList.add('hide');
        scoresSection.classList.remove('hide');
    })

    /*******************BUTTON TO REVEAL THE RESULT SECTION IN THE STAFF PROFILE******************* */
    staffResultBtn && staffResultBtn.addEventListener('click', function(e){
        e.preventDefault();
        staffProfileSection.classList.add('hide');
        staffResultSection.classList.remove('hide');
    })

})



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
