document.addEventListener('DOMContentLoaded', function(){
    let studentBtn = document.querySelector('#studentBtn');
    let staffBtn = document.querySelector('#searchStaffBtn');
    let restoreBtn = document.querySelector('#restoreBtn');
    let restoreStudentsModal = document.querySelector('#restoreStudentsModal');
    let progressModal = document.querySelector('#progressModal');

    studentBtn && studentBtn.addEventListener('click', function(e){
        e.preventDefault()
        let studentName = document.querySelector('#searchName').value;

        if(studentName.trim() === ''){
            alert("Please fill in a name!")
            return;
        }

        progressModal.querySelector('.progress').classList.remove('hide');
        M.Modal.getInstance(progressModal).open()

        AJAXJS({studentName}, 'POST', '/archive/student/search', function(res){
            console.log(res)

            M.Modal.getInstance(progressModal).close()

            if(res.students){

                let archiveStudentsTable = document.querySelector("#archiveStudentsTable tbody");
                let archivedStudentsSection = document.querySelector("#archivedStudentsSection");
                let archiveStudentsTableBody = '';
                let num = 0;

                res.students.forEach(student => {
                    archiveStudentsTableBody += `
                        <tr>
                            <td>${++num}</td>
                            <td>${student.regNo }</td>
                            <td class="nameCell">${ student.lastName + ' ' + student.firstName + ' ' + student.otherName }</td>
                            <td>${ student.level + ' ' + student.suffix }</td>
                            <td>${ student.sessionName }</td>
                            <td>
                                <button class="btn-small colCode reactivateBtn" data-std="${ student.student_id }" class="" >Reactivate</button>
                            </td>
                        </tr>
                    `
                })

                archiveStudentsTable.innerHTML = archiveStudentsTableBody;
                archivedStudentsSection.classList.remove('hide');
                archiveStudentsTable.querySelectorAll('tr').forEach(row => {

                    let studentName = row.querySelector('.nameCell').textContent,
                        btn = row.querySelector('.reactivateBtn'),
                        studentId = btn.dataset.std;

                    btn.addEventListener('click', (e)=>{
                        restoreStudentsModal.querySelector('#studentName').innerHTML = studentName;
                        restoreStudentsModal.setAttribute('data-std', studentId);
                        M.Modal.getInstance(restoreStudentsModal).open();
                    })

                })

            }
            else
            {
                M.toast({html: "<h5>No students found!</h5>", classes: "red-text"})
                let archiveStudentsTable = document.querySelector("#archiveStudentsTable tbody");
                archiveStudentsTable.innerHTML = '';
            }

        })
    })



    staffBtn && staffBtn.addEventListener('click', function(e){
        e.preventDefault()
        let staffName = document.querySelector('#searchName').value;

        if(staffName.trim() === ''){
            alert("Please fill in a name!")
            return;
        }

        progressModal.querySelector('.progress').classList.remove('hide');
        M.Modal.getInstance(progressModal).open()

        AJAXJS({staffName}, 'POST', '/archive/staff/search', function(res){
            console.log(res)

            M.Modal.getInstance(progressModal).close()

            if(res.staff && res.staff.length > 0){

                let archiveStaffTable = document.querySelector("#archiveStaffTable tbody");
                let archivedStaffSection = document.querySelector("#archivedStaffSection");
                let archiveStaffTableBody = '';
                let num = 0;

                res.staff.forEach(staff => {
                    archiveStaffTableBody += `
                        <tr>
                            <td>${++num}</td>
                            <td>${staff.regNo }</td>
                            <td class="nameCell">${ staff.lastName + ' ' + staff.firstName + ' ' + staff.otherNames }</td>
                            <td>
                                <button class="btn-small colCode reactivateStaffBtn" data-std="${ staff.id }" class="" >Reactivate</button>
                            </td>
                        </tr>
                    `
                })

                archiveStaffTable.innerHTML = archiveStaffTableBody;
                archivedStaffSection.classList.remove('hide');

                archiveStaffTable.querySelectorAll('tr').forEach(row => {

                    let staffName = row.querySelector('.nameCell').textContent,
                        btn = row.querySelector('.reactivateStaffBtn'),
                        staffId = btn.dataset.std;

                        
                    btn.addEventListener('click', (e)=>{
                        
                        M.Modal.getInstance(progressModal).open()
                        progressModal.querySelector('.progress').classList.remove('hide');
                        AJAXJS({staffId}, 'POST', '/archive/staff/reactivate', function(res){

                            M.Modal.getInstance(progressModal).close()

                            if(res.status){
                                M.toast({html: "<h5>Staff successfully reactivated!", classes: "white green-text"});
                                // M.Modal.getInstance(restoreStaffsModal).close();
                            }
                            else{
                                M.toast({html: "<h5>Unable to reactivate staff!", classes: "white red-text"});
                            }

                        })
                        // restoreStaffModal.querySelector('#staffName').innerHTML = staffName;
                        // restoreStaffModal.setAttribute('data-std', staffId);
                        // M.Modal.getInstance(restoreStaffModal).open();
                    })

                })

            }
            else
            {
                M.toast({html: "<h5>No staff found!</h5>", classes: "red-text white"})
                let archiveStaffTable = document.querySelector("#archiveStaffTable tbody");
                archiveStaffTable.innerHTML = '';
            }

        })
    })



    restoreBtn.addEventListener('click', (e)=>{
        e.preventDefault();

        let studentId = restoreStudentsModal.dataset.std;
        let termId = document.querySelector('#termIdSelect').value
        let sessionId = document.querySelector('#sessionIdSelect').value
        let classId = document.querySelector('#classSelect').value

        if(termId == '' || sessionId == '' || classId == ""){
            alert('Please select all fields');
            return;
        }

        progressModal.querySelector('.progress').classList.remove('hide');
        M.Modal.getInstance(progressModal).open()

        AJAXJS({studentId, termId, sessionId, classId}, 'POST', '/archive/student/restore', (res) => {
            M.Modal.getInstance(progressModal).close()

            if(res.status){
                M.toast({html: "<h5>Student successfully reactivated!", classes: "white green-text"});
                M.Modal.getInstance(restoreStudentsModal).close();
            }
            else{
                M.toast({html: "<h5>Unable to reactivate student!", classes: "white red-text"});
            }

            console.log(res);
        })
    })



    function openRestoreModal(){
        M.Modal.getInstance(restoreStudentsModal).open()
    }


})



/*****************AJAX FUNCTION TO SELECT THE UNSYNCED TABLE DATA FROM DB****************** */
function AJAXJS(sendObj, actionMET, actionURL, successFxn){
    let aj = new XMLHttpRequest();
    aj.open(actionMET, actionURL);
    aj.onload = ()=>{
        if(aj.status == 200){
            let returnObj = JSON.parse(aj.responseText);
            // console.log(returnObj);
            successFxn(returnObj);
        } else {
            document.querySelector('.progress').classList.add('hide');
            M.toast({html: "<h5>Error! Please Contact Admin</h5>", classes: "red"}, 4000);
        }
    };

    aj.setRequestHeader('Content-Type', 'application/json');
    aj.setRequestHeader('X-CSRF-Token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    aj.send(JSON.stringify(sendObj));
}
/*************END OF AJAX************** */
