document.addEventListener('DOMContentLoaded', function () {
    console.log('loaded');
    const classChoiceForm = {};
    const classChoiceDetails = {};
    let classLoadBtn = document.querySelector('#loadClassResults');
    let viewAllBtn = document.querySelector('#viewClassResults');
    let profileResultsBtn = document.querySelectorAll('.staffResultPicker');
    let classListLoadBtn = document.querySelector('#loadClassResultsList');
    let manageResultsLoadBtn = document.querySelector('#manageResultsLoadBtn');
    let schoolBadge = document.querySelector('#schoolBadge') ? document.querySelector('#schoolBadge').src : null;
    let schoolBadgeArr = schoolBadge ? schoolBadge.split('/') : null;
    // schoolBadgeArr.splice(schoolBadgeArr.length-1, 1, 'onitshaarch.jpg');
    let homepg = '';
    if (schoolBadgeArr) {
        for (let i = 0, len = schoolBadgeArr.length; i < len - 1; i++) { homepg += schoolBadgeArr[i] + '/' }
    }
    // let archLogo = schoolBadgeArr.toString().replace(',', '/');
    let archLogo = homepg + 'onitshaarch.jpg';
    let schoolName = document.querySelector('#schoolName') ? document.querySelector('#schoolName').textContent.toUpperCase() : null;
    let resultBtn = document.querySelectorAll('.stdResultBtn') ? document.querySelectorAll('.stdResultBtn') : null;
    let stdResultBtn = document.querySelector('#studentProfileResultBtn');
    let viewResultBtn = document.querySelectorAll('.viewResultBtn') ? document.querySelectorAll('.viewResultBtn') : null;
    let progressModal = document.querySelector('#progressModal');
    let resultCSS = `<style>
        body{ font-family: Bookman Old Style; }

        .resultSheet{ border:1px solid black; width:800px; height:1000px;
            page-break-after:auto; page-break-inside:avoid;
            padding-top:10px; border-radius:10px; margin:20px auto; }

        .schoolInfo{ width:800px; height:90px; border-bottom:1px solid black; margin-bottom:5px; border-radius:10px;}

        .schoolName{ margin:0 auto; width:600px; height:100px; text-align:center; padding:0px; font-weight:bold; font-size:18px; vertical-align:bottom;}

        .badge{ width:80px; height:100px; float:left; text-align:center; margin-left: 6px; }

        .archLogo{ width:80px; height:100px; float:right; text-align:center; }

        .resultSheet table{ margin:0 auto; border:1px solid black; width:780px; border-collapse:collapse;}

        .headerTable th{ text-align: left; font-size:13px;}

        .commentTable th, .commentTable td{ text-align: left; font-size:12px;}
        
        .subjectsDiv { transform: rotate(-90deg); width:75px; height:100%; text-align:left; padding:0px; }

        .subjectsDivTd { height:75px; max-width:30px; min-width:30px; border:1px solid; overflow:hidden; margin:0px; padding:0px; }
        
        .broadSubjectsDiv { transform: rotate(-90deg); width:180px; height:90%; text-align:left; padding:0px; }

        .broadSubjectsDivTd { height:190px; max-width:35px; min-width:35px; 
            border:1px solid white; overflow:hidden; margin:0px; padding:0px; font-size:12.5px;}
        
        .broadSubjectsDivTdAnnual { height:190px; max-width:80px; min-width:80px; 
            border:1px solid white; overflow:hidden; margin:0px; padding:0px; }

        #broadSheetTable{border:none; border-collapse:collapse; width:100%; height:100%; border-radius:10px; margin:20px auto; /*page-break-inside:avoid;*/}

        #broadSheetTable td{font-size:13px; border-collapse:collapse;}
        .midCell{text-align:center;}
        .botCell{vertical-align:bottom}

        .greyBack{background-color: #d7d7d7 !important;}

        .broadSchoolInfo{ width:100%; height:90px; margin-bottom:5px;
            background-color:white; border-radius: .4rem;
            border-bottom:.3rem solid #d7d7d7; border-top:.3rem solid #d7d7d7;
        }
        @media print{
            .btnDiv{display:none}
            #newsletterDiv{display:none}
        }
        #newsletterDiv{
            margin: 20px auto;
            text-align:center;
        }
        .embedContainer{
            padding: 20px 0px;
        }
        .btnDiv{background-color:white; border-bottom: .2rem solid black; width:100%; height: auto;margin-bottom:2rem;}
        #printBtn{border-radius:0.4rem; background-color:none; height:2rem;}
        #printBtn:hover{background-color: #d7d7d7;}
        .nameCell{white-space: nowrap;}
        .termTitle{margin-top: 5px}; 
        .termTitle td {border: 1px solid black}
        </style>`;

    /**************Button Click to load the BroadSheet Results ******************** */
    classLoadBtn && classLoadBtn.addEventListener('click', function (e) {
        e.preventDefault();
        let checkLoad = loadData();
        document.querySelector('.progress').classList.remove('hide');

        if (checkLoad) {
            document.querySelector('#resultListTable').classList.add('hide');
            if (classChoiceForm.termId == '4') {
                AJAXJS(classChoiceForm, 'POST', `/class/result/${classChoiceForm.classId}`, fillBroadsheet);
            } else {
                AJAXJS(classChoiceForm, 'POST', `/class/result/${classChoiceForm.classId}`, fillBroadsheet);
            }
        }
    });
    /***************END OF BUTTON FOR STUDENT LOAD**************** */

    /****************BUTTON CLICK ACTION TO VIEW ALL STUDENTS rESULTS************************** */
    viewAllBtn && viewAllBtn.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector('.progress').classList.remove('hide');
        let checkLoad = loadData();

        if (checkLoad) {
            AJAXJS(classChoiceForm, 'POST', `/class/result/${classChoiceForm.classId}`, showClassResultTab);
        }
    })


    /*************VIEW RESULT FROM STUDENT DETAILS PAGE************* */
    viewResultBtn && viewResultBtn.forEach(btn =>
        btn.addEventListener('click', e => {
            e.preventDefault();
            // console.log('dasf');
            let sessId = btn.dataset.session;
            let clsId = btn.dataset.cls;
            let stdId = btn.dataset.id;
            classChoiceForm.classId = clsId;
            classChoiceForm.sessionId = sessId;
            classChoiceForm.schoolId = document.querySelector('#schoolId').value;
            classChoiceForm.termId = document.querySelector(`#termIdSelect${sessId}`).value;

            classChoiceDetails.class = document.querySelector(`#sess${sessId} .classTitle`).textContent;
            classChoiceDetails.session = document.querySelector(`#sess${sessId} .sessName`).textContent;
            classChoiceDetails.term = document.querySelector(`#sess${sessId} #termIdSelect${sessId}`).selectedOptions[0].innerHTML;

            // document.querySelector(`#card${sessId} .progress`).classList.remove('hide');
            // if(classChoiceForm.termId == '4'){
            //     // Build Result for Annual
            //     AJAXJS(classChoiceForm, 'POST', `/student/result/${stdId}`, showResultTab);
            // }
            AJAXJS(classChoiceForm, 'POST', `/student/result/${stdId}`, showResultTab);
        })
    )


    /*********************BUTTON CLICK FOR RESULTS FROM STAFF PROFILE********************* */
    profileResultsBtn && profileResultsBtn.forEach(btn => btn.addEventListener('click', function (e) {
        e.preventDefault();
        classChoiceDetails.class = btn.parentElement.querySelector('#classTitle').textContent;
        classChoiceDetails.session = document.querySelector('#currentSession').textContent;
        classChoiceDetails.term = document.querySelector('#currentTerm').textContent;
        let classResultObj = {
            classId: btn.getAttribute('data-class'),
            sessionId: document.querySelector('#sessionId').value,
            termId: document.querySelector('#termId').value,
            schoolId: document.querySelector('#schoolId').value,
        }
        AJAXJS(classResultObj, 'POST', `/class/result/${classResultObj.classId}`, showClassResultTab);
    }))

    /**************Button Click to load the Students List ******************** */
    //button click action for fetching students list for selected variables
    classListLoadBtn && classListLoadBtn.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector('.progress').classList.remove('hide');
        let checkLoad = loadData();

        if (checkLoad) {
            document.querySelector('#studentResultListTable').classList.add('hide');
            AJAXJS(classChoiceForm, 'POST', `/results/students/${classChoiceForm.classId}`, fillResultTable);
        }
    });
    /***************END OF BUTTON FOR STUDENT LOAD**************** */

    //button click action for fetching students list for selected variables
    manageResultsLoadBtn && manageResultsLoadBtn.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector('.progress').classList.remove('hide');
        let checkLoad = loadData();

        if (checkLoad) {
            document.querySelector('#studentResultListTable').classList.add('hide');
            AJAXJS(classChoiceForm, 'POST', `/results/students/${classChoiceForm.classId}`,
                (res) => fillResultTable(res, true));
        }
    });
    /***************END OF BUTTON FOR STUDENT LOAD**************** */

    /***************FUNCTION TO LOAD DATA********************** */
    function loadData() {
        // GET THE VARIABLES TO SELECT A CLASS FOR SCORE ENTRY
        classChoiceForm.classId = document.querySelector('#classId').value;
        classChoiceForm.sessionId = document.querySelector('#sessionIdSelect').value;
        classChoiceForm.termId = document.querySelector('#termIdSelect').value;
        classChoiceForm.schoolId = document.querySelector('#schoolId').value;

        classChoiceDetails.class = document.querySelector('#classId').selectedOptions[0].innerHTML;
        classChoiceDetails.session = document.querySelector('#sessionIdSelect').selectedOptions[0].innerHTML;
        classChoiceDetails.term = document.querySelector('#termIdSelect').selectedOptions[0].innerHTML;

        // CHECK THE SELECTED VARIABLES (CLASS, SESSION, TERM, SUBJECT) AND THROW ERROR
        for (const [key, value] of Object.entries(classChoiceForm)) {
            if (value == '') {
                alert(`Please Select a ${key.substring(0, key.length - 2).toUpperCase()}`);
                return false;
            }
        }
        return true;
    }

    /***************FUNCTION TO FILL RESULT CELLS IN BROADSHEET*************** */
    function resultCellsFxn(id, results, subjects, allScores) {
        let resultCells = ``;

        if (classChoiceForm.termId == '4') {
            // let students = results.map(({id}) => id).sort((a,b)=>a-b);
            // console.log(students);
            // students.forEach( stud => {
            let studScores = allScores.filter(score => score.student_id == id), totalScore = 0;
            console.log(studScores)
            subjects.forEach(subj => {
                let allSub = studScores.filter(sco => sco.subject_id == subj.id), proSub = {};
                if (allSub.length > 0) {
                    // allSub = allSub.reduce((acc, val)=>{
                    //     return {...acc, [val.term_id]:[val.TOTAL]}
                    // }, {})
                    allSub.forEach(val => {
                        if (val.term_id == '1') { proSub[1] = val.TOTAL; totalScore += +val.TOTAL; }
                        if (val.term_id == '2') { proSub[2] = val.TOTAL; totalScore += +val.TOTAL; }
                        if (val.term_id == '3') { proSub[3] = val.TOTAL; totalScore += +val.TOTAL; }
                    })
                } else {
                    proSub = { 1: '-', 2: '-', 3: '-' };
                }
                resultCells += `
                    <td class='midCell'>
                        <table>
                            <tr>
                                <td style='width:20; text-align:center;'>${proSub['1'] == undefined ? '-' : proSub['1']}</td>
                                <td style='width:20; text-align:center;'>${proSub['2'] == undefined ? '-' : proSub['2']}</td>
                                <td style='width:20; text-align:center;'>${proSub['3'] == undefined ? '-' : proSub['3']}</td>
                                <td style='width:20; text-align:center;'>${totalScore}</td>
                                <td style='width:20; text-align:center;'>${(totalScore / Object.values(proSub).length).toFixed(2)}</td>
                            </tr>
                        </table>
                    </td>`;
                totalScore = 0;
            })

            // })
            // console.log(resultCells);
            return resultCells;
        }
        let studentResults = results.filter(result => result.student_id == id);
        // let studentTotal = 0;
        let offeredSubjects = studentResults.map(result => result.subject_id);
        // studentResults.forEach(result=> studentTotal += parseInt(result.TOTAL) );
        // let studentAverage = (studentTotal / offeredSubjects.length).toFixed(2);

        for (const subject of subjects) {
            if (!offeredSubjects.includes(subject.id)) {
                resultCells += `<td class='midCell'>-</td>`
            }
            for (const result of studentResults) {
                if (subject.id == result.subject_id) {
                    resultCells += `<td class='midCell'>${result.TOTAL}</td>`
                }
            }
        }
        return resultCells;
    }

    /***********annual Broadsheet Compute********** */
    const annualBroad = () => {
        // Build result body for annual result
        let stdIdSet = new Set(classResults.map(({ student_id }) => student_id));
        let subIdSet = new Set(classResults.map(({ subject_id }) => subject_id));
        let annualResultArr = [];
    }


    /***********FUNCTION TO HELP SORT ARRAY OF STUDENTS LIST WITH TOTAL********* */
    const totalSort = (student1, student2) => {
        if (parseFloat(student1.TOTAL) > parseFloat(student2.TOTAL)) {
            return -1;
        } else if (parseFloat(student1.TOTAL) < parseFloat(student2.TOTAL)) {
            return 1;
        } else {
            return 0;
        }
    };
    /*************** */

    /***********FUNCTION TO HELP SORT STUDENT POSITIONS IN ARRAY OF STUDENTS LIST********* */
    const positionSort = sortedResults => {
        sortedResults[0].Position = 1;
        // let position = 1;

        for (let i = 0; i < sortedResults.length; i++) {
            if (i == sortedResults.length - 1) {
                break;
            }
            if (sortedResults[i].Average > sortedResults[i + 1].Average) {
                sortedResults[i].Position = sortedResults[i].Position;
                sortedResults[i + 1].Position = ++sortedResults[i].Position;
            } else {
                sortedResults[i].Position = sortedResults[i].Position;
                sortedResults[i].Position = sortedResults[i].Position;
            }
        }
    };

    /***************FILL UP BROADSHEET TABLE ********************* */
    function fillBroadsheet(resultResponse) {
        let subjectTitles = ``;
        let [overAllResult, positionedRes] = resultCalc(resultResponse.overAllScores, resultResponse.resultRes)
        resultResponse.subjectRes.forEach(sub => subjectTitles += `
            ${classChoiceForm.termId == '4' ? `
                <th style='vertical-align:bottom'><div>${sub.title}
                <table class='termTitle'>
                    <tr>
                        <td width='20'>1st</td>
                        <td width='20'>2nd</td>
                        <td width='20'>3rd</td>
                        <td width='20'>Tot</td>
                        <td width='20'>Avg</td>
                    </tr>
                </table></div></th>` : `<th class='${sub.id} broadSubjectsDivTd'>
                <div class='broadSubjectsDiv'>${(sub.title).toUpperCase()}</div>`}
                </th>`);
        // let positionedRes = buildResult(resultResponse.resultRes, classChoiceForm.termId);
        let tableHead = `<tr class='greyBack'>
                        <th class='botCell'>S/No.</th><th class='botCell'>STUDENT</th>
                        ${subjectTitles}
                        <th class='broadSubjectsDivTd'>
                        <div class='broadSubjectsDiv'>TOTAL</div></th>
                        <th class='broadSubjectsDivTd'><div class='broadSubjectsDiv'>AVERAGE</div></th>
                        <th class='broadSubjectsDivTd'><div class='broadSubjectsDiv'>CLASS POSITION</div></th>
                        <th class='broadSubjectsDivTd'><div class='broadSubjectsDiv'>OVERALL POSITION (${overAllResult[1]})</div></th></tr>`;
        let broadRows = ``;
        let snum = 1;
        resultResponse.studentRes.forEach(student => {
            let studentPositionedRes = positionedRes.filter(res => res.id == student.id);
            let overallBuild = overAllResult[0].filter(res => res.id == student.id);
            let stdOverPos = suffixer(overallBuild[0].Position);
            let resultCells = classChoiceForm.termId == '4' ?
                resultCellsFxn(student.id, resultResponse.studentRes, resultResponse.subjectRes, resultResponse.classScores) :
                resultCellsFxn(student.id, resultResponse.resultRes, resultResponse.subjectRes, []);
            broadRows += `<tr class='${snum % 2 == 0 ? 'greyBack' : ''}'>
                    <td>${snum++}</td>
                    <td class='nameCell'>${(student.lastName + ' ' + student.otherName + ' ' + student.firstName).toUpperCase()}<br>
                        <span style='font-size:9px;float:right;'>${student.regNo}</span>
                    </td>
                    ${resultCells}
                    <td>${studentPositionedRes[0].Total.toFixed(2)}</td>
                    <td>${studentPositionedRes[0].Average.toFixed(2)}</td>
                    <td>${suffixer(studentPositionedRes[0].Position)}</td>
                    <td>${stdOverPos}</td>
                    </tr>`
        });

        // document.querySelector('#resultListTable thead').innerHTML = tableHead;
        // document.querySelector('#resultListTable tbody').innerHTML = broadRows;
        document.querySelector('.progress').classList.add('hide');
        // document.querySelector('#resultListTable').classList.remove('hide');

        let broadSheetTab = window.open("", "window=200,height=100");
        broadSheetTab.focus();
        broadSheetTab.document.head.innerHTML += resultCSS;
        broadSheetTab.document.body.innerHTML = `
            <div style='margin: 0 auto;'>
                <div class='btnDiv'><button id='printBtn'>PRINT</div>
                <div class='broadSchoolInfo'>
                    <div class='badge'><img width='80' src=${schoolBadge}></div>
                    
                    <div class='schoolName'>
                       
                        <span>${schoolName}</span><br>
                        <span>CLASS:: ${classChoiceDetails.class}</span><br>
                        <span>${classChoiceDetails.term.trim()}, ${classChoiceDetails.session} Broadsheet</span>
                    </div>
                </div>
                <table id='broadSheetTable'  border='1'>
                    <thead>${tableHead}</thead>
                    
                    <tbody>${broadRows}</tbody>
                </table>
            </div>
        `;
        // console.log(buildResult(resultResponse.resultRes, resultResponse.studentRes))
        broadSheetTab.document.querySelector('#printBtn').onclick = () => broadSheetTab.print();
    }


    /****************FUNCTION TO FILL THE STUDENT LIST TABLE****************** */
    function fillResultTable(resultResponse, manage = false) {
        let resultTableRows = ``;
        let numb = 1;
        resultResponse.studentRes.forEach(student => {
            resultTableRows += `<tr><td>${numb++}</td><td>${student.regNo}</td>
                    <td>${(student.lastName + ' ' + student.otherName + ' ' + student.firstName).toUpperCase()}</td>
                    <td><button id=${student.id} class="btn btn-floating colCode studentResult">
                        <i class="material-icons">pageview</i></button>
                        ${manage ? `<button id=${student.id} class="btn btn-floating colCode deleteResult">
                        <i class="material-icons">delete</i></button>` : ''}    
                    </td>
                    </tr>`
        })

        document.querySelector('#studentResultListTable tbody').innerHTML = resultTableRows;
        document.querySelector('.progress').classList.add('hide');
        document.querySelector('#studentResultListTable').classList.remove('hide');

        /****************FUNCTION TO BUILD INDIVIDUAL STUDENT RESULT************ */
        document.querySelectorAll('.studentResult').forEach(button => {
            let stdId = button.getAttribute('id');
            button.addEventListener('click', () => { buildStudentResult(stdId); });
        })

        let deleteBtns = document.querySelectorAll('.deleteResult');
        /****************FUNCTION TO DELETE INDIVIDUAL STUDENT RESULT************ */
        deleteBtns && deleteBtns.forEach(button => {
            let stdId = button.getAttribute('id');

            button.addEventListener('click', () => {

                let passwordModal = document.querySelector('#deleteResultModal');
                passwordModal.setAttribute('data-student', stdId);
                M.Modal.getInstance(passwordModal).open();

            });
        })


    }

    let confirmDeleteBtn = document.querySelector('#confirmDeleteBtn');

    confirmDeleteBtn && confirmDeleteBtn.addEventListener('click', function () {

        let passwordModal = document.querySelector('#deleteResultModal');

        let adminPassword = document.querySelector('#deleteResultModal .modal-content input[name="adminpassword"]');
        let stdId = passwordModal.getAttribute('data-student');

        if (adminPassword.value == '') {
            alert('Please enter password!');
            return;
        }

        classChoiceForm.password = adminPassword.value;

        M.Modal.getInstance(progressModal).open();
        progressModal.querySelector('.progress').classList.remove('hide');

        AJAXJS(classChoiceForm, 'POST', `/delete/result/${stdId}`, (res) => {
            M.Modal.getInstance(progressModal).close();
            adminPassword.value = "";

            if (res.deleted) {
                M.toast({ html: "<h5>Delete Successful!", classes: "white green-text" }, 3000);
                M.Modal.getInstance(passwordModal).close();
            }
            else if (res.unauthorized) {
                M.toast({ html: "<h5>Incorrect Password!", classes: "white red-text" }, 3000);
            }
            else {
                M.toast({ html: "<h5>Delete Failed!", classes: "white red-text" }, 3000);
            }
        });

    })


    /********************* FUNCTION TO VIEW STUDENTS RESULT FROM PROFILE******************* */
    resultBtn && resultBtn.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            let sessId = btn.getAttribute('data-session');
            let clsId = btn.getAttribute('data-class');
            let stdId = btn.getAttribute('data-id');
            classChoiceForm.classId = clsId;
            classChoiceForm.sessionId = sessId;
            classChoiceForm.termId = document.querySelector(`#termIdSelect${sessId}`).value;

            classChoiceDetails.class = document.querySelector(`#card${sessId} #classDesc`).textContent;
            classChoiceDetails.session = document.querySelector(`#card${sessId} #sessName`).textContent;
            classChoiceDetails.term = document.querySelector(`#card${sessId} #termIdSelect${sessId}`).selectedOptions[0].innerHTML;

            document.querySelector(`#card${sessId} .progress`).classList.remove('hide');
            // if(classChoiceForm.termId == '4'){
            //     // Build Result for Annual
            //     AJAXJS(classChoiceForm, 'POST', `/student/result/${stdId}`, showResultTab);
            // }
            AJAXJS(classChoiceForm, 'POST', `/student/result/${stdId}`, showResultTab);
        })
    })

    stdResultBtn && stdResultBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const chooseCls = document.querySelector('#resChooseClass');
        const stdId = stdResultBtn.dataset.stdid;
        classChoiceForm.classId = chooseCls.selectedOptions[0].dataset.cls;
        classChoiceForm.sessionId = chooseCls.value;
        classChoiceForm.termId = document.querySelector('#resChooseTerm').value;
        classChoiceForm.schoolId = document.querySelector('#schoolId').value;

        classChoiceDetails.class = chooseCls.selectedOptions[0].innerHTML.trim();
        classChoiceDetails.session = chooseCls.selectedOptions[0].dataset.session;
        classChoiceDetails.term = document.querySelector('#resChooseTerm').selectedOptions[0].innerHTML;
        // console.log(classChoiceDetails, classChoiceForm)
        const progModal = document.querySelector('#progressModal');
        M.Modal.getInstance(progModal).open();
        progModal.querySelector('.progress').classList.remove('hide');

        AJAXJS(classChoiceForm, 'POST', `/student/result/${stdId}`, showResultTab);

    })


    /****************FUNCTION TO BUILD INDIVIDUAL STUDENT RESULT************ */
    function buildStudentResult(studentId) {
        document.querySelector('.progress').classList.remove('hide');
        M.Modal.getInstance(progressModal).open();
        progressModal.querySelector('.progress').classList.remove('hide');

        AJAXJS(classChoiceForm, 'POST', `/student/result/${studentId}`, showResultTab);
    }

    /*******************
     * FUNCTION THAT CALCULATES OVERALL AND CLASS RESULTS BY CALLING THE buildResult FXN FOR BOTH **
     * CALLED ON THE showResultTab fxn and showClassResultTab fxn
     * ***************** */
    function resultCalc(overAll, resultRes) {
        let overallArr = overAll.reduce((acc, item) => { return [...acc, ...item] }, []);
        let overAllResult = (() => {
            let idArr = overallArr.map(({ student_id }) => student_id);
            let idSet = new Set(idArr);
            let buildRes = buildResult(overallArr, classChoiceForm.termId);
            return [buildRes, idSet.size];
        })()
        let positionedRes = buildResult(resultRes, classChoiceForm.termId);
        // console.log(positionedRes);
        return [overAllResult, positionedRes];
    }


    /********
     * CALLBACK FXN THAT BUILDS THE FINAL RESULT SHEET FOLLOWING AJAX CALL **
     * 
     * ************* */
    function showResultTab(resp) {

        M.Modal.getInstance(progressModal).close();

        if (resp.selectedStudent.length == 0) {
            // This works on the student profile view result
            let modInsta = document.querySelector('#noResultModal');
            modInsta && M.Modal.init(modInsta).open();
            document.querySelectorAll('.progress').forEach(pro => pro.classList.add('hide'));

            M.toast({ html: "<h5>No result found!</h5>", classes: "red white-text" }, 3000);

            return;
        }

        // document.querySelector('.progress').classList.add('hide');
        document.querySelectorAll('.progress').forEach(pro => pro.classList.add('hide'));
        const progModal = document.querySelector('#progressModal');
        progModal && M.Modal.getInstance(progModal).close();

        let resultTab = window.open("", "window=200,height=100");
        resultTab.focus();
        let resultSet = resultCalc(resp.overAllScores, resp.resultRes);
        // console.log(resultSet)
        let finalResultSheet = resultSheetFunction(resp, resultSet[1], resultSet[0]);

        // const resultComments = buildResultComments(resp.studentComments);
        resultTab.document.head.innerHTML += resultCSS;
        resultTab.document.body.innerHTML = finalResultSheet;
        // console.log(resp)
    }

    /****************FUNCTION TO VIEW ALL CLASS STUDENTS rESULTS**************** */
    function showClassResultTab(resp) {
        if (resp.studentRes.length == 0) {
            let modInsta = document.querySelector('#noResultModal');
            profileResultsBtn && M.Modal.init(modInsta).open();
            document.querySelectorAll('.progress').forEach(pro => pro.classList.add('hide'));
            return;
        }

        document.querySelector('.progress').classList.add('hide');

        let resultTab = window.open("", "window=200,height=100");
        resultTab.focus();

        let finalResultSheet = classResultSheet(resp);

        // const resultComments = buildResultComments(resp.studentComments);
        resultTab.document.head.innerHTML += resultCSS;
        resultTab.document.body.innerHTML = finalResultSheet;
        // console.log(resp)


    }

    /****************FUNCTION TO BREAK LONG TABLE HEADINGS*********************** */
    function theadBreak(thText) {
        let thArray = thText.split(' ');
        let thProper = ``;
        for (let i = 0; i < thArray.length; i++) {
            if (thArray[i].length > 7) {
                let line1 = thArray[i].substring(0, 6);
                let line2 = thArray[i].substring(6);
                thProper += `${line1}-<br>${line2} `;
            } else {
                thProper += `${thArray[i]} `;
            }
        }

        return thProper;
    }

    /************FUNCTION TO BUILD CLASS RESULT SHEET********************* */
    function classResultSheet(classResponse) {
        let classResult = ``;
        let resultBuildObj = {
            studentRes: classResponse.studentRes,
            subjectRes: classResponse.subjectRes,
            resultRes: classResponse.resultRes,
            overAllScores: classResponse.overAllScores,
            assessFormat: classResponse.assessFormat,
            schoolTraits: classResponse.schoolTraits,
            traitRating: classResponse.traitRating,
            teacherDetails: classResponse.teacherDetails,
            gradingFormat: classResponse.gradingFormat,
            resumeDate: classResponse.resumeDate,
            shw: classResponse.shw
        };


        let resultSet = resultCalc(classResponse.overAllScores, classResponse.resultRes);

        classResponse.studentRes.forEach(student => {
            resultBuildObj.selectedStudent = classResponse.classScores.filter(score => score.student_id == student.id);
            resultBuildObj.studentTraits = classChoiceForm.termId != '4' ? classResponse.classTraits.filter(trait => trait.student_id == student.id) : null;
            resultBuildObj.studentComments = classResponse.classComments.filter(comment => comment.student_id == student.id);
            classResult += resultSheetFunction(resultBuildObj, resultSet[1], resultSet[0]);
        });

        return classResult;
    }

    /***********RUNCTION TO BUIILD FINAL RESULT SHEET******************** */
    function resultSheetFunction(resp, positionedRes, overAllResult) {


        let studentDetails = resp.studentRes.filter(student => student.id == resp.selectedStudent[0].student_id);
        // console.log(studentDetails);
        let studentPositionedRes = positionedRes.filter(res => res.id == studentDetails[0].id);
        let stdPos = suffixer(studentPositionedRes[0].Position);
        let overallBuild = overAllResult[0].filter(res => res.id == studentDetails[0].id);
        let stdOverPos = suffixer(overallBuild[0].Position);
        let studentTotal = studentPositionedRes[0].Total;
        let studentAverage = studentPositionedRes[0].Average.toFixed(2);
        let marksObtain = studentPositionedRes[0].MarksObtainable;
        let passOrFail = '-';
        let checkAnnual = classChoiceForm.termId == '4';


        let h1, h2, w1, w2, shw;
        if (resp.shw.length) {
            shw = resp.shw[0]
            h1 = shw.h1 ? shw.h1 + "cm" : "N/A";
            h2 = shw.h2 ? shw.h2 + "cm" : "N/A";
            w1 = shw.w1 ? shw.w1 + "kg" : "N/A";
            w2 = shw.w2 ? shw.w2 + "kg" : "N/A";
        } else {
            h1 = "N/A";
            h2 = "N/A";
            w1 = "N/A";
            w2 = "N/A";
        }

        if (resp.studentComments[0]) {
            passOrFail = !checkAnnual ?
                (resp.studentComments[0].passOrFail ? resp.studentComments[0].passOrFail : '-') : resp.studentComments[0].promotedOrNotPromoted
        }

        let resumptionDate = !resp.resumeDate ? '-' : resp.resumeDate.length != 0 ? resp.resumeDate[0].startDate : '-';
        let studentName = `${studentDetails[0].lastName} ${studentDetails[0].firstName} ${studentDetails[0].otherName}`
        let resultHeader = `<table class='headerTable' border='1' style='font-size:13px;'>
                        <tr>
                            <th>NAME:</th>
                            <td>${studentName.toUpperCase()}</td>
                            <th>CLASS:</th>
                            <td>${classChoiceDetails.class}</td>
                        </tr>

                        <tr>
                            <th>TOTAL SCORE:</th>
                            <td>${studentTotal.toFixed(2)}</td>
                            <th>AVERAGE:</th>
                            <td>${studentAverage}</td>
                        </tr>

                        <tr>
                            <th>H1:</th><td>${h1}</td>
                            <th>H2:</th><td>${h2}</td>
                        </tr>

                        <tr>
                            <th>W1:</th><td>${w1}</td>
                            <th>W2:</th><td>${w2}</td>
                        </tr>

                        <tr>
                            <th>SESSION:</th><td>${classChoiceDetails.session}</td><th>CLASS POSITION:</th>
                            <td>${stdPos}</td>
                        </tr>

                        <tr>
                            <th>TERM:</th><td>${classChoiceDetails.term}</td><th>NO. IN CLASS</th>
                            <td>${positionedRes.length}</td>
                        </tr>

                        <tr>
                            <th>NEXT TERM BEGINS:</th><td>${resumptionDate}</td>
                            <th>OVERALL POSITION:</th><td>${stdOverPos}/${overAllResult[1]}</td>
                        </tr>

                        <tr>
                            <th>PASS OR FAIL:</th><td colspan='3'>${passOrFail}</td>
                        </tr>
        </table>`;

        let assessHead = ``;

        if (checkAnnual) {
            // Result Header for Annual Result
            assessHead = '<th>First Term<br>(100%)</th><th>Second Term<br>(100%)</th><th>Third Term<br>(100%)</th>'
        } else {
            // Result Header for Termly Results
            resp.assessFormat.forEach(assess => { assessHead += `<th>${theadBreak(assess.name)}<br>(${assess.percentage})</th>` });
        }

        let resultBody = buildResultBody(resp.subjectRes, resp.selectedStudent, resp.resultRes, resp.assessFormat, resp.gradingFormat);
        let gradeFooter = `Grade Details:: `;
        resp.gradingFormat.forEach(grade => { gradeFooter += `${grade.description}=(${grade.minScore}-${grade.maxScore})  &nbsp;` });

        const resultTableHead = `<table border='1' style='font-size:11px;text-align:center;border-radius:5px;'>
                                <tr><th style='font-size:16px;'>Subject</th>${assessHead}<th>${checkAnnual ? 'Average' : 'Total'}<br>(100)</th>
                                <th>Grade</th>
                                <th>Comment</th><th>Subject Teacher's Signature</th>${resultBody}
                                <tfoot><tr><th colspan='100%' style='text-align:left;'>${gradeFooter}</th></tr></tfoot>
                                </table>`;

        const traitResult = classChoiceForm.termId != '4' ? buildTraitResult(resp.schoolTraits, resp.studentTraits, resp.traitRating) : '';
        let newsDiv = '';
        let hasNewsletter = resp.newsletter && resp.newsletter.length > 0;
        hasNewsletter ?
            resp.newsletter.forEach(news =>
                newsDiv += `<div class="embedContainer">
                    <embed src="${homepg + news.file_name}#toolbar=1&navpanes=1&statusbar=1" style="width:750px;height:750px;"></div>`
            ) : '';


        let finalResultSheet = `<div class='resultSheet'>
                                    <div class='schoolInfo'>
                                        <div class='badge'><img width='80' src=${schoolBadge}></div>
                                        
                                        <div class='schoolName'>
                                           
                                            <span>${schoolName}</span><br>
                                            <span>${classChoiceDetails.term}, ${classChoiceDetails.session} Student Result</span><br>
                                        </div>
                                    </div>
                                    ${resultHeader}${resultTableHead}<br>${traitResult}
                                    <br>${buildResultComments(resp.studentComments[0], resp.teacherDetails)}</div>
                                    ${hasNewsletter ?
                `<div id="newsletterDiv">
                                            <h4>Newsletter</h4>
                                            ${newsDiv}
                                            </div>`
                : ''}
                                    `;
        return finalResultSheet;
    }

    /***************FUNCTION TO BUILD TRAIT ASSESSMENT FOR RESULT*************** */
    function buildTraitResult(schoolTraits, studentTraits, traitRating) {
        let studentTraitObj = studentTraits.length !== 0 ? JSON.parse(studentTraits[0].traitRating) : null;
        let traitNames = `<th>BEHAVIOURAL TRAITS</th>`;
        schoolTraits.forEach(trait => traitNames += `<th class='subjectsDivTd'><div class='subjectsDiv'>${trait.name}</div></th>`)
        // let traitTableHead = `<tr>${traitNames}</tr>`;
        let traitCells = `<th>RATING</th>`;
        if (studentTraits.length !== 0) {
            for (const val of Object.values(studentTraitObj)) {
                traitCells += val ? `<td>${val}</td>` : `<td>-</td>`;
            }
        } else {
            schoolTraits.forEach(trait => { traitCells += `<td>-</td>` });
        }

        let traitFooter = `Rating Details:: `;
        traitRating.forEach(rating => { traitFooter += `${rating.description}=${rating.rating}  &nbsp;` });

        let traitTable = `<table border='1' style='border-collapse:collapse;font-size:11px;text-align:center;'>
                <tr>${traitNames}</tr><tr>${traitCells}</tr>
                <tfoot><tr><th colspan='100%' style='text-align:left;'>${traitFooter}</th></tr></tfoot>
                </table>`;
        return traitTable;
    }

    /***************FUNCTION TO BUILD COMMENTS TABLE****************** */
    function buildResultComments(comments, teachers) {
        let formTeacherComment = `-`;
        let headTeacherComment = `-`;
        if (comments) {
            formTeacherComment = comments.formTeacherComment ? comments.formTeacherComment : '-';
            headTeacherComment = comments.headTeacherComment ? comments.headTeacherComment : '-';
        }
        let formTeacher = teachers.formTeacher[0] ? (teachers.formTeacher[0].lastName + ' ' + teachers.formTeacher[0].firstName) : '';
        let commentsTable = `<table border='1' class='commentTable' style='font-size:11px;'>
                            <tr><th>Form Teacher</th><td>${formTeacher}</td><th>Form Teacher's Remark</th>
                            <td>${formTeacherComment}</td></tr>
                            <tr><th>Head Teacher</th><td>${teachers.schoolHead}</td><th>Head Teacher's Remark</th>
                            <td>${headTeacherComment}</td></tr>
                            <tr><th>Head Teacher's Signature</th>
                            <td colspan='3' class='center'><img height='50' width='200' src='${homepg + teachers.schoolHeadSign}'></td></tr>
                            </table>`;
        return commentsTable;
    }

    /***************FUNCTION TO BUILD THE BODY OF THE STUDENT RESULT TABLE***************** */
    function buildResultBody(subjects, studentResult, classResults, assessFormat, gradingFormat) {
        let resBody = ``;
        if (classChoiceForm.termId == '4') {
            // Build result body for annual result
            let stdIdSet = new Set(classResults.map(({ student_id }) => student_id));
            let subIdSet = new Set(classResults.map(({ subject_id }) => subject_id));
            let annualResultArr = [];

            // Calculate the total(average) for each subject per student for calculating student's subject position
            stdIdSet.forEach(student => {
                subIdSet.forEach(subject => {
                    let total = 0;
                    let subjAllTerms = classResults.filter(res => res.subject_id == subject && res.student_id == student);
                    subjAllTerms.forEach(term => total += parseInt(term.TOTAL));
                    let annualAverage = total / subjAllTerms.length;
                    annualResultArr.push({ student_id: student, subject_id: subject, TOTAL: annualAverage });
                })
            })

            // Rearrange the student result to annual format with subject as key
            const annualResObj = studentResult.reduce((acc, res) => {
                if (!acc[res.subject_id]) return { ...acc, [res.subject_id]: { [res.term_id]: res } }
                return { ...acc, [res.subject_id]: { ...acc[res.subject_id], [res.term_id]: res } }
            }, {})

            // console.log(annualResultArr)
            // Build the Annual Result Table
            for (const [key, subject] of Object.entries(annualResObj)) {
                let classSubjRes = annualResultArr.filter(subjRes => subjRes.subject_id == key).sort(totalSort);
                let posRes = positioner(classSubjRes, 'TOTAL')
                let studentSubjPos = suffixer(posRes.filter(position => position.student_id == studentResult[0].student_id)[0].Position);

                let subj = subjects.filter(subj => subj.id == key)[0];
                let term1 = subject[1] ? +subject[1].TOTAL : 0;
                let term2 = subject[2] ? +subject[2].TOTAL : 0;
                let term3 = subject[3] ? +subject[3].TOTAL : 0;
                let annualAverage = ((term1 + term2 + term3) / Object.values(subject).length).toFixed(1);
                let grade = subject ?
                    gradingFormat.filter(grade => annualAverage >= parseFloat(grade.minScore) && annualAverage <= parseFloat(grade.maxScore))[0] : null;
                let assessCells = `<tr><th style='text-align:left;'>${subj ? subj.title : '-'}</th>`;
                assessCells += `
                    <td>${subject[1] ? term1 : '-'}</td>
                    <td>${subject[2] ? term2 : '-'}</td>
                    <td>${subject[3] ? term3 : '-'}</td>
                    <td>${annualAverage}</td>
                    <td>${grade ? grade.grade : '-'}</td><td>${grade ? grade.description : '-'}</td>
                    <td>${subj ? (subj.signature ?
                        `<img src="${homepg + subj.signature}" style="height:14px;width:81px;" >`
                        : '-') : '-'}</td></tr>`;
                resBody += assessCells;
            }
            return resBody;
        }
        studentResult.forEach(res => {
            let classSubjRes = classResults.filter(subjRes => subjRes.subject_id == res.subject_id).sort(totalSort);
            let posRes = positioner(classSubjRes, 'TOTAL')
            // console.log(posRes)
            // console.log(posRes.filter(position => position.studentId == res.studentId))
            let studentSubjPos = suffixer(posRes.filter(position => position.student_id == res.student_id)[0].Position);
            // console.log(classSubjRes);
            let subj = subjects.filter(subj => subj.id == res.subject_id)[0];
            let assessCells = `<tr><th style='text-align:left;'>${subj ? subj.title : '-'}</th>`;
            let grade = res ? gradingFormat.filter(grade => res.TOTAL >= parseFloat(grade.minScore) && res.TOTAL <= parseFloat(grade.maxScore))[0] : null;

            for (const assess of assessFormat) {
                assessCells += `<td>${res[assess.formatType]}</td>`;
            }
            assessCells += `<td>${res.TOTAL}</td><td>${studentSubjPos}</td>
                            <td>${grade ? grade.grade : '-'}</td><td>${grade ? grade.description : '-'}</td>
                            <td>${subj ? (subj.signature ?
                    `<img src="${homepg + subj.signature}" style="height:14px;width:81px;" >`
                    : '-') : '-'}</td></tr>`;
            resBody += assessCells;

        });
        return resBody;
    };



});
/************END OF DOCUMENT LOAD FUNCTION*********** */




/*****************FUNCTION TO CALCULATE TOTAL, AVERAGE, POSITION FOR EACH STUDENT************** */
function buildResult(result, termId) {
    //
    let allBuiltResults = [];
    // let allStudentsId = students.map(({id})=>id);
    let allStudentsId = () => {
        let idSet = new Set(result.map(({ student_id }) => student_id));
        return [...idSet];
    }
    for (const student of allStudentsId()) {
        let studentSubjectRes = result.filter(res => res.student_id == student);
        let subIdSet = new Set(studentSubjectRes.map(({ subject_id }) => subject_id));
        // subIdSet unique set of id for all subjects
        let studentTotal = 0;
        let studentAverage = 0;

        if (termId == '4') {
            subIdSet.forEach(subId => {
                // Calculate Annual Average for each subject and add to total
                let allSubjectTerms = studentSubjectRes.filter(subj => subj.subject_id == subId);
                let subjectTotal = 0;
                allSubjectTerms.forEach(({ TOTAL }) => subjectTotal += parseInt(TOTAL));
                let subjectAverage = subjectTotal / allSubjectTerms.length;
                studentTotal += subjectAverage;

            })
        } else {
            studentSubjectRes.forEach(({ TOTAL }) => studentTotal += parseInt(TOTAL));
        }
        studentAverage = studentTotal / subIdSet.size;
        let studentBuiltRes = {
            id: student,
            Total: studentTotal,
            Average: studentAverage,
            MarksObtainable: subIdSet.size * 5,
        };

        allBuiltResults.push(studentBuiltRes);
    }
    let sortedResults = allBuiltResults.sort(averageSort);
    // let positionedResults = sortedResults.map((result,index)=>({...result, Position: index+1}));
    // console.log(sortedResults);
    let positionedResults = positioner(sortedResults, 'Average');
    return positionedResults;
}

/***********FUNCTION TO HELP SORT ARRAY OF STUDENTS LIST WITH AVERAGE********* */
const averageSort = (student1, student2) => {
    if (student1.Average > student2.Average) {
        return -1;
    } else if (student1.Average < student2.Average) {
        return 1;
    } else {
        return 0;
    }
};
/*************** */


/***********FUNCTION TO HELP SORT STUDENT POSITIONS IN ARRAY OF STUDENTS LIST********* */
const positioner = (sortedResults, definer) => {
    sortedResults[0].Position = 1;

    for (let i = 0; i < sortedResults.length; i++) {
        if (i == sortedResults.length - 1) {
            break;
        }
        if (sortedResults[i][definer] > sortedResults[i + 1][definer]) {
            if (sortedResults[i + 1].Position) { continue; }
            else { sortedResults[i + 1].Position = parseInt(sortedResults[i].Position) + 1; }
        } else {
            sortedResults[i + 1].Position = parseInt(sortedResults[i].Position);
            if (sortedResults[i + 2]) sortedResults[i + 2].Position = i + 3;
        }
    }
    return sortedResults;
}
/*************** */

/***************FUNCTION TO ADD POSITION SUFFIX******************************* */
const suffixer = position => {
    let positionStr = position.toString();
    let eleventhers = ['11', '12', '13'];
    if (eleventhers.includes(positionStr.substring(positionStr.length - 2))) {
        return `${positionStr}th`;
    }
    else if (positionStr.substring(positionStr.length - 1) == 1) {
        return `${positionStr}st`;
    } else if (positionStr.substring(positionStr.length - 1) == 2) {
        return `${positionStr}nd`;
    } else if (positionStr.substring(positionStr.length - 1) == 3) {
        return `${positionStr}rd`;
    } else {
        return `${positionStr}th`;
    }
}


export { buildResult, suffixer };



/*****************AJAX FUNCTION TO SELECT THE CHOSEN CLASS, SESSION AND TERM FROM DB****************** */
function AJAXJS(formObj, actionMET, actionURL, successFxn) {
    let aj = new XMLHttpRequest();
    aj.open(actionMET, actionURL);
    aj.onload = () => {
        if (aj.status == 200) {
            let returnObj = JSON.parse(aj.responseText);
            // console.log(returnObj);
            successFxn(returnObj);
        }
        else {
            let progressModal = document.querySelector('#progressModal');
            progressModal && M.Modal.getInstance(progressModal).close();
            document.querySelector('.progress').classList.add('hide');

            M.toast({ html: "<h5>An error occurred!</h5>", classes: "red white-text" }, 3000);
        }
    };
    aj.setRequestHeader('Content-Type', 'application/json');
    aj.setRequestHeader('X-CSRF-Token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    aj.send(JSON.stringify(formObj));
}
/*************END OF AJAX************** */
