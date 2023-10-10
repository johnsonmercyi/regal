document.addEventListener('DOMContentLoaded', () => {

    let studentsJson = document.querySelector('#studentsInfo').value;
    let promoteButton = document.querySelector('#promoteBtn');
    let sessionId = document.querySelector('#sessionId').value;
    let schoolId = document.querySelector('#schoolId').value;
    let newSessionId = +sessionId + 1;
    let progressModal = document.querySelector('#progressModal')
    let chooseStudentsModal = document.querySelector('#chooseStudentsModal')
    let chooseStudentsTable = document.querySelector('#chooseStudentsTable tbody')
    let saveChosenStudentsBtn = document.querySelector('#saveChosenStudentsBtn')
    let promotionRows = document.querySelectorAll('#promotionTable tbody tr');
    let promotionArray = [];

    promotionRows.forEach((prow) => {
        let classSelect = prow.querySelector('select').innerHTML;
        let chooseStudentBtn = prow.querySelector('.chooseStudentBtn');
        let class_id = prow.dataset.classid;

        chooseStudentBtn.addEventListener('click', (e) => {
            e.preventDefault();

            let studentsInfo = JSON.parse(studentsJson);
            let classInfo = studentsInfo[class_id];
            let studentsRows = '';
            let num = 1; 
            let studentData = null;

            classInfo.forEach(student => {

                if(promotionArray.length > 0){
                    studentData = promotionArray.find(std => std.student_id === student.id);
                }

                studentsRows += `
                    <tr data-id=${student.id}><td>${num++}</td><td>${student.lastName +' '+ student.firstName}</td>
                    <td>
                    <select class="browser-default" value=${studentData ? studentData.class_id : ""}>${classSelect}</select>
                    </td></tr>
                `
            })

            chooseStudentsTable.innerHTML = studentsRows;
            M.Modal.getInstance(chooseStudentsModal).open()
        })
    })

    saveChosenStudentsBtn && saveChosenStudentsBtn.addEventListener('click', (e)=>{
        e.preventDefault();
        let valid = true;

        chooseStudentsTable.querySelectorAll('tr').forEach(student => {
            let student_id = student.dataset.id;
            let new_class = student.querySelector('select').value;

            if(new_class == ""){
                valid = false;
            }

            
            if(promotionArray.length > 0){

                studentData = promotionArray.find(std => std.student_id == student_id);
                let studentIndex = promotionArray.findIndex(std => std.student_id == student_id);
                // promotionArray.find(student => student.student_id == student_id)
                if(studentData){
                    studentData.class_id = new_class;
                    promotionArray[studentIndex] = studentData;
                }

            } else {
                promotionArray.push({
                    student_id: student_id,
                    school_id: schoolId,
                    academic_session_id: newSessionId,
                    first_term: '1',
                    class_id: new_class,
                    status: 'Active'
                })
            }

            // cls.forEach(student => {
            // });
        })

        if(!valid){
            alert("Please Select Class for all the Students!")
            return;
        }

        M.Modal.getInstance(chooseStudentsModal).open()

    })


    promoteButton && promoteButton.addEventListener('click', () => {
        // console.log(JSON.parse(studentsInfo))
        let studentsInfo = JSON.parse(studentsJson);
        let classRows = document.querySelectorAll('#promotionTable tbody tr');
        let classToNewClass = {};
        let checkValid = true;

        classRows.forEach(cls => {

            let class_id = cls.dataset.classid;
            let newClass = cls.querySelector('select').value;

            if(newClass == ''){
                checkValid = false;
            }

            classToNewClass[class_id] = newClass;

        })

        if(!checkValid){
            alert("Please select all new classes.");
            return;
        }

        Object.values(studentsInfo).map(cls => {

            cls.forEach(student => {

                // if(promotionArray.length > 0){
                
                let studentData = promotionArray.find(std => std.student_id == student.id);
                    // let studentIndex = promotionArray.findIndex(std => std.student_id == student.id);
                    // promotionArray.find(student => student.student_id == student_id)
                if(studentData){

                    // studentData.class_id = new_class;
                    // promotionArray[studentIndex] = studentData;
                    
    
                } else {

                    promotionArray.push({
                        student_id: student.id,
                        school_id: schoolId,
                        academic_session_id: newSessionId,
                        first_term: '1',
                        class_id: +classToNewClass[student.class_id],
                        status: 'Active'
                    })

                }
                
            });

        })

        const promotionData = {
            students: promotionArray, 
            school: {academic_session_id: newSessionId, current_term_id: 1}
        }

        // console.log(promotionArray, classToNewClass);
        progressModal.querySelector('.progress').classList.remove('hide')
        M.Modal.getInstance(progressModal).open()

        AJAXJS(promotionData, 'POST', '/students/promote', (res) =>{

            M.Modal.getInstance(progressModal).close()

            if(res.status){
    
                M.toast({html:'<h5>Students Promoted Successfully!</h5>', classes:'green-text white'}, 2000);
                setTimeout(window.location.assign(`/schools/${schoolId}`), 2000);

            }
            else
            {
                M.toast({html:'<h5>Students Promotion Failed!</h5>', classes:'red-text white'}, 2000);
            }

        })
    })

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