
/*************SIDEBAR CONTROLS********************* */
document.addEventListener('DOMContentLoaded', function(){  
    const preloader = document.querySelector('#preloaderCon') ? document.querySelector('#preloaderCon') : null;
    preloader && (preloader.style.display = 'none');

    let school = document.querySelector('#schoolId') ? document.querySelector('#schoolId').value : null,
    sessionId = document.querySelector('#sessionId') ? document.querySelector('#sessionId').value: null,
    termId = document.querySelector('#termId') ? document.querySelector('#termId').value : null,
    schColor = document.querySelector("#schColor") ? document.querySelector("#schColor").value : null;
    let sideNavEl = document.querySelector('#menuSide'); 
    let sideNavIn = M.Sidenav.init(sideNavEl);
    let menuBtn = document.querySelector('#menuSideBtn');
    let menuOpen = true;
    sideNavIn && sideNavIn.open();
    let staffSSCE = document.querySelector('#staffssce tbody');
    let higherIntn = document.querySelector('#higherIntn');
    let printProfileBtn = document.querySelector('#printProfile');
    let staffCategory = document.querySelector('#staffCategory');

    document.querySelectorAll('.collapsible-accordion').forEach(hd =>{
        hd.addEventListener('click', ()=>{
            let act = hd.querySelector('li').classList.contains('active');
            hd.querySelector('.right i').innerHTML = act ? 'expand_more' : 'expand_less';
        })
    })

    
    const removeRow = (tRow, table) => {
        tRow.querySelector('.remRow').addEventListener('click', e => {
            table.deleteRow(tRow.rowIndex-1);            
        })
    }

    // Add new Row to staff ssce score table
        let ssceCount = 1;
        staffSSCE && (()=>{
        document.querySelector('#addSSCE').addEventListener('click', e=>{
            e.preventDefault();
            // console.log('hi');
            let ssceSubjects = document.querySelector('#ssceSubjectSelect').innerHTML;
            let ssceGrades = document.querySelector('#ssceGradeSelect').innerHTML;
            let rowContent =  `<td>
                    <select name="stfsscesub${ssceCount}" class="browser-default greyInp">
                        ${ssceSubjects}
                    </select>
                </td>
                <td>
                    <select name="stfsscegrade${ssceCount++}" class="browser-default greyInp">
                        ${ssceGrades}
                    </select>
                </td>
                <td>
                    <i class="material-icons red-text remRow">close</i>
                </td>`;
            let newRow = staffSSCE.insertRow(staffSSCE.rows.length);
            newRow.innerHTML = rowContent;
            removeRow(newRow, staffSSCE)
        })
        document.querySelector('#numSittings') && document.querySelector('#numSittings').addEventListener('blur', e=>{
            e.preventDefault()
            let val = +document.querySelector('#numSittings').value;
            
            if(val > 1){
                let otherssce = document.querySelector('#otherSsce');
                otherssce ? (()=>otherssce.style.display = 'block')() :
                (()=>{
                    let newDiv = document.createElement('div')
                    // newDiv.classList.add(...['file-field','input-field', 'col', 's12', 'm6'])
                    newDiv.classList.add(...['row'])
                    newDiv.setAttribute('id', 'otherSsce');
                    newDiv.innerHTML = `
                        <div class="col s12 m6">
                            <select name="stfsscebody2" class='browser-default' style="margin-top: 1rem;">
                                <option value="" disable selected>Certificate Exam Body</option>
                                <option value="WASSCE" >WASSCE</option>
                                <option value="GCE">GCE</option>
                                <option value="NECO">NECO</option>
                            </select>
                        </div>
                        <div class='file-field input-field col s12 m6'>
                            <div class="btn green">
                                <span>Upload Other Certificate</span>
                                <input type="file" accept="image/*" name="stfsscecert2" class="validate" >
                            </div>
                            <div class="file-path-wrapper">
                                <input type="text" class="file-path validate">
                            </div>
                        </div>
                    `
                    document.querySelector('#ssceCerts').append(newDiv)})();
                // 9/9/2020 using the below stopped propagation after the first run
                // document.querySelector('#ssceCerts').innerHTML += `
                // <div class="file-field input-field col s12 m6" >
                //     <div class="btn green">
                //             <span>Select Other Certificate</span>
                //     <input type="file" accept="image/*" name="stffslcyeay" class="validate" >
                //     </div>
                //     <div class="file-path-wrapper">
                //         <input type="text" class="file-path validate">
                //     </div>
                // </div>
                // `;
            } else {
                let otherssce = document.querySelector('#otherSsce');
                otherssce && (()=>otherssce.style.display = 'none')()
                // console.log('ssce')
            }
        })
        // removeRow(staffSSCE.querySelector('tr'), staffSSCE)
    })()

    // Add new Row to staff higher Education
    higherIntn && (()=>{
        let num = 2;
        document.querySelector('#addhigherEd').addEventListener('click', e=>{
            e.preventDefault();
            // console.log('hi');
            let staffQualifSelect = document.querySelector('#staffQualifSelect').innerHTML;
            let staffHigherGradeSelect = document.querySelector('#staffHigherGradeSelect').innerHTML;
            let rowContent = `
                <div class="row">
                <div class="col m6 s12">
                    <input type="text" name="stfhigherinstitute${num}" placeholder="Institution Attended" class="greyInp" style="margin-bottom:0px">
                </div>
                <div class="col m3 s12">
                    <select name="stfhigherinstqual${num}" class="browser-default greyInp">
                        ${staffQualifSelect}
                    </select>
                </div>
                <div class="col m3 s12">
                    <select name="stfhigherinstgrade${num}" class="browser-default greyInp">
                        ${staffHigherGradeSelect}
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col m6 s12">
                    <input type="text" name="stfhighercourse${num}" placeholder="Course of Study" class="greyInp" style="margin-bottom:0px">
                </div>
                <div class="col m2 s12">
                    <input type="number" name="stfhigherinstyear${num}" 
                        class="validate greyInp" min="1930" max="2020" placeholder="Year" style="margin-bottom:0px">
                    <!-- <input type="file" accept="image/*" name="stfhigherinstcert${num}" class="greyInp validate" style="width:150px"> -->
                </div>
                <div class="col m3 s12" style="padding:0">
                    <div class="file-field input-field" style="margin-top:0">
                        <div class="btn green">
                                <span>Upload Certificate</span>
                        <input type="file" accept="image/*" name="stfhigherinstcert${num++}" class="validate" >
                        </div>
                        <div class="file-path-wrapper">
                            <input type="text" class="file-path validate">
                        </div>
                    </div>
                </div>
                <div class="col m1 s12">
                    <i class="material-icons red-text remHigher">close</i>
                </div>
            </div>`;
            // let newRow = higherIntn.insertRow(higherIntn.rows.length);
            let newDiv = document.createElement('div')
            newDiv.classList.add('otherHighRow');
            newDiv.innerHTML = rowContent;
            higherIntn.append(newDiv);
            // Remove added div
            document.querySelectorAll('.otherHighRow').forEach(r => {
                r.querySelector('.remHigher').addEventListener('click', e=>{
                    e.preventDefault();
                    r.remove();
                })
            })
            // removeRow(newRow, higherIntn)
            
        })
        // removeRow(higherIntn.querySelector('tr'), higherIntn)
    })()


    menuBtn && menuBtn.addEventListener('click', function(e){
        e.preventDefault();
        // console.log('menuOpen');
        if(menuOpen){
            menuOpen = false;
            menuSide.querySelector('.side-info').style.display = 'none';
            menuSide.querySelector('#badgeDiv').style.display = 'none';
            menuBtn.querySelector('i').innerHTML = 'close';
            menuBtn.querySelector('i').classList.remove('right');
            document.querySelector('.main-container').setAttribute('style', 'padding-left: 50px')
            document.querySelector('header').setAttribute('style', 'margin-left: 50px')
            menuSide.setAttribute('style', 'width:50px !important');
            menuSide.querySelectorAll('.collapsible-accordion').forEach(txt=>{
                txt.querySelector('.collapsible-header').onclick = (e)=>e.stopPropagation();
                txt.querySelector('.menuText').style.display = 'none';
                txt.querySelector('.right').style.display = 'none';
                // txt.querySelector('.collapsible-body').style.display = 'none';
            })
        } else {
            menuOpen = true;
            menuSide.querySelector('.side-info').style.display = 'inline';
            menuSide.querySelector('#badgeDiv').style.display = 'block';
            menuBtn.querySelector('i').innerHTML = 'menu';
            menuBtn.querySelector('i').classList.add('right');
            document.querySelector('.main-container').setAttribute('style', 'padding-left: 250px')
            document.querySelector('header').setAttribute('style', 'margin-left: 250px')
            menuSide.setAttribute('style', 'width:250px !important');
            schColor && (menuSide.style.backgroundColor = schColor);
            menuSide.querySelectorAll('.collapsible-accordion').forEach(txt=>{
                txt.querySelector('.collapsible-header').onclick = ()=>console.log('hi');
                txt.querySelector('.menuText').style.display = 'inline';
                // txt.querySelector('.').classList.add('left');
                txt.querySelector('.right').style.display = 'inline';
                // txt.querySelector('.collapsible-body').style.display = 'inline';
            })
        }

        // if(menuOpen){
        //     menuOpen = false;
        //     sideNavIn.close();
        //     document.querySelector('.main-container').classList.remove('padSide');
        // } else {
        //     menuOpen = true;
        //     document.querySelector('.main-container').classList.add('padSide');
        //     document.querySelector('body').classList.add('showOver');
        //     sideNavIn.open();
        // }
        
    });

    //VIEW CERTIFICATE IN MODAL
    // const certModalClose = document.querySelector('#certModal #close');
    // certModalClose && certModalClose.addEventListener('click', e => {
    //     e.preventDefault()
    // })
    document.querySelectorAll('.certView').forEach( mage => {
        mage.addEventListener('click', e => {
            e.preventDefault();
            const isrc = mage.getAttribute('src');
            let imgWind = window.open("", "width=200, height=100");
            imgWind.focus();
            imgWind.document.body.innerHTML = `
            <div style='margin: 0 auto;text-align:center'>
                <img src='${window.location.origin + isrc}' style='width:700px;height:auto;' />
            </div>`;
        });                
    });

    // VIEW CERTIFICATE BUTTON
    let certRow = document.querySelectorAll('.imgPrintRow');
    certRow && certRow.forEach(r => {
        r.querySelector('.viewStaffCert').addEventListener('click', e => {
            e.preventDefault();
            const imgSrc = r.querySelector('.certView').getAttribute('src');
            let imgWind = window.open("", "width=200, height=100");
            imgWind.focus();
            imgWind.document.body.innerHTML = `
            <div style='margin: 0 auto;text-align:center'>
                <img src='${window.location.origin + imgSrc}' style='width:700px;height:auto;' />
            </div>`;
        })
    })
    
    // PRINT STAFF CERTIFICATE IMAGE
    const allCertDiv = document.querySelectorAll('.imgPrintRow');
    allCertDiv && allCertDiv.forEach(imgDiv => {
        let prtBtn = imgDiv.querySelector('.printStaffCert');
        let certImg = imgDiv.querySelector('img').getAttribute('src');
        prtBtn.addEventListener('click', e => {
            e.preventDefault();
            console.log('certificate')
            let imgWind = window.open("", "width=200, height=100");
            imgWind.focus();
            imgWind.document.body.innerHTML = `
            <div style='margin: 0 auto;text-align:center;'>
                <img src='${window.location.origin + certImg}' style='width:700px;height:auto' />
            </div>`;
            setTimeout(()=>imgWind.print(), 1500)
        })
    })

    // HANDLE THE SIBLING CHECK ON STUDENT'S REGISTRATION FORM
    let siblingYes = document.querySelector('#siblingYes');
    let siblingNo = document.querySelector('#siblingNo');
    let siblingClassSelect = document.querySelector('#siblingClassSelect');
    let siblingClassList = document.querySelector('#siblingClassList');
    let guardianForm = document.querySelector('#guardianFormDiv') ? document.querySelector('#guardianFormDiv').innerHTML : null;
    
    siblingYes && siblingYes.addEventListener('click', e => {
        e.preventDefault();
        let siblingDiv = document.querySelector('#siblingClassDiv');
        siblingDiv.classList.remove('hide');
    }) 
    
    siblingNo && siblingNo.addEventListener('click', e => {
        e.preventDefault();
        let siblingDiv = document.querySelector('#siblingClassDiv');
        siblingDiv.classList.add('hide');
    }) 

    siblingClassSelect && siblingClassSelect.addEventListener('change', e => {
        let classId = siblingClassSelect.value;
        if(classId == '') return;
        document.querySelector('#siblingDiv .progress').classList.remove('hide');
        let sessionId = document.querySelector('#sessionId').value;
        AJAXJS({sessionId}, 'POST', `/class/${classId}/members/move`, fillStudents)
    })

    siblingClassList && siblingClassList.addEventListener('change', e => {
        let studentId = siblingClassList.value;
        if(studentId == '') return;
        document.querySelector('#siblingDiv .progress').classList.remove('hide');
        let sessionId = document.querySelector('#sessionId').value;
        AJAXJS({sessionId}, 'POST', `/student/guardian/${school}/${studentId}`, fillParents)
    });

    function fillParents(res){
        document.querySelector('#siblingDiv .progress').classList.add('hide');

        siblingNo.addEventListener('click', e => {
            e.preventDefault();
            document.querySelector('#guardianFormDiv').innerHTML = guardianForm;
        });

        if(!res.guardian){
            M.toast({html: "<h5>No parent/guardian found for selected student</h5>", classes: "rounded red"}, 3000);
            return;
        }
        let guardian = res.guardian;
        let guardianDetails = `<h5 style="padding-top:1rem" style="margin-bottom: .5rem">Parent/Guardians Details</h5>
            <div class="row">
                <div class="col m12">
                    <input type="hidden" name="stdparent_id" value="${guardian.id}" >
                    <table>
                        <tr>
                            <td class="titext" style="padding-right:1rem">Father's Name:</td>
                            <td>
                                ${ (guardian.father_title).toUpperCase()+' '+(guardian.father_firstName).toUpperCase()+' '+(guardian.father_otherName).toUpperCase()+' '+(guardian.father_lastName).toUpperCase() }
                            </td>
                            <td class="titext" style="padding-right:1rem">Father's Phone Number:</td>
                            <td>
                                ${ (guardian.father_phoneNo).toUpperCase() }
                            </td>
                        </tr>

                        <tr>
                            <td class="titext" style="padding-right:1rem">Father's Occupation:</td>
                            <td>
                                ${ (guardian.father_occupation).toUpperCase()  }
                            </td>
                            <td class="titext" style="padding-right:1rem">Father's Office Address:</td>
                            <td>
                                ${ (guardian.father_officeAddress).toUpperCase() }
                            </td>
                        </tr>
                        <tr>
                            <td class="titext" style="padding-right:1rem">Father's Office Phone Number:</td>
                            <td>
                                ${ (guardian.father_officePhone).toUpperCase()  }
                            </td>
                            <td class="titext" style="padding-right:1rem">Father's Email Address:</td>
                            <td>
                                ${ (guardian.father_email).toUpperCase() }
                            </td>
                        </tr>     
                        <tr>
                            <td class="titext" style="padding-right:1rem">Mother'sName:</td>
                            <td>
                                ${ (guardian.mother_title).toUpperCase()+' '+(guardian.mother_firstName).toUpperCase()+' '+(guardian.mother_otherName).toUpperCase()+' '+(guardian.mother_lastName).toUpperCase() }
                            </td>
                            <td class="titext" style="padding-right:1rem">Mother's Phone Number:</td>
                            <td>
                                ${ (guardian.mother_phoneNo).toUpperCase() }
                            </td>
                        </tr>

                        <tr>
                            <td class="titext" style="padding-right:1rem">Mother's Occupation:</td>
                            <td>
                                ${ (guardian.mother_occupation).toUpperCase() }
                            </td>
                            <td class="titext" style="padding-right:1rem">Mother's Office Address:</td>
                            <td>
                                ${ (guardian.mother_officeAddress).toUpperCase() }
                            </td>
                        </tr>
                        <tr>
                            <td class="titext" style="padding-right:1rem">Mother's Office Phone Number:</td>
                            <td>
                                ${ (guardian.mother_officePhone).toUpperCase() }
                            </td>
                            <td class="titext" style="padding-right:1rem">Mother's Email Address:</td>
                            <td>
                                ${ (guardian.mother_email).toUpperCase() }
                            </td>
                        </tr>   
                        <tr>
                            <td class="titext" style="padding-right:1rem">Family Doctor:</td>
                            <td>
                                ${ (guardian.family_doctor_name).toUpperCase() }
                            </td>
                            <td class="titext" style="padding-right:1rem">Hospital Address:</td>
                            <td>
                                ${ (guardian.family_doctor_address).toUpperCase() }
                            </td>
                        </tr>      
                        <tr>
                            <td class="titext" style="padding-right:1rem">Doctor's Phone Number:</td>
                            <td>
                                ${ (guardian.family_doctor_phone).toUpperCase() }
                            </td>
                        </tr>      
                    </table>
                </div>
            </div>`;
        document.querySelector('#guardianFormDiv').innerHTML = guardianDetails;
            // console.log(res.guardian)
        
    }

    /****Callback function to populate siblings class list**** */
    function fillStudents(res){
        document.querySelector('#siblingDiv .progress').classList.add('hide');
        if(res.members.length < 1){
            M.toast({html: "<h4>No Students Registered in this Class</h4>", classes: "rounded red"});
            return;
        }
        let studentOptions = '<option value="">Select Sibling</option>';
        res.members.forEach(stud => studentOptions += `
            <option value="${stud.student_id}">${stud.lastName+' '+stud.firstName+' '+stud.otherName}</option>
        `);
        document.querySelector('#siblingClassList').innerHTML = studentOptions;
    }


    // FILL UP THE STUDENTS COUNT ON HOME/DASHBOARD
    let studentEl = document.querySelector('#studentCount');
    studentEl && AJAXJS({sessionId, termId}, 'POST', `/${school}/counts`, fillCount)

    // Print Student Profile
    printProfileBtn && printProfileBtn.addEventListener('click', function(e){
        e.preventDefault();
        printPage();
        // return true;
    });

    async function printPage(){
        let profileDiv = document.querySelector('#profileDiv').innerHTML;
        // let myWindow = window.open('', 'PRINT', 'height=400,width=600');
        // myWindow.document.write('<html><head><title>'+document.title+'</title></head><body>');
        // myWindow.document.write('<h1>'+document.title+'</h1>');
        // myWindow.document.write(profileDiv + '</body></html>');
        let printPage = window.open("", "window=200,height=100");
        printPage.focus();
        
        printPage.document.write('<html><head><title>'+document.title+`</title><style>
            body{font-family: 'Bookman Old Style'}
            .titext{font-weight: bold;}
            td{font-size:.9rem;padding:.2rem; border-bottom: 1px solid #333;}
            #otherTable .titext{font-size: .7rem}
            h5{font-size:1rem}
            #scoreTable td{margin: 1px; font-size: .7rem;height:20px !important;text-wrap:nowrap;border-right:1px solid black;}
            #scoreTable tfoot{display:none}
            #scoreTable th{margin: 1px; font-size: .7rem;height:20px !important;width:50px !important;}
            .totalScore{display:none}
            #scoreTable input{display:none}
            button{display:none}
        </style></head><body>`);
        printPage.document.write(profileDiv + '</body></html>');
        
        // printPage.document.body.innerHTML = profileDiv;
        // let profilePix = document.querySelector('#profilePix');
        // let pixSrc = profilePix.getAttribute('src');
        // printPage.document.querySelector('#profilePix').setAttribute('src', window.location.host + pixSrc);

        // myWindow.print();
        // printPage.document.onload = ()=>printPage.print();
        setTimeout(() => {printPage.print()}, 1500)
    }

    //Change Staff Position on Select Category
    staffCategory && staffCategory.addEventListener('change', e => {
        e.preventDefault();
        let staffPosition = document.querySelector('#staffPosition');
        let category = staffCategory.value;
        let positions = JSON.parse(document.querySelector('#positionControl').value);
        let posOptions = `<option value="" >Staff Position<sup>*</sup></option>`;
        positions.forEach(pos => posOptions += pos.category_id == category ? 
            `<option value='${pos.id}'>${pos.position}</option>`
            : ''
        );
        staffPosition.innerHTML = posOptions;
    })

    // Change Staff Rank on select grade level
    let gradeLevelSelect = document.querySelector('#gradeLevelSelect');
    let rankInput = document.querySelector('#rankInput');
    gradeLevelSelect && gradeLevelSelect.addEventListener('change', e => {
        e.preventDefault();
        let rank = gradeLevelSelect.selectedOptions[0].dataset.rank;
        rankInput.value = rank;
    })

    // Remove the higher education div
    let higherEdDiv = document.querySelector('#higherEdDiv');
    let higherEdDiv2 = document.querySelector('#higherIntn');
    let remHigherBtn = document.querySelector('#removeHigherEd');
    remHigherBtn && remHigherBtn.addEventListener('click', function(){
        higherEdDiv.classList.add('hideAnimate');
        // higherEdDiv2.classList.add('hideAnimate');
        document.addEventListener('animationend', function(){
            console.log('out')
            higherEdDiv.remove();
        })
    })
  


    /*****************AJAX FUNCTION TO SELECT THE CHOSEN CLASS, SESSION AND TERM FROM DB****************** */
    function AJAXJS(sendObj, actionMET, actionURL, successFxn){
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
        aj.send(JSON.stringify(sendObj));
    }
/*************END OF AJAX************** */

})

let fillCount = res => {
    let {students, teachers, male, female} = res;
    let studentEl = document.querySelector('#studentCount');
    let teacherEl = document.querySelector('#teacherCount');
    let parentEl = document.querySelector('#parentCount');
    let maleEl = document.querySelector('#maleCount');
    let femaleEl = document.querySelector('#femaleCount');
    let c = 0;
    let t = 0;
    while(c < students){
        setTimeout(()=>{studentEl.innerHTML = +studentEl.innerHTML + 1;}, 400)        
        // if(c < teachers) setTimeout(()=>teacherEl.innerHTML = +teacherEl.innerHTML + 1, 400);
        // if(c < parents) setTimeout(()=>parentEl.innerHTML = +parentEl.innerHTML + 1, 400);
        if(c < male) setTimeout(()=>maleEl.innerHTML = +maleEl.innerHTML + 1, 400);
        if(c < female) setTimeout(()=>femaleEl.innerHTML = +femaleEl.innerHTML + 1, 400);
        c++;
    }
    while(t < teachers){        
        if(t < teachers) setTimeout(()=>teacherEl.innerHTML = +teacherEl.innerHTML + 1, 400);
        t++;
    }
}