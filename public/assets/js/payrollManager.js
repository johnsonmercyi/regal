document.addEventListener('DOMContentLoaded', () => {
    let naira = (1).toLocaleString('en-NG', {style:'currency', currency:"NGN"})[0];
    let allNaira = document.querySelectorAll('.naira');
    let salaryBtn = document.querySelector('#submitSalary');
    let progModal = document.querySelector('#progressModal');
    let getPayrollStaff = document.querySelector('#getPayrollStaff');
    let getPayrollReport = document.querySelector('#getPayrollReport');
    let submitPayrollBtn = document.querySelector('#submitPayrollBtn');
    let confirmModal = document.querySelector('#confirmModal');
    let yesConfirm = document.querySelector('#confirmModal #yesConfirm');
    let noConfirm = document.querySelector('#confirmModal #noConfirm');
    let generalModalBtn = document.querySelector('#generalModalBtn');
    let applyGeneralBtn = document.querySelector('#applyGeneralBtn');
    let generalValuesModal = document.querySelector('#generalValuesModal');
    let months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    let payPackObj = {payPackArr: [], structure_id: null};
    let progressModal = document.querySelector('#progressModal');
    let school_id = document.querySelector('#schoolId').value;
    
    allNaira && allNaira.forEach((na) => {
        na.textContent = naira;
    })

    salaryBtn && salaryBtn.addEventListener('click', function(e){
        let salaryRows = document.querySelectorAll('#salaryTable tbody tr');
        let salaryArr = [];
        let valid = true
        salaryRows.forEach((row) => {
            let salary = row.querySelector('.salary').value;
            if(salary == ''){
                valid = false;
            }

            salaryArr.push({
                staff_id: row.id,
                basic_salary: salary
            })
        })

        if(!valid){
            alert("Please fill in all salaries!");
            return;
        }
        console.log(salaryArr);

        progModal.querySelector('.progress').classList.remove('hide');
        M.Modal.getInstance(progModal).open();

        AJAXJS(salaryArr, 'POST', '/salary/store', (res) => {
            M.Modal.getInstance(progModal).close();

            M.toast({html: "<h5>Staff Salary updated successfully!</h5>", classes: "rounded green"});
            console.log(res);
        });
    })

    getPayrollStaff && getPayrollStaff.addEventListener('click', (e) => {
        e.preventDefault()
        let payMonth = document.querySelector('input[name="payrollMonth"]').value;
        console.log(payMonth);
        if(payMonth == ''){
            alert('Please select a month!');
            return;
        }

        let dateSpan = document.querySelector('#reportDate');
        let dateSplit = payMonth.split('-');
        let dateValue = months[dateSplit[1] - 1] + ' ' + dateSplit[0];


        progModal.querySelector('.progress').classList.remove('hide');
        M.Modal.getInstance(progModal).open();

        AJAXJS({payMonth}, 'POST', '/payroll/start', (res) => {
            M.Modal.getInstance(progModal).close();
            if(res.exists){
                M.toast({html: "<h5>Payroll already exists for this month!</h5>", classes: "rounded white red-text"});
            }
            if(res.staffSal){
                let payrollTableDiv = document.querySelector('#payrollTableDiv');
                let payrollTable = document.querySelector('#payrollTable');
                let generalValuesTable = document.querySelector('#generalValuesModal .modal-content table tbody');
                let generalValuesTableBody = '';
                let tableBody = '';

                dateSpan.textContent = dateValue;

                let tableHead = '<tr><th>S/No.</th><th>Staff Name</th><th>Basic Salary</th>';

                res.payrollStructure.forEach(struct => {
                    tableHead += `<th>${struct.name}</th>`

                    generalValuesTableBody += `<tr>
                        <th>${struct.name}</th>
                        <td>
                            <input type='number' data-field="${struct.field_name}" style='background-color:#ddd;border-radius:5px'
                            class='center payinp' data-action="${struct.field_action}">
                        </td>
                    </tr>`
                });

                generalValuesTable.innerHTML = generalValuesTableBody;

                tableHead += '<th>Net Salary</th></tr>';
                payrollTable.querySelector('thead').innerHTML = tableHead;

                res.staffSal.forEach((staff, i) => {
                    tableBody += `<tr id='${staff.id}'>
                            <td>${i+1}</td>
                            <td>${staff.lastName+' '+staff.firstName+' '+staff.otherNames}</td>
                            <td><span class='center basicSalary' name='' data-pay=${staff.basic_salary}>${staff.basic_salary}</span></td>
                        `;

                    res.payrollStructure.forEach(struct => {
                        tableBody += `<td><input type='number' data-field="${struct.field_name}" style='background-color:#ddd;border-radius:5px'
                        class='center payinp' data-action="${struct.field_action}" name='' value="${0}"></td>`
                    });

                    tableBody += `<td><span class='center netSalary' name=''>${staff.basic_salary}</span></td></tr>`

                })

                payPackObj.structure_id = res.payrollStructure[0].structure_id;
                
                payrollTable.querySelector('tbody').innerHTML = tableBody;

                payrollTableDiv.classList.remove('hide');
                payrollCalc()
            }
            
            console.log(res);
        });
    })

    /**********fxn to Submit Payroll********* */
    submitPayrollBtn && submitPayrollBtn.addEventListener('click', (e) => {
        e.preventDefault();
        packPayroll();
    })

    /********FXN TO PACK PAYROLL VALUES******** */
    const packPayroll = () => {
        let total_salary = 0,
            school_id = document.querySelector('#schoolId').value;
        let pay_date = document.querySelector('input[name="payrollMonth"]').value + '-1';
            
        let payRows = document.querySelectorAll('#payrollTable tbody tr');
        payRows.forEach(trow => {
            let rowFields = trow.querySelectorAll('input'),
                net_pay = trow.querySelector('.netSalary').textContent,
                rowObj = {},
                basic_salary = trow.querySelector('.basicSalary').dataset.pay;
            total_salary += +net_pay;
            rowFields.forEach(field => rowObj[field.dataset.field] = field.value);
            payPackObj.payPackArr.push({
                ...rowObj,
                school_id,
                net_pay,
                basic_salary,
                staff_id: trow.id,
                pay_date
            })
        });
        
        let payDateObj = {school_id, total_salary};
        
        confirmModal.querySelector('#confirmQuery').innerHTML = `Total Salary: ${(total_salary).toLocaleString('en-NG', {style:'currency', currency:"NGN"})}. Continue?`
        M.Modal.getInstance(confirmModal).open()
        console.log(payDateObj, payPackObj);
        
    }

    
    yesConfirm && yesConfirm.addEventListener('click', () => {
        confirmModal.querySelector('.progress').classList.remove('hide')

        AJAXJS(payPackObj, 'POST', '/payroll/store', (res) => {
            if(res.exists){
                M.toast({html: "<h5>Payroll already exists!</h5>", classes: "rounded white red-text"});
                confirmModal.querySelector('.progress').classList.add('hide');
                M.Modal.getInstance(confirmModal).close()
                return;
            }

            if(res.success){
                console.log(res);
                confirmModal.querySelector('.progress').classList.add('hide')
                M.Modal.getInstance(confirmModal).close()
                M.toast({html: "<h5>Payroll Submitted Successfully!</h5>", classes: "rounded white green-text"});
            } else {
                confirmModal.querySelector('.progress').classList.add('hide')
                M.Modal.getInstance(confirmModal).close()
                M.toast({html: "<h5>Unable to create payroll!</h5>", classes: "rounded white red-text"});
            }

            payPackObj.payPackArr = [];
        })
    })
    noConfirm && noConfirm.addEventListener('click', () => {
        M.Modal.getInstance(confirmModal).close()
    })



    /********FXN TO ASSIGN CALC FXN TO INPUT EVENTS****** */
    const payrollCalc = () => {
        let payRows = document.querySelectorAll('#payrollTable tbody tr');
        payRows.forEach(trow => {
            let payrollInputs = trow.querySelectorAll('.payinp');
            payrollInputs.forEach(inp => {
                inp.addEventListener('keyup', e => {e.preventDefault(); eventAct(trow)});
                inp.addEventListener('input', e => {e.preventDefault(); console.log('change'); eventAct(trow)});
                // inp.addEventListener('change', e => {e.preventDefault(); console.log('change'); eventAct(trow)});
            })
        })
    }

    /*******FXN TO CALCULATE NET SALARY FROM INPUT FIELDS****** */
    let eventAct = trow =>{
        let basicValue = +trow.querySelector('.basicSalary').dataset.pay;
        let netSal = trow.querySelector('.netSalary');
        trow.querySelectorAll('.payinp').forEach(inpCalc => {
            let inpVal = +inpCalc.value;
            let inpAction = inpCalc.dataset.action;
            if(inpAction == 'add'){
                basicValue += inpVal;
            } else if (inpAction == 'minus') {
                basicValue -= inpVal;
            }
        })
        netSal.textContent = basicValue < 0 ? 'Error' : basicValue.toFixed(2);
    };

    generalModalBtn && generalModalBtn.addEventListener('click', () => {
        M.Modal.getInstance(generalValuesModal).open();


    });

    applyGeneralBtn && applyGeneralBtn.addEventListener('click', (e) => {
        e.preventDefault();
        let payrollTable = document.querySelector('#payrollTable');

        let generalValuesForm = document.querySelector('#generalValuesForm');
        generalValuesForm.querySelectorAll('input').forEach(genVal => {
            let inputTitle = genVal.dataset.field;
            let inputVal = +genVal.value

            payrollTable.querySelectorAll(`input[data-field='${inputTitle}']`).forEach(inp => {
                inp.value = +inp.value + inputVal;
            })
        })
        payrollTable.querySelectorAll('tbody tr').forEach(trow => eventAct(trow));

        generalValuesForm.reset()
        M.Modal.getInstance(generalValuesModal).close();
    })


    /*************GET PAYROLL REPORTS************* */
    getPayrollReport && getPayrollReport.addEventListener('click', (e) => {
        e.preventDefault()
        let payMonth = document.querySelector('input[name="payrollMonth"]').value;
        if(payMonth == ''){
            alert('Please select a month!');
            return;
        }

        progModal.querySelector('.progress').classList.remove('hide');
        M.Modal.getInstance(progModal).open();

        AJAXJS({payMonth}, 'POST', '/payroll/report', (res) => {
            M.Modal.getInstance(progModal).close();
            if(res.payroll.length == 0){
                M.toast({html: "<h5>No payroll submitted for this month!</h5>", classes: "rounded white red-text"});
                return;
            }

            let dateSpan = document.querySelector('#reportDate');
            let dateSplit = payMonth.split('-');
            let dateValue = months[dateSplit[1] - 1] + ' ' + dateSplit[0];

            if(res.payroll){
                let payrollTable = document.querySelector('#payrollReport');
                let tableBody = '', totalSalary =0, totalBasicSalary = 0;
                dateSpan.textContent = dateValue;
                let totalsObj = {basic_salary: 0};
                let payrollTableFooter = '<tr><td></td><th>Grand Total</th>';
                
                let tableHead = '<tr><th>S/No.</th><th>Staff Name</th><th>Basic Salary</th>';

                res.structure.forEach(struct => {
                    tableHead += `<th>${struct.name}</th>`
                    totalsObj[struct.field_name] = 0;
                });

                tableHead += '<th class="center">Net Pay <br /> <span class="naira"></span></th></tr>';

                payrollTable.querySelector('thead').innerHTML = tableHead;


                res.payroll.forEach((staff, i) => {
                    totalSalary += +staff.net_pay;
                    totalBasicSalary += +staff.basic_salary;

                    tableBody += `<tr>
                            <td>${i+1}</td>
                            <td>${staff.lastName+' '+staff.firstName+' '+staff.otherNames}</td>
                            <td><span class='center basicSalary' name=''>${staff.basic_salary}</span></td>`

                    res.structure.forEach(struct => {
                        if(struct.field_action == "minus"){
                            tableBody += `<td>(${staff[struct.field_name] || "0.00"})</td>`
                        } else {
                            tableBody += `<td>${staff[struct.field_name] || "0.00"}</td>`
                        }
                        totalsObj[struct.field_name] += +staff[struct.field_name];
                    });

                    // `
                    // <td>${staff.bonus ? staff.bonus : "0.00"}</td>
                    // <td>${staff.overtime ? staff.overtime : "0.00"}</td>
                    // <td>${staff.absence ? staff.absence : "0.00"}</td>
                    // <td>(${staff.lateness ? staff.lateness : "0.00"})</td>
                    // <td>(${staff.other_addition ? staff.other_addition : "0.00"})</td>
                    // <td>(${staff.other_deduction ? staff.other_deduction : "0.00"})</td>

                    tableBody += `<td><span class='center netSalary' data-action="add" name=''>${staff.net_pay}</span></td>
                        </tr>`;
                });


                
                payrollTableFooter += `<th>${totalBasicSalary.toFixed(2)}</th>`
                res.structure.forEach(struct => {
                    payrollTableFooter += `<th>${totalsObj[struct.field_name].toFixed(2)}</th>`
                });
                payrollTableFooter += `<th>${totalSalary.toFixed(2)}</th></tr>`

                payrollTable.querySelector('tbody').innerHTML = tableBody;
                payrollTable.querySelector('tfoot').innerHTML = payrollTableFooter;
                document.querySelector('#totalSalarySpan').textContent = totalSalary.toLocaleString('en-NG', {style:'currency', currency:"NGN"});

                document.querySelector('#profileDiv').classList.remove('hide');
            }
        })

    });
    
    
    
    /******HANDLE PAYROLL STRUCTURE SETTINGS****** */
        let submitStructureBtn = document.querySelector('#submitStructureBtn')
        let cancelStructureBtn = document.querySelector('#cancelStructureBtn')
        let createStructureBtn = document.querySelector('#createStructureBtn')
        let newStructureTable = document.querySelector('#newStructureTable')
        let newStructureModal = document.querySelector('#newStructureModal')
        let addStructureField = document.querySelector('#addStructureField')
        let clearStructureBtn = document.querySelector('#clearStructureBtn')
        let countStructureField = 1;
        let structureDiv = document.querySelectorAll('.structureDiv')
    
        createStructureBtn && createStructureBtn.addEventListener('click', () => {
            M.Modal.getInstance(newStructureModal).open()
        });
    
        clearStructureBtn && clearStructureBtn.addEventListener('click', () => {
            newStructureTable.querySelector('tbody').innerHTML = '';
            countStructureField = 1;
        });
    
    
        addStructureField && addStructureField.addEventListener('click', () => {
            if(countStructureField <= 10){
                let rowContent =  `
                    <td>
                        ${countStructureField}
                    </td>
                    <td>
                        <input type='text' name='name' style='background-color:#ddd;border-radius:5px' class='center' >
                    </td>
                    <td>
                        <select name="field_action" class="browser-default greyInp">
                            <option value="add">add</option>
                            <option value="minus">minus</option>
                        </select>
                    </td>
                    `;
                let tableBody = newStructureTable.querySelector('tbody')
                let newRow = tableBody.insertRow(tableBody.rows.length);
                newRow.id = countStructureField;
                newRow.innerHTML = rowContent;
                countStructureField++;
            }
        })
    
        cancelStructureBtn && cancelStructureBtn.addEventListener('click', () => {
            newStructureTable.querySelector('tbody').innerHTML = '';
            M.Modal.getInstance(newStructureModal).close();
        })
    
        submitStructureBtn && submitStructureBtn.addEventListener('click', () => {
            let newStructureArr = []
            let valid = true;
            let structureRows = newStructureTable.querySelectorAll('tbody tr');
            structureRows && structureRows.forEach(row => {
                let name = row.querySelector('input[name="name"]').value;
                if(!name){
                    valid = false;
                }
                let field = {
                    name: name,
                    field_name: "field" + row.id,
                    field_action: row.querySelector('select[name="field_action"]').value,
                    status: '1'
                }
                newStructureArr.push(field);
            })
    
            if(!valid){
                alert('Please fill all fields!');
                return;
            }
    
            progressModal.querySelector('.progress').classList.remove('hide');
            M.Modal.getInstance(progressModal).open();
    
            if(newStructureArr.length > 0){
                console.log(newStructureArr)  
                AJAXJS(newStructureArr, 'POST', '/payroll/structure/store', (res) => {
                    M.Modal.getInstance(progressModal).close();
                    M.Modal.getInstance(newStructureModal).close()
                    if(res.success){
                        M.toast({html: "<h5>Structure Created Successfully!</h5>", classes: "rounded white green-text"});
    
                        setTimeout(window.location.assign(`/${school_id}/payroll/structure`), 3000);
                    } else {
                        M.toast({html: "<h5>Unable to Create Structure!</h5>", classes: "rounded white red-text"});
                    }
                    // console.log(res)
                })
            }
        })
    
        structureDiv && structureDiv.forEach(struct => {
    
            let btn = struct.querySelector('.activateBtn');
            let allStatus = document.querySelectorAll('.structureStatus')
            btn && btn.addEventListener('click', () => {
                let structure_id = btn.dataset.structure;
    
                progressModal.querySelector('.progress').classList.remove('hide')
                M.Modal.getInstance(progressModal).open()
    
                AJAXJS({structure_id}, 'POST', '/payroll/structure/activate', (res) => {
                    M.Modal.getInstance(progressModal).close()
    
                    if(res.success){
                        M.toast({html: "<h5>Structure Activated!</h5>", classes: "rounded white green-text"});
                        allStatus.forEach(stat => {
                            stat.textContent = 'Inactive'
                        })
    
                        struct.querySelector('.structureStatus').textContent = 'Active';
                        btn.setAttribute('disabled', 'true');
                    } else {
                        M.toast({html: "<h5>Unable to Activate Structure!</h5>", classes: "rounded white red-text"});
                    }
                })
            })
        })


});


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