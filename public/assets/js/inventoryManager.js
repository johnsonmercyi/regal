// import {AJAXJS} from './subjectsManager.js';
document.addEventListener('DOMContentLoaded', function(){

    let classDropDown = document.querySelector('#salesPickClass');
    let studentDropDown = document.querySelector('#salesPickStudent');
    let sessionId = document.querySelector('#sessionId').value;
    let invoiceClass = document.querySelector('#selectedClass');
    let invoiceStudentName = document.querySelector('#selectedStudentName');
    let invoiceReg = document.querySelector('#studentReg');
    let invoiceDate = document.querySelector('#currentDate');
    let invoiceItems = document.querySelector(".invoiceItemSelect");
    let invoiceTable = document.querySelector('#invoiceTable tbody');
    let addBtn = document.querySelector('#invoiceAddBtn');
    let itemQuants = document.querySelectorAll('.itemQuant');
    let createCatBtn = document.querySelector('#createCatBtn');
    let createItemBtn = document.querySelector('#createItemBtn');
    let showCatForm = document.querySelector('#showCatForm');
    let anotherItem = document.querySelector('#createAnotherItemBtn');
    let createItemDone = document.querySelector('#createItemDoneBtn');
    let itemMod = document.querySelector('#itemModal');
    let invoiceMod = document.querySelector('#invoiceModal');
    let itemOptions = document.querySelector('#invoiceItemSelect') ? document.querySelector('#invoiceItemSelect').innerHTML : '';
    let invoiceSubmit = document.querySelector('#invoiceSubmitBtn');
    let school_id = document.querySelector('#schoolId').value;
    let dailyReportsBtn = document.querySelector('#dailyReportBtn');
    let invoiceReportBtn = document.querySelector('#invoiceReportBtn');
    let invoiceStudent = {school_id};
    let invoiceItemsId = [];
    let daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    // let removeBtn = document.querySelectorAll('.removeItem');

    // let loadMoveClassBtn = document.querySelector('loadCurrentClassStudents');
    /**************FUNCTION TO SET DATE ON INVOICE************ */
    invoiceDate && (invoiceDate.textContent = makeDate());
    function makeDate(){
        let d = new Date;
        return `${d.getDate()+'-'+(d.getMonth()+1)+'-'+d.getFullYear()}`;
    }

    /***************FUNCTION TO SELECT CLASS AND LOAD CLASS MEMBERS LIST INTO DROPDOWN******************* */
    classDropDown && classDropDown.addEventListener('change', function(e){
        e.preventDefault()
        let classId = classDropDown.value;
        let classObj = {classId, sessionId}
        // console.log(classId);
        if(classId == '') return false;
        document.querySelector('.progress').classList.remove('hide');
        AJAXJS(classObj, 'POST', `/class/${classObj.classId}/members/move`, getClassMembers);
        
    })

    /******************FUNCTION TO REMOVE A ROW********************** */
    function removeRow(btn){
        btn.addEventListener('click', function(e){
            e.preventDefault()
            delRow(btn);
            sumTotal();
        })
    }
    invoiceTable && removeRow(document.querySelector('.removeItem'));

    /*******************FUNCTION TO SELECT AN ITEM FROM THE DROPDOWN LIST************************* */
    function selectItem(item){
        item.addEventListener('change', function(e){
            e.preventDefault();
            let itemNum = item.value;
            if(itemNum == '' || invoiceItemsId.includes(+itemNum)) return false;
            let itemName = item.selectedOptions[0].textContent;
            let itemRow = item.parentElement.parentElement;
            itemRow.querySelector('.uPrice').innerHTML = item.selectedOptions[0].getAttribute('data-price');
            itemRow.querySelector('.itemNum').innerHTML = itemNum;
            itemRow.querySelector('.itemQuant').value = 1;
            itemRow.querySelector('.itemTotal').innerHTML = item.selectedOptions[0].getAttribute('data-price');
            itemRow.setAttribute('data-qty', item.selectedOptions[0].getAttribute('data-qty'));
            item.parentElement.innerHTML = itemName;
            invoiceItemsId.push(+itemNum)
            sumTotal();
        })
    }
    invoiceTable && selectItem(invoiceItems);
    invoiceTable && listenTotal(document.querySelector('.itemQuant'));

    /************FUNCTION TO CALC TOTAL AMOUNT FOR EACH ITEM************* */
    function listenTotal(inp){
        inp.addEventListener('change', function(e){e.preventDefault(); getTotal(inp)});
        inp.addEventListener('keyup', function(e){e.preventDefault(); getTotal(inp)});
    }

    function getTotal(inp){
        let itemQty = parseFloat(inp.value);
        let itemRow = inp.parentElement.parentElement;
        let itemStock = parseFloat(itemRow.getAttribute('data-qty'));
        if(itemQty > itemStock){
            alert('Quantity Exceeds Stock!');
            inp.value = 0;
            return false;
        }
        let itemPrice = parseFloat(itemRow.querySelector('.uPrice').innerHTML);
        itemRow.querySelector('.itemTotal').innerHTML = (itemQty * itemPrice).toFixed(2);
        sumTotal();
    }

    /****************FUNCTION TO GET THE GRAND TOTAL*************** */
    function sumTotal(){
        let totVals = [];
        document.querySelectorAll('.itemTotal').
        forEach(tot => totVals.push(parseFloat(tot.textContent)))
        document.querySelector('#invoiceTotal').textContent = totVals.length == 0  ? '0.00' : (totVals.reduce((gtot, tot) => gtot + tot )).toFixed(2);
    }

    /****************FUNCTION TO ADD A NEW ROW TO THE INVOICE**************** */
    invoiceTable && addBtn.addEventListener('click', function(e){
        e.preventDefault();
        if(document.querySelector('.invoiceItemSelect')) return false;
        let rowContent = `
            <td class="itemNum thinrow"></td>
            <td class="itemName thinrow">
                <select id="" class="browser-default invoiceItemSelect" style="background-color:#e9e8e8">
                    ${itemOptions}
                </select></td>
            <td class="uPrice thinrow"></td>
            <td class="qty thinrow">
                <input type='number' style='width:50px;background-color:#e9e8e8;border-radius:5px;margin:0px'
                class='itemQuant center' >
            </td>
            <td class="thinrow">
                <span class="itemTotal"></span>
                <button class="btn-floating btn-small white removeItem right">
                    <i class="material-icons red-text ">close</i>
                </button>
            </td>
        `
        let newRow = invoiceTable.insertRow(invoiceTable.rows.length);
        newRow.innerHTML = rowContent;
        newRow.classList.add('bottomdash');
        selectItem(document.querySelector('.invoiceItemSelect'));
        listenTotal(newRow.querySelector('.itemQuant'));
        removeRow(newRow.querySelector('.removeItem'));
    })

    /*******************FUNCTION TO DELETE A ROW FROM INVOICE******************** */
    function delRow(btn){
        let rowId = btn.parentElement.parentElement;
        let itemId = parseInt(rowId.querySelector('.itemNum').innerHTML)
        if(invoiceItemsId.includes(itemId)){
            let itemIndex = invoiceItemsId.indexOf(itemId)
            delete invoiceItemsId[itemIndex];
        }
        document.querySelector('#invoiceTable').deleteRow(rowId.rowIndex);
        // let itemId = 
        console.log(invoiceItemsId);
    }

    /********************FUNCTION TO SELECT A STUDENT FROM THE STUDENT DROPDOWN***************** */
    studentDropDown && studentDropDown.addEventListener('change', function(e){
        e.preventDefault();
        if(studentDropDown.value == '') return false;
        invoiceStudent.student_id = studentDropDown.value;
        // invoiceStudent.class_id = classDropDown.value;
        invoiceStudentName.textContent = studentDropDown.selectedOptions[0].textContent;
        invoiceReg.textContent = studentDropDown.selectedOptions[0].getAttribute('data-reg');
        invoiceClass.textContent = classDropDown.selectedOptions[0].textContent;

    })

    /*******************FUNCTION TO LOAD THE CLASS MEMBERS FROM THE DB INTO DROPDOWN********************** */
    function getClassMembers(resp){
        document.querySelector('.progress').classList.add('hide');
        if(resp.members.length == 0){
            M.toast({html:"<h5>Selected Class has No Members!", classes:"red"}, 4000);
            return false;
        }
        // console.log(resp);
        let classStudents = `<option value=""> Select Student </option>`;
        resp.members.forEach(std => {
            classStudents += `<option value=${std.student_id} data-reg=${std.regNo}>${(std.lastName+' '+ std.firstName+' '+std.otherName).toUpperCase()}</option>`
        })
    
        studentDropDown.innerHTML = classStudents;
    }

    /******************FUNCTION TO CREATE AN ITEM CATEGORY******************** */
    createCatBtn && createCatBtn.addEventListener('click', function(e){
        e.preventDefault();
        let catObj = {
            name: document.querySelector('#catName').value,
            description : document.querySelector('#catDesc').value,
            prefix: document.querySelector('#catPrefix').value,
            school_id: school_id,
        }
        console.log(catObj);
        document.querySelector('.progress').classList.remove('hide');
        AJAXJS(catObj, 'POST', `/inventory/category/create`, function(resp){
        document.querySelector('.progress').classList.add('hide');
            if(resp.created){
                let catMod = document.querySelector('#catModal');
                M.Modal.init(catMod).open();
            } else {
                M.toast({html:"<h4>Unable to Create Category!</h4>", classes:"red"}, 3000)
            }
        });
    });

    /**************FUNCTION TO SHOW THE CREATE CATEGORY FORM**************** */
    showCatForm && showCatForm.addEventListener('click', function(e){
        e.preventDefault();
        document.querySelector('#catTable').classList.add('hide')
        document.querySelector('#catForm').classList.remove('hide')
    })

    /*****************FUNCTION TO CREATE AN INVENTORY ITEM************** */
    createItemBtn && createItemBtn.addEventListener('click', function(e){
        e.preventDefault();
        let itemObj = {
            name: document.querySelector('#itemName').value,
            category_id: document.querySelector('#itemCat').value,
            current_price: document.querySelector('#itemPrice').value,
            quantity: document.querySelector('#itemQty').value,
            school_id: school_id,
        }
        document.querySelector('.progress').classList.remove('hide');
        createItemBtn.setAttribute('disabled', true);
        console.log(itemObj)
        AJAXJS(itemObj, 'POST', `/inventory/item/create`, function(resp){
        document.querySelector('.progress').classList.add('hide');
            if(resp.created){
                M.Modal.init(itemMod).open();
            } else {
                M.toast({html:"<h4>Unable to Create Item!</h4>", classes:"red"}, 3000)
            }
        });
    })

    anotherItem && anotherItem.addEventListener('click', function(e){
        e.preventDefault()
        document.querySelector('#itemCreateForm').reset();
        createItemBtn.setAttribute('disable', false);
        M.Modal.init(itemMod).close();
    })


    /****************FUNCTION TO SUBMIT THE INVOICE**************** */
    invoiceSubmit && invoiceSubmit.addEventListener('click', function(e){
        e.preventDefault();
        let invoiceRows = document.querySelectorAll('#invoiceTable tbody tr');
        let invoiceArr = [];
        let d = new Date;
        invoiceRows.forEach(row => {
            let quantity = row.querySelector('.itemQuant').value;
            let oldQuant = parseFloat(row.getAttribute('data-qty'));
            let newQuant = oldQuant - quantity;
            let rowObj = {
                ...invoiceStudent,
                invoice_no: `INV/${invoiceStudent.student_id}/${d.getDate()}${d.getMonth()+1}${d.getFullYear()}`,
                item_id: row.querySelector('.itemNum').innerHTML,
                quantity,
                newQuant,
                unit_price: row.querySelector('.uPrice').innerHTML,
                name: row.querySelector('.itemName').innerHTML,
                sale_date: `${d.getFullYear()}-${d.getMonth()+1}-${d.getDate()}`
            }
            invoiceArr.push(rowObj);
        })
        invoiceSubmit.setAttribute('disabled', true);
        console.log(invoiceArr)
        document.querySelector('#invoiceSection .progress').classList.remove('hide');
        AJAXJS(invoiceArr, 'POST', `/inventory/invoice/store`, function(resp){
            if(resp.success){
                document.querySelector('#invoiceSection .progress').classList.add('hide');
                M.Modal.init(invoiceMod).open();
            }
        });
    })



    /****************REPORTS SECTION****************** */

    /**************FUNCTION TO CHECK AND RETURN CHOSEN DATE VALUES************ */
    function dateCheck(){
        let dateVal = document.querySelector('#reportDate').value;
        if(dateVal == ""){
            document.querySelector('#dateError').classList.remove('hide');
            return false;
        }
        let dateObj = {date: dateVal};
        document.querySelector('#dateError').classList.add('hide');
        return dateObj;
    }

    /*************FUNCTION TO FETCH DAILY REPORTS FROM DATABASE************** */
    dailyReportsBtn && dailyReportsBtn.addEventListener('click', function(e){
        e.preventDefault();
        let dateObj = dateCheck();
        if(!dateObj) return false;
        document.querySelector('#reportsTableSection').classList.add('hide');
        document.querySelector('.progress').classList.remove('hide');
        AJAXJS(dateObj, 'POST', `/inventory/report/daily/fetch`, function(resp){
            if(resp.dailySales){
                document.querySelector('.progress').classList.add('hide');
                let saleBody = ``;
                let salesArr = [];
                let salesTot = 0;
                // Below Function is to get total sum for each repeated item
                resp.itemsId.forEach(sale => {
                    let saleOccur = resp.dailySales.filter(s => s.item_id == sale.item_id);
                    if(saleOccur.length > 1){
                        let totalNum = 0;
                        saleOccur.forEach(unit => totalNum += unit.quantity);
                        saleOccur[0].quantity = totalNum;
                        salesArr.push(saleOccur[0]);
                    } else {
                        salesArr.push(saleOccur[0]);
                    }
                })
                salesArr.forEach(sale => {
                    saleBody += `<tr class='rowBord'><td>${sale.item_id}</td><td>${sale.name}</td>
                    <td>${sale.quantity}</td><td>${sale.unit_price}</td><td>${sale.quantity * sale.unit_price}</td></tr>`;
                    salesTot += sale.quantity * sale.unit_price;
                })
                document.querySelector('#dailyReportsTable #totalCell').innerHTML = salesTot;
                document.querySelector('#dailyReportsTable tbody').innerHTML = saleBody;
                let d = new Date(dateObj.date);
                document.querySelector('#dateHead').innerHTML = `Report for ${daysOfWeek[d.getDay()]}, ${d.getDate()}-${d.getMonth()+1}-${d.getFullYear()}`;
                document.querySelector('#reportsTableSection').classList.remove('hide');
            }
        });
    })


    /********************FUNCTION TO FETCH INVOICE REPORT OF DAILY SALES********************** */
    invoiceReportBtn && invoiceReportBtn.addEventListener('click', function(e){
        e.preventDefault();
        let dateObj = dateCheck();
        if(!dateObj) return false;
        document.querySelector('#invoiceRepTableSection').classList.add('hide');
        document.querySelector('.progress').classList.remove('hide');
        AJAXJS(dateObj, 'POST', `/inventory/report/invoice/fetch`, function(resp){
            if(resp.invoiceRep){
                let invoiceRepArr = resp.invoiceRep.reduce((acc, inv) => {
                    if(!acc.student_id)acc[inv.student_id] = [inv];
                    acc[inv.student_id].push(inv);
                    return acc;
                }, {})
                console.log(invoiceRepArr);
            }
        })
    })

});


    
/****************FUNCTION FOR CALLBACK ON AJAX REQUEST********************* */
    function studentsMoved(resp){
        //
    }



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