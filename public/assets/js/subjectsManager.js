document.addEventListener('DOMContentLoaded', function () {


    let loadBtn = document.querySelector('#loadAssignedSubjectStudents');
    let classChoiceForm = {};
    let classSubjectAssignTable = document.querySelector('#unassignedSubjectsTable tbody');
    let classAssignedTable = document.querySelector('#assignedSubjectsTable tbody');
    let sectionAssignedTable = document.querySelector('#sectionAssignedSubjectsTable tbody');
    let sectReassign = document.querySelectorAll('#sectionAssignedSubjectsTable .reassignSect');
    let submitAssignedStudentsBtn = document.querySelector('#submitAssignedStudents');
    let loadClassAssignedSubjectsBtn = document.querySelector('#loadClassAssignedSubjects');
    let classAssignSubmitBtn = document.querySelector('#submitClassAssignedSubject');
    let sectionAssignSubmitBtn = document.querySelector('#submitSectionAssignedSubject');
    let assignModalObj = document.querySelector('#classAssignModal');
    let sectionModalObj = document.querySelector('#sectionAssignModal');
    let sessionId = document.querySelector('#sessionId').value;
    let termId = document.querySelector('#termId').value;
    let assignModal = assignModalObj ? M.Modal.init(assignModalObj) : null;
    let sectionModal = sectionModalObj ? M.Modal.init(sectionModalObj) : null;
    let sectionBtn = document.querySelectorAll('.selectSectionAssign');
    let sectionReturnBtn = document.querySelector('#sectionReturn');
    let compulsorySectionAssignBtn = document.querySelector('#compulsorySectionAssign');
    let teachersArr = sectionModalObj ? Array.from(sectionModalObj.querySelector('#teacherSelect').children).
        map(option => ({ teacherId: option.value, teacherName: option.textContent })) : [];
    let sectSubRemoveBtn = `<button class="sectSubRemove btn btn-floating btn-small red right" ><i class="material-icons">close</i></button>`;
    let sectSubReassignBtn = `<button class="reassignSect btn btn-floating btn-small right colCode" ><i class="material-icons">edit</i></button>`;
    let sectionClassesObj = { classArr: [], sectionObj: {} };
    let schoolId = document.querySelector('#schoolId').value;
    let deleteModalObj = document.querySelector('#subjectTeacherPage #confirmModal');
    let deleteModal = deleteModalObj ? M.Modal.init(deleteModalObj) : null;
    let selStdModalObj = document.querySelector('#selectStudents #confirmModal');
    let selStdModal = selStdModalObj ? M.Modal.init(selStdModalObj) : null;
    let selectAllBtn = document.querySelector('#assignTableSection #selectAllStudents');
    let assignSpan = document.querySelector('#classAssignTableSection #countAssigned');
    let multiModalObj = document.querySelector('#classAssignTableSection #confirmModal');
    let multiAssignModal = multiModalObj ? M.Modal.init(multiModalObj) : null;
    let oldSubjectStudents = [];
    let submitOrderBtn = document.querySelector('#submitSubjectOrder');

    /** CHINWATAKWEAKU INITIALIZATIONS STARTS */
    let nurSubjectCategory = document.querySelector('#nurSubjectCategory');
    let createNurSubject = document.querySelector('#createNurSubject');
    let add_row = document.querySelector('#add_row');
    let createSubject = document.querySelector('#createSubject');
    /** CHINWATAKWEAKU INITIALIZATIONS ENDS */


    /** CHINWATAKWEAKU INITIALIZATIONS FUNCTIONS STARTS */
    nurSubjectCategory && nurSubjectCategory.addEventListener('click', (e) => {
        e.preventDefault();
        // alert('Working');
        // return; 

        let subject_Category = document.querySelector('#subject_Category').value;
        let subjectCateForm = document.querySelector('#subjectCateForm');

        if (subject_Category == "") {
            //alert('PLEASE ENTER SUB CATEGORY');
            M.toast({ html: "PLEASE ENTER SUBJECT CATEGORY", classes: "red" }, 3000)
            return
        } else {
            // console.log(subcategory); 
            AJAXJS(subject_Category, 'POST', '/subjects/nurserysubjectCategory', (resp) => {
                if (resp.success) {
                    //alert("Subject Category Successfully Added");
                    M.toast({ html: "Subject Category Successfully Added", classes: "green" }, 3000)
                    location.reload();

                    return;
                } else {
                    // M.toast({html:"Subject Cannot be Added", classes:"red"}, 3000) 
                    alert('Error');
                    return;
                }
            });
        }
    });

    createNurSubject && createNurSubject.addEventListener('click', (e) => {
        e.preventDefault();
        // alert('Working');
        // return;     
        let subjectDetails = {
            categoryID: document.querySelector('#categoryID').value,
            nurserysubject: document.querySelector('#nurserysubject').value
            // subCategory: document.querySelector('#subCategory').value
        };

        // return;
        if (subjectDetails.categoryID == '') {
            M.toast({ html: "PLEASE SELECT SUBJECT CATEGORY", classes: "red" }, 3000);
            return
        } else if (subjectDetails.nurserysubject == '') {
            M.toast({ html: "PLEASE ENTER SUBJECT", classes: "red" }, 3000)
            return;
        } else {
            // console.log(subjectDetails);
            // return;
            AJAXJS(subjectDetails, 'POST', '/subjects/nurserysubject', (resp) => {
                if (resp.success) {
                    M.toast({ html: "Subject Successfully Added", classes: "green" }, 3000)
                    location.reload();
                } else {
                    M.toast({ html: "Cannot add subject, contact admin", classes: "green" }, 3000)
                    location.reload();
                }
            })
        }

    });
    /** CHINWATAKWEAKU INITIALIZATIONS FUNCTIONS ENDS */




    loadBtn && loadBtn.addEventListener('click', function (e) {
        e.preventDefault();
        let checkLoad = loadData();

        if (checkLoad) {
            // document.querySelector('#resultListTable').classList.add('hide');
            document.querySelector('#assignTableSection').classList.add('hide');
            AJAXJS(classChoiceForm, 'POST', `/subjects/students/${classChoiceForm.classId}`, fillAssignTable);
            btnRadio(selectAllBtn, 'false', 'black', 'grey');
        }
    })

    /***************FUNCTION TO SUBMIT SELECTED STUDENTS*************** */
    submitAssignedStudentsBtn && submitAssignedStudentsBtn.addEventListener('click', function (e) {
        e.preventDefault();
        selStdModalObj.querySelector('#confirmQuery').textContent = `You selected ${packAssignedStudents()[0]} students. Do you want to submit?`;
        selStdModal.open()
    })

    selStdModalObj && selStdModalObj.querySelector('#noConfirm').addEventListener('click', function (e) {
        e.preventDefault()
        selStdModal.close()
    })
    selStdModalObj && selStdModalObj.querySelector('#close').addEventListener('click', function (e) {
        e.preventDefault()
        selStdModal.close()
    })


    selStdModalObj && selStdModalObj.querySelector('#yesConfirm').addEventListener('click', function (e) {
        e.preventDefault()
        // let stdAssignArr = packAssignedStudents();
        let assignObj = {
            classId: classChoiceForm.classId,
            sessionId: classChoiceForm.sessionId,
            schoolId: classChoiceForm.schoolId,
            // termId: classChoiceForm.termId,
            subjectId: classChoiceForm.subjectId,
            students: JSON.stringify(packAssignedStudents()[1]),
            removed: JSON.stringify(packAssignedStudents()[2]),
        }
        selStdModalObj.querySelector('.progress').classList.remove('hide');
        AJAXJS(assignObj, 'POST', '/subjects/submitassign', function (resp) {
            document.querySelector('.progress').classList.add('hide');
            selStdModalObj.querySelector('.progress').classList.add('hide');

            if (resp.success) {
                M.toast({ html: '<h4>Subject Members Updated Successfully</h4>', classes: "green" }, 3000);
                selStdModal.close()

            } else {
                let failedDialog = document.querySelector('#failedModal');
                let failedModal = M.Modal.init(failedDialog);
                failedModal.open();
            }
        });
    })


    /***************FUNCTION TO LOAD ASSIGNED SUBJECTS************ */
    loadClassAssignedSubjectsBtn && loadClassAssignedSubjectsBtn.addEventListener('click', function (e) {
        e.preventDefault();
        let tempObj = {
            classId: document.querySelector('#classId').value,
            // termId: document.querySelector('#schoolCurrentTerm').value,
            // sessionId: document.querySelector('#schoolCurrentSession').value,
            sessionId,
        }

        let checkSelect = () => {
            for (const [key, value] of Object.entries(tempObj)) {
                if (value == '') {
                    alert(`Please Select a ${key.substring(0, key.length - 2).toUpperCase()}`);
                    return false;
                }
            }
            return true;
        }

        if (checkSelect) {
            AJAXJS(tempObj, 'GET', `/subjects/class/assign`, fillClassAssignTable);
        }
    });


    /************FUNCTION TO SELECT ALL STUDENTS************ */
    selectAllBtn && selectAllBtn.addEventListener('click', function (e) {
        e.preventDefault();
        // console.log('hi')
        let allRows = document.querySelectorAll('#assignTableBody tr');
        let allRadio = (check, aRows, oldCol, newCol) => {
            aRows.forEach(sRow => {
                sRow.setAttribute('data-check', check);
                sRow.querySelector('button').classList.remove(oldCol)
                sRow.querySelector('button').classList.add(newCol)
            })
        }
        if (selectAllBtn.getAttribute('data-check') == 'false') {
            btnRadio(selectAllBtn, 'true', 'grey', 'black');
            allRadio('true', allRows, 'grey', 'black');
        } else {
            btnRadio(selectAllBtn, 'false', 'black', 'grey');
            allRadio('false', allRows, 'black', 'grey');
        }

    })

    let btnRadio = (btn, check, oldCol, newCol) => {
        btn.setAttribute('data-check', check);
        btn.classList.remove(oldCol);
        btn.classList.add(newCol);
    }


    /**********************BTN ACTION TO SUBMIT SUBJECT ORDER********************* */
    submitOrderBtn && submitOrderBtn.addEventListener('click', function (e) {
        e.preventDefault();
        let orderObj = packOrder();
        console.log(orderObj);
        document.querySelector('.progress').classList.remove('hide')
        AJAXJS(orderObj, 'POST', '/subjects/order/store', function (resp) {
            document.querySelector('.progress').classList.remove('hide')

            if (resp.success) {
                M.toast({ html: "<h5>Subject Order Submitted</h5>", classes: "red" }, 3000);
            }
        })
    })

    /***************FUNCTION TO LOAD DATA********************** */
    function loadData() {
        // GET THE VARIABLES TO SELECT A CLASS FOR SCORE ENTRY
        classChoiceForm.classId = document.querySelector('#classId').value;
        classChoiceForm.sessionId = document.querySelector('#sessionId').value;
        // classChoiceForm.termId =  document.querySelector('#termId').value;
        classChoiceForm.subjectId = document.querySelector('#subjectId').value;
        classChoiceForm.schoolId = schoolId;

        // CHECK THE SELECTED VARIABLES (CLASS, SESSION, TERM, SUBJECT) AND THROW ERROR
        for (const [key, value] of Object.entries(classChoiceForm)) {
            if (value == '') {
                alert(`Please Select a ${key.substring(0, key.length - 2).toUpperCase()}`);
                return false;
            }
        }
        return true;
    }

    classAssignedTable && classAssignedTable.querySelectorAll('.reassignSect').forEach(btn => assignBtnAction(btn));
    // classAssignedTable && classAssignedTable.querySelectorAll('.reassignSect').forEach(btn=> assignSectionFxn(btn, assignmodalConfig));

    classAssignedTable && document.querySelectorAll('#assignedSubjectsTable .sectSubRemove').forEach(btn => deleteAssignFxn(btn));
    // document.querySelectorAll('#assignedSubjectsTable .sectSubRemove').forEach(btn=> deleteAssignFxn(btn));
    deleteModalObj && deleteModalObj.querySelector('#close').addEventListener('click', function (e) {
        e.preventDefault()
        deleteModal.close()
    })


    /*****************FUNCTION TO ASSIGN SUBJECT TO CLASS*********************** */
    let unassignNum = 1;
    classSubjectAssignTable && document.querySelectorAll('#unassignedSubjectsTable button').forEach(butt => {
        // unassignArr.push(unassignNum++);
        // assignBtnAction(butt)
        butt.addEventListener('click', function (e) { e.preventDefault; makeRadio(butt) })
    })

    classSubjectAssignTable && document.querySelector('#assignMultiBtn').addEventListener('click', function (e) {
        e.preventDefault;
        let [assignSubArr, assignDetails] = packClassSubjects();
        if (assignSubArr.length == 0) {
            alert('No Subjects Selected')
            return;
        } else {
            for (const val of assignSubArr) {
                if (val.staff_id == "" || val.subjectType == "") {
                    let sName = assignDetails.filter(subJ => subJ.subjectId == val.subject_id)[0].subjectName;
                    alert(`Please Select a Teacher and Subject Type for ${sName}`)
                    // break;
                    return false;
                }
            }
            console.log(assignSubArr)

            multiModalObj.querySelector('#confirmQuery').textContent = `Are you sure you want to submit?`;
            multiAssignModal.open();

            // if(val.subjectName){delete val.subjectName;}
        }
    });

    function packClassSubjects() {
        let subRows = document.querySelectorAll('#unassignedSubjectsTable tr');
        let assignSubArr = [];
        let assignDetails = [];
        subRows.forEach(sRow => {
            if (sRow.getAttribute('data-check') == 'true') {
                let subObj = {
                    class_id: document.querySelector('#classId').value,
                    section_id: document.querySelector('#sectionId').value,
                    subject_id: sRow.getAttribute('data-id'),
                    academic_session_id: document.querySelector('#sessionId').value,
                    school_id: schoolId,
                    staff_id: sRow.querySelector('.teacherSelect').value,
                    subjectType: sRow.querySelector('.subjectTypeSelect').value,
                    students: 'All',
                    status: '1',
                };
                let subDetails = {
                    subjectId: sRow.getAttribute('data-id'),
                    subjectName: sRow.querySelector('.subjectName').innerHTML,
                    teacherName: sRow.querySelector('.teacherSelect').selectedOptions[0].innerHTML,
                    rowId: sRow.rowIndex,
                }
                assignSubArr.push(subObj);
                assignDetails.push(subDetails);
            }
        })
        return [assignSubArr, assignDetails];
    }

    multiModalObj && multiModalObj.querySelector('#close').addEventListener('click', function (e) {
        multiAssignModal.close();
    })
    multiModalObj && multiModalObj.querySelector('#noConfirm').addEventListener('click', function (e) {
        multiAssignModal.close();
    })
    multiModalObj && multiModalObj.querySelector('#yesConfirm').addEventListener('click', function (e) {
        // multiAssignModal.close();
        multiModalObj.querySelector('.progress').classList.remove('hide');
        submitMultiAssign(packClassSubjects());
    })


    function submitMultiAssign(subjectPackage) {
        AJAXJS(subjectPackage[0], 'POST', `/subjects/class/assign/many`, function (resp) {
            document.querySelector('.progress').classList.add('hide');
            if (resp.storeSuccess) {
                M.toast({ html: `<h5>${subjectPackage[0].length} Subjects Successfully Assigned</h5>`, classes: "green" }, 3000)
                multiModalObj.querySelector('.progress').classList.add('hide');
                multiAssignModal.close();
                let assignCount = parseInt(assignSpan.textContent) + subjectPackage[0].length;
                assignSpan.innerHTML = `${assignCount} `;
                // console.log(assignCount)

                subjectPackage[0].forEach(subj => {
                    let subjDetails = subjectPackage[1].filter(d => d.subjectId == subj.subject_id)[0];
                    let rowContent = `<td class="subjectName">${subjDetails.subjectName}</td>
                            <td class="teacherName">${subjDetails.teacherName}</td><td class="subjectType">${subj.subjectType}</td>
                            <td>${sectSubReassignBtn}</td><td>${sectSubRemoveBtn}</td>`;
                    let newRow = classAssignedTable.insertRow(classAssignedTable.rows.length);
                    newRow.setAttribute('data-subject', subj.subject_id);
                    newRow.setAttribute('data-teacher', subj.staff_id);
                    newRow.setAttribute('id', `subject${subj.subject_id}`);
                    newRow.innerHTML = rowContent;
                    // document.querySelector('#unassignedSubjectsTable').deleteRow();
                    let rowId = document.querySelector(`#unassignedSubjectsTable #subject${subj.subject_id}`).rowIndex;
                    document.querySelector('#unassignedSubjectsTable').deleteRow(rowId);
                })

                document.querySelectorAll('#assignedSubjectsTable .reassignSect').forEach(btn => assignBtnAction(btn, assignmodalConfig));
                document.querySelectorAll('#assignedSubjectsTable .sectSubRemove').forEach(btn => deleteAssignFxn(btn));
            } else {
                M.toast({ html: "Error Assigning Subject", classes: "red" }, 3000)
            }
        });
    }

    function assignBtnAction(butt) {
        butt.addEventListener('click', function (e) {
            e.preventDefault();
            let tRow = butt.parentNode.parentNode;
            let rowIndx = tRow.rowIndex;
            let subjectName = tRow.querySelector('.subjectName').innerHTML;
            let subjectId = tRow.getAttribute('data-id') ? tRow.getAttribute('data-id') : tRow.getAttribute('data-subject');
            assignModalObj.querySelector('.modal-content .subjectName').textContent = subjectName;
            assignModalObj.querySelector('.modal-content #classAssignForm').reset();
            assignModalObj.setAttribute('data-id', subjectId);
            assignModalObj.setAttribute('data-row', rowIndx);
            assignModal.open();
        })
    }

    /***************FUNCTION TO SUBMIT ASSIGNED CLASS SUBJECTS*************** */
    classAssignSubmitBtn && classAssignSubmitBtn.addEventListener('click', function (e) {
        e.preventDefault();
        // let assignModal = document.querySelector('#classAssignModal');
        let selectedSubject = assignModalObj.querySelector('.subjectName');
        let selectedTeacher = assignModalObj.querySelector('#teacherSelect');
        let selectedSubjectType = assignModalObj.querySelector('#subjectTypeSelect').value;
        let teacherName = assignModalObj.querySelector('#teacherSelect').selectedOptions[0].innerHTML;
        let subType = assignModalObj.querySelector('#subjectTypeSelect').selectedOptions[0].innerHTML;
        let subjectAssignObj = {
            class_id: document.querySelector('#classId').value,
            subject_id: assignModalObj.getAttribute('data-id'),
            academic_session_id: document.querySelector('#sessionId').value,
            school_id: schoolId,
            // termId: document.querySelector('#termId').value,
            staff_id: selectedTeacher.value,
            subjectType: selectedSubjectType,
            students: 'All',
            status: '1',
        }
        console.log(subjectAssignObj)
        if (selectedTeacher.value != '' && selectedSubjectType != '') {
            document.querySelector('#showAssignModalError').classList.add('hide');
            assignModalObj.querySelector('.progress').classList.remove('hide');
            let rowIndx = parseInt(assignModalObj.getAttribute('data-row'))

            AJAXJS(subjectAssignObj, 'POST', `/subjects/class/store`, function (resp) {
                document.querySelector('.progress').classList.add('hide');
                if (resp.storeSuccess) {
                    M.toast({ html: "<h5>Subject Successfully Assigned</h5>", classes: "green" }, 3000)
                    assignModalObj.querySelector('.progress').classList.add('hide');
                    let reassignRow = document.querySelector(`#assignedSubjectsTable #subject${subjectAssignObj.subject_id}`)
                    reassignRow.querySelector('.teacherName').innerHTML = teacherName;
                    reassignRow.querySelector('.subjectType').innerHTML = subType;
                    assignModal.close();
                    // let assignCount = parseInt(assignSpan.textContent) + 1;
                    // assignSpan.innerHTML = `${assignCount} `;
                    // console.log(assignCount)

                    // let rowContent = `<td class="subjectName">${selectedSubject.innerHTML}</td>
                    //             <td>${selectedTeacher.selectedOptions[0].innerHTML}</td><td>${selectedSubjectType}</td>
                    //             <td>${sectSubReassignBtn}</td><td>${sectSubRemoveBtn}</td>`;
                    // let newRow = classAssignedTable.insertRow(classAssignedTable.rows.length);
                    // newRow.setAttribute('data-subject', subjectAssignObj.subjectId);
                    // newRow.setAttribute('data-teacher', subjectAssignObj.teacherId);
                    // newRow.innerHTML = rowContent;
                    // document.querySelector('#unassignedSubjectsTable').deleteRow(rowIndx);
                    // document.querySelectorAll('#assignedSubjectsTable .reassignSect').forEach(btn=> assignBtnAction(btn, assignmodalConfig));
                    // document.querySelectorAll('#assignedSubjectsTable .sectSubRemove').forEach(btn=> deleteAssignFxn(btn));
                } else {
                    M.toast({ html: "Error Assigning Subject", classes: "red" }, 3000)
                }
            });

        } else {
            document.querySelector('#showAssignModalError').classList.remove('hide');
        }
    })

    classAssignSubmitBtn && assignModalObj.querySelector('#close').addEventListener('click', function (e) {
        assignModal.close();
    })

    /**************FUNCTION TO FILL TABLE FOR SUBJECT ASSIGN******************* */
    function fillAssignTable(resp) {
        let assignTableBody = ``;
        let classTitle = document.querySelector('#classId').selectedOptions[0].innerHTML;
        let subjectTitle = document.querySelector('#subjectId').selectedOptions[0].innerHTML;
        let sNum = 1;
        const buttonFunc = (student, color, check) => `<tr data-check='${check}' data-id='${student.id}'><td>${sNum++}</td><td>${student.regNo}</td><td>${student.lastName + ' ' + student.firstName + ' ' + student.otherName}</td>
        <td><button class='btn btn-floating ${color}' ><i class='material-icons'>check</i></button></td></tr>`

        document.querySelector('.progress').classList.add('hide');

        if (resp.oldMembers.length == 0) {
            console.log('empty');
            M.toast({ html: '<h5>Subject is Not Assigned to Class</h5>', classes: "red" }, 5000);
            return false;
        }
        else if (resp.oldMembers[0].students == "All" && resp.oldMembers[0].subjectType == 'Compulsory') {
            resp.students.forEach(student => {
                assignTableBody += buttonFunc(student, 'black', 'true')
            });
            document.querySelector('#submitAssignedStudents').classList.add('hide');
        } else if (resp.oldMembers[0].students == "All" && resp.oldMembers[0].subjectType == 'Selective') {
            resp.students.forEach(student => {
                assignTableBody += buttonFunc(student, 'grey', 'false')
            });
            document.querySelector('#submitAssignedStudents').classList.remove('hide');
        }
        else {
            let oldMembers = JSON.parse(resp.oldMembers[0].students).students;
            oldSubjectStudents = oldMembers;
            resp.students.forEach(student => {
                if (oldMembers.includes(student.id)) {
                    assignTableBody += buttonFunc(student, 'black', 'true')
                } else {
                    assignTableBody += buttonFunc(student, 'grey', 'false')
                }
            });
            document.querySelector('#submitAssignedStudents').classList.remove('hide');
        }

        document.querySelector('#assignTableBody').innerHTML = assignTableBody;
        document.querySelector('#classInfo').innerHTML = `${classTitle} students for ${subjectTitle} <small>(${resp.oldMembers[0].subjectType})</small>`;
        document.querySelectorAll('#assignTableBody tr').forEach(assignRow => {
            if (resp.oldMembers[0].subjectType !== 'Compulsory') {
                let btn = assignRow.querySelector('button')
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    makeRadio(btn);
                })
            }
        })
        document.querySelector('#assignTableSection').classList.remove('hide');
    }

    /******************FUNCTION TO SHOW SUCCESSFUL CLASS ASSIGNMENT********************** */
    function classAssignSubmit(resp) {
        document.querySelector('.progress').classList.add('hide');
        if (resp.storeSuccess) {
            M.toast({ html: "Subject Successfully Assigned", classes: "green" }, 3000)
        } else {
            M.toast({ html: "Error Assigning Subject", classes: "red" }, 3000)
        }
    }

    /****************FUNCTION TO PACK SELECTED STUDENTS********************** */
    function packAssignedStudents() {
        let assignedStudentsArr = [];
        let removed = [];
        document.querySelectorAll('#assignTableBody tr').forEach(student => {
            let check = student.getAttribute('data-check');
            let stdId = parseInt(student.getAttribute('data-id'));
            if (check == 'true') {
                assignedStudentsArr.push(stdId);
            } else {
                removed.push(stdId);
            }
        });

        // let removed = oldSubjectStudents.length != 0 ? oldSubjectStudents.filter(mem => !assignedStudentsArr.includes(mem)) : [];
        return [assignedStudentsArr.length, { students: assignedStudentsArr, }, { removed }];
    }

    /******************FUNCTION TO FILL CLASS ASSIGN TABLES********************** */
    function fillClassAssignTable(resp) {
        let assignedSubjects = ``;
        resp.assignedSubjects.forEach(subject => {
            assignedSubjects += `<tr><td>${subject.subjectTitle}</td><td>${subject.lastName + ' ' + subject.firsName}</td><td>${subject.type}</td></tr>`;
        });

        document.querySelector('#assignedSubjectsTable thead').innerHTML = assignedSubjects;
        document.querySelector('#classAssignTableSection').classList.remove('hide');
    }


    /***************FUNCTION TO PICK A SCHOOL SECTION TO ASSIGN******************** */
    sectionBtn && sectionBtn.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector('.progress').classList.remove('hide');

            let sectionId = btn.getAttribute('data-id');
            let sectionType = btn.getAttribute('data-type');
            compulsorySectionAssignBtn.setAttribute("data-id", sectionId);
            compulsorySectionAssignBtn.setAttribute("data-type", sectionType);
            let sectionTitle = btn.parentElement.querySelector('.sectionName').textContent;
            let fetchObj = { sectionId };

            // console.log(teachersArr)
            AJAXJS(fetchObj, 'POST', '/subjects/section/assign', function (resp) {
                document.querySelector('.progress').classList.add('hide');
                if (resp.sectionClasses) {
                    let classTable = ``;
                    resp.sectionClasses.forEach(croom => {
                        sectionClassesObj.classArr.push({ sessionId, schoolId, classId: croom.id });
                        classTable += `<tr><td class=center'>${croom.classLevel + ' ' + croom.classSuffix}</td><td class="center">
                        <a href="/subjects/class/${croom.id + '/' + sessionId + '/' + termId}" 
                        class="btn btn-floating waves-effect waves-light colCode lighten-1">
                            <i class="material-icons">edit</i>
                        </a>
                    </td>`
                    });
                    document.querySelector('#sectionCollectionList').classList.add('hide');
                    document.querySelector('#classSubjectsTable tbody').innerHTML = classTable;
                    document.querySelector('#classTableSection #sectionTitle').innerHTML = sectionTitle;
                    document.querySelector('#classTableSection').classList.remove('hide');

                } else {
                    M.toast({ html: '<h4>Failed to Load Section</h4>', classes: "red" }, 4000);

                }
            });
        });
    });

    /**************FUNCTION TO RETURN TO SECTION LIST******************* */
    sectionReturnBtn && sectionReturnBtn.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector('#classTableSection').classList.add('hide');
        document.querySelector('#sectionCollectionList').classList.remove('hide');
    })

    /***************FUNCTION TO FETCH SECTION SUBJ3CTS************************ */
    compulsorySectionAssignBtn && compulsorySectionAssignBtn.addEventListener('click', function (e) {
        e.preventDefault();
        let fetchObj = { sectionId: compulsorySectionAssignBtn.getAttribute("data-id"), sessionId };
        let sectionSubTitle = document.querySelector('#classTableSection #sectionTitle').textContent;
        // console.log(fetchObj)

        AJAXJS(fetchObj, 'POST', '/subjects/section/get', function (resp) {
            document.querySelector('.progress').classList.add('hide');
            let unassignedSub = ``;
            let assignedSub = ``;
            let assignedSubId = resp.sectionSubjects.length > 0 ? resp.sectionSubjects.map(subj => (subj.subjectId)) : [];
            // console.log(assignedSubId)
            if (assignedSubId.length > 0) {
                resp.sectionSubjects.forEach(subj => {
                    console.log(subj)
                    let subjectName = resp.subjects.filter(subject => subject.id == subj.subjectId)[0].subjectTitle;
                    let teacherVal = teachersArr.filter(teach => subj.teacherId == parseInt(teach.teacherId))[0];
                    // console.log(subj.teacherId)
                    assignedSub += `<tr data-subject="${subj.subjectId}"  data-teacher="${teacherVal.teacherId}">
                        <td class="subjectName">${subjectName}</td><td>${teacherVal.teacherName}${sectSubReassignBtn}</td>
                        <td>${sectSubRemoveBtn}</td></tr>`
                });
            }

            resp.subjects.filter(subj => !assignedSubId.includes(subj.id)).forEach(subj => {
                unassignedSub += `<tr data-subject="${subj.id}"><td class="subjectName">${subj.subjectTitle}</td>
                    <td><button class="colCode btn btn-floating btn-small secAssBtn"><i class="material-icons">edit</i></button></td></tr>`
            });

            document.querySelector('#sectionSubTitle').textContent = sectionSubTitle;
            document.querySelector('#sectionAssignedSubjectsTable tbody').innerHTML = assignedSub;
            document.querySelector('#sectionUnassignedSubjectsTable tbody').innerHTML = unassignedSub;

            document.querySelector('#sectionAssignTableSection').classList.remove('hide');
            document.querySelector('#classTableSection').classList.add('hide');
            sectionModalObj.setAttribute('data-section', fetchObj.sectionId);
            deleteModalObj.setAttribute('data-section', fetchObj.sectionId);
            sectionModalObj.querySelector('#close').addEventListener('click', function (e) {
                e.preventDefault()
                sectionModal.close();
            })
            document.querySelectorAll('#sectionUnassignedSubjectsTable .secAssBtn').forEach(btn => assignSectionFxn(btn, sectionmodalConfig));
            document.querySelectorAll('#sectionAssignedSubjectsTable .reassignSect').forEach(btn => assignSectionFxn(btn, sectionmodalConfig));
            document.querySelectorAll('#sectionAssignedSubjectsTable .sectSubRemove').forEach(btn => deleteAssignFxn(btn));
        })
    })

    /*****************FUNCTION TO ASSIGN SUBJECT TO SECTION******************** */
    function assignSectionFxn(btn, modalFxn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            let currRow = btn.parentNode.parentNode;
            let modalConfigObj = {
                subjectId: currRow.getAttribute('data-subject'),
                subjectName: currRow.querySelector('.subjectName').innerHTML,
                currIndx: currRow.rowIndex,
            }
            console.log(modalConfigObj)
            modalFxn(modalConfigObj);

        })
    }

    /***********************CONFIGURE MODALS***************************** */
    function sectionmodalConfig(configObj) {
        sectionModalObj.setAttribute('data-index', configObj.currIndx);
        sectionModalObj.setAttribute('data-subject', configObj.subjectId);
        sectionModalObj.querySelector('.subjectName').textContent = configObj.subjectName;
        sectionModalObj.querySelector('#sectionAssignForm').reset();

        sectionModal.open();
    }

    function assignmodalConfig(configObj) {
        assignModalObj.setAttribute('data-index', configObj.currIndx);
        assignModalObj.setAttribute('data-subject', configObj.subjectId);
        assignModalObj.querySelector('.modal-content .subjectName').textContent = configObj.subjectName;
        assignModalObj.querySelector('.modal-content #classAssignForm').reset();
        assignModal.open();
    }

    function deleteModalConfig(configObj) {
        deleteModalObj.setAttribute('data-index', configObj.currIndx);
        deleteModalObj.setAttribute('data-subject', configObj.subjectId);
        deleteModalObj.setAttribute('data-name', configObj.subjectName);
        deleteModalObj.setAttribute('data-school', schoolId);
        deleteModalObj.querySelector('#confirmQuery').textContent = 'Are you sure you wish to delete this subject assignment?';
        deleteModal.open()
    }

    function deleteAssignFxn(btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault()
            let currRow = btn.parentNode.parentNode;
            let modalConfigObj = {
                subjectId: currRow.getAttribute('data-subject'),
                subjectName: currRow.querySelector('.subjectName').innerHTML,
                currIndx: currRow.rowIndex,
            }
            deleteModalConfig(modalConfigObj);
        })
    }

    /*****************FUNCITON TO CLOSE DELETE MODAL************************* */
    deleteModalObj && deleteModalObj.querySelector('#noConfirm').addEventListener('click', function (e) {
        e.preventDefault();
        deleteModal.close();
    })
    deleteModalObj && deleteModalObj.querySelector('#yesConfirm').addEventListener('click', function (e) {
        e.preventDefault();
        deleteModalObj.querySelector('.progress').classList.remove('hide');
        let deleteObj = {
            sessionId,
            subjectId: deleteModalObj.getAttribute('data-subject'),
            classId: document.querySelector('#classId').value,
            schoolId,
        }
        // console.log(deleteObj)
        AJAXJS(deleteObj, 'POST', '/subjects/class/remove', function (resp) {
            if (resp.updated) {
                M.toast({ html: '<h5>Subject removed successfully</h5>', classes: 'green' });
                document.querySelector('.progress').classList.add('hide');
                deleteModalObj.querySelector('.progress').classList.add('hide');
                let teachersDrop = document.querySelector('#unassignedSubjectsTable .teacherCell').innerHTML;
                let removeRow = deleteModalObj.getAttribute('data-index');
                document.querySelector('#assignedSubjectsTable').deleteRow(removeRow);
                deleteModal.close();

                let assignCount = parseInt(assignSpan.textContent) - 1;
                assignSpan.innerHTML = `${assignCount} `;

                let newRow = classSubjectAssignTable.insertRow(classSubjectAssignTable.rows.length);
                newRow.setAttribute('data-id', deleteObj.subjectId)
                newRow.setAttribute('id', `subject${deleteObj.subjectId}`)
                newRow.setAttribute('data-check', 'false');
                newRow.innerHTML = `<td><button class="grey btn btn-small btn-floating center"><i class="material-icons">check</i></button></td>
                <td class="subjectName">${deleteModalObj.getAttribute('data-name')}</td>
                <td class="teacherCell">${teachersDrop}</td>
                <td class="subjectTypeCell">
                    <select id="subjectTypeSelect" class="browser-default subjectTypeSelect">
                    <option value="">SUBJECT TYPE</option>
                    <option value="Compulsory">Compulsory</option>
                    <option value="Selective">Selective</option>
                    </select>
                </td>
                `
                // assignBtnAction(newRow.querySelector("button"));
                newRow.querySelector("button").addEventListener('click', function (e) {
                    e.preventDefault();
                    makeRadio(this);
                });

                console.log(newRow)
            } else {
                M.toast({ html: '<h5>Subject removal failed!</h5>', classes: 'red' });

            }
        })
    })


    /*************FUNCTION TO SUBMIT SECTION SUBJECT*************** */
    sectionAssignSubmitBtn && sectionAssignSubmitBtn.addEventListener('click', function (e) {
        e.preventDefault();
        let subjectId = sectionModalObj.getAttribute('data-subject');
        let selectedTeacher = sectionModalObj.querySelector('#teacherSelect');
        let selectedSubject = sectionModalObj.querySelector('.subjectName').innerHTML;
        let teacherId = selectedTeacher.value;
        if (!selectedTeacher.value == "") {
            document.querySelector('#showAssignModalError').classList.add('hide');
            sectionClassesObj.classArr = sectionClassesObj.classArr.map(croom => ({ ...croom, teacherId, subjectId, subjectType: 'Compulsory', students: 'All' }));
            sectionClassesObj.sectionObj = {
                sessionId, schoolId, subjectId, teacherId, sectionId: sectionModalObj.getAttribute('data-section')
            }
            // console.log(sectionClassesObj);
            AJAXJS(sectionClassesObj, 'POST', '/subjects/section/store', function (resp) {
                document.querySelector('.progress').classList.add('hide');

                if (resp.storeSuccess) {
                    M.toast({ html: "Subject Successfully Assigned", classes: "green" }, 3000)
                    let rowContent = `<td>${selectedSubject}</td>
                        <td>${selectedTeacher.selectedOptions[0].innerHTML}${sectSubReassignBtn}</td><td>${sectSubRemoveBtn}</td>`
                    let newRow = sectionAssignedTable.insertRow(sectionAssignedTable.rows.length);
                    newRow.setAttribute('data-teacher', teacherId);
                    newRow.setAttribute('data-subject', subjectId);
                    newRow.innerHTML = rowContent;
                    document.querySelector('#sectionUnassignedSubjectsTable').deleteRow(sectionModalObj.getAttribute('data-index'))
                    sectionModal.close();
                } else {
                    M.toast({ html: "Error Assigning Subject", classes: "red" }, 3000)
                }

            })
        } else {
            document.querySelector('#showAssignModalError').classList.remove('hide');
        }

    })

    /******************FUNCTION TO REASSIGN SECTION SUBJECTS************************** */
    sectReassign && sectReassign.forEach(btn => assignSectionFxn(btn))



    /***************FUNCTION TO SUBMIT SELECTED ORDER FOR SUBJECTS RESULT*******************/
    function packOrder() {
        let orderTable = document.querySelector('#orderTable tbody');
        let orderArr = [];
        orderTable.querySelectorAll('tr').forEach(row => {
            let rowObj = {
                id: row.querySelector('.subjectCell').getAttribute('data-id'),
                result_order: row.querySelector('.orderValue').value,
            }
            orderArr.push(rowObj);
        })
        return orderArr;
    }

});
/*****************END OF DOM CONTENT LOADED************************ */

/***************FUNCTION TO MAKE CHECK BUTTON RADIO*********** */
function makeRadio(btn) {
    let check = btn.parentElement.parentElement.getAttribute('data-check')
    if (check == 'true') {
        btn.parentElement.parentElement.setAttribute('data-check', 'false')
        btn.classList.remove('black');
        btn.classList.add('grey');
    } else {
        btn.parentElement.parentElement.setAttribute('data-check', 'true')
        btn.classList.remove('grey');
        btn.classList.add('black');
    }
}

/*****************AJAX FUNCTION TO SELECT THE CHOSEN CLASS, SESSION AND TERM FROM DB****************** */
function AJAXJS(formObj, actionMET, actionURL, successFxn) {
    document.querySelector('.progress').classList.remove('hide');
    let aj = new XMLHttpRequest();
    aj.open(actionMET, actionURL);
    aj.onload = () => {
        if (aj.status == 200) {
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

// export {AJAXJS};