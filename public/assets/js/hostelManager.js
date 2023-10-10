document
.addEventListener('DOMContentLoaded', function(){
    let studentReg = document.querySelector('#studRegNo');
    let verifyBtn = document.querySelector('#verifyBtn');



    /********************FUNCTION TO CHECK STUDENT DETAILS***************** */
    verifyBtn && verifyBtn.addEventListener('click', function(e){
        e.preventDefault();
        let stdObj = {stdId: studentReg.value};
        if(!stdObj.stdId){
            alert('Please Enter Student Registration Number');
            return false;
        }
        document.querySelector('.progress').classList.remove('hide');
        AJAXJS(stdObj, 'POST', '/hostel/student/verify', function(resp){
            console.log(resp);
            document.querySelector('.progress').classList.add('hide');

            document.querySelector('#stdName').innerHTML = `${resp.student.lastName+' '+resp.student.firstName+' '+resp.student.otherName}`;
            document.querySelector('#stdReg').innerHTML = `${resp.student.regNo}`;
            document.querySelector('#stdDept').innerHTML = `${resp.croom.level+resp.croom.suffix}`;
            document.querySelector('#verifiedSection').classList.remove('hide')
        })
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