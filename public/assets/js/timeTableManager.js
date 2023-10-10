
document.addEventListener('DOMContentLoaded', function(){
    const newBtn = document.querySelector('#newPeriodRow');
    const periodContainer = document.querySelector('#periodsContainer');
    const submitPeriods = document.querySelector('#submitPeriods');
    const submitTimetable = document.querySelector('#submitTimetable');
    let serialNum = 1;
    let school_id = document.querySelector('#schoolId').value
    let progressModal = document.querySelector('#progressModal');
    let classtimetable = document.querySelector('.classtimetable');
    let viewclasstimetable= document.querySelector('#viewclasstimetable');
    let timetabletable = document.querySelector('#timetabletable');
    let periodNames = ['First Period',
     'Second Period', 
     'Third Period', 
     'Fourth Period', 
     'Fifth Period', 
     'Sixth Period', 
     'Seventh Period', 
     'Eighth Period', 
     'Ninth Period', 
     'Tenth Period'
    ]

    newBtn && newBtn.addEventListener('click', function(e) {
        e.preventDefault();
        let rowCount = document.querySelectorAll('#periodsContainer .row').length
        if(rowCount >= periodNames.length){
            alert('You have reached Max')
            return;
        }
        let newDiv = document.createElement('div');
        newDiv.className = "periodRow";
        newDiv.classList.add('row');
        newDiv.innerHTML += `<div  style="width: 100%; hover:{ cursor: pointer; }">
            <div class="input-field col s4 m5 period_name">
            ${periodNames[rowCount]}
            </div>
            <div class="input-field col s4 m3">
            <input type="time" name="" id="class"  class="start_time">
            <label for="subject">Start Time</label>
            </div>
            <div class="input-field col s4 m3">
            <input type="time" name="" id="class"  class="end_time">
            <label for="subject">End Time</label>
            </div>
            <div class="input-field col s4 m1">
            <a class="btn-floating btn-large waves-effect waves-light red"><i class="material-icons removePeriod right">close</i></a>
            </div>     
        </div>`;

        periodContainer.append(newDiv);

        removeDiv();
    })

         // Remove Div
        function removeDiv(){
            document.querySelectorAll('.periodRow').forEach(function (pr) {
             pr.querySelector('.removePeriod').addEventListener('click', function(){
                pr.remove();
                serialNum = serialNum- 1
               })
            })
        }

        submitPeriods && submitPeriods.addEventListener('click', function(e) {
            e.preventDefault()
                    progressModal.querySelector('.modal-content .progress').classList.remove('hide')
                    M.Modal.getInstance(progressModal).open()
            let periodArray = []
            document.querySelectorAll('#periodsContainer .row').forEach(period => {
                let per = {
                    period_name: period.querySelector('.period_name').textContent.trim(),
                    start_time: period.querySelector('.start_time').value,
                    end_time: period.querySelector('.end_time').value,
                    school_id
                }
                periodArray.push(per)
            })
            console.log(periodArray)
            AJAXJS(periodArray, 'POST', '/period/store', (res)=>{
                if(res.success){
                    M.Modal.getInstance(progressModal).close()
                    M.toast({html:"<h4>Period Added Successfully</h4>", classes:"green"})
                    
                    // console.log(res)
                }
            })
        })
    //Submit Timetable....
        submitTimetable && submitTimetable.addEventListener('click', function(e) {
            e.preventDefault()
            let timeArr = [];
            let validPeriod = true;
            let class_id = document.querySelector('#classId').value;
            let academic_session_id = document.querySelector('#acadId').value;

            if (class_id=='') {
                alert('Select Class')
                return;
            }else if(academic_session_id ==''){
                alert('Select Academic Session')
                return;
            // }else if(timeArr==''){
            //     alert('Please fill all periods')
            //     return;
            }else{
                document.querySelectorAll('#timetabletable td select').forEach((ss) => {
                    if(ss.value == ''){
                        validPeriod = false;
                    }
                    let details = {
                        period_id: ss.dataset.period,
                        day: ss.dataset.day,
                        subject_id: ss.value,
                        school_id,
                        class_id,
                        academic_session_id,
                    }
                    timeArr.push(details)
                });
                if (validPeriod == false) {
                    alert('Please fill all periods')
                        return;
                }
                //console.log(timeArr)
                AJAXJS(timeArr, 'POST', '/timetable/store', (res)=>{
                    if(res.success){
                        M.Modal.getInstance(progressModal).close()
                        M.toast({html:"<h4>Timetable Added Successfully</h4>", classes:"green"});
                        timetabletable.reset();
                       // console.log(res)
                    }
                })
            }
            
        });

        viewclasstimetable && viewclasstimetable.addEventListener('click', function(e){
            e.preventDefault();
           // alert('welcome');
           let selectObj = {
               class_id: document.querySelector('#classId').value,
               school_id: document.querySelector('#schoolId').value,
               academic_session_id: document.querySelector('#sessionIdSelect').value
           }
           if(selectObj.class_id==''){
               alert('Select Class')
               return;
           }else if(selectObj.academic_session_id==''){
               alert('Select Academic Session')
               return;
           }else{
                   progressModal.querySelector('.progress').classList.remove('hide')
                    M.Modal.getInstance(progressModal).open()
                AJAXJS(selectObj, 'POST', '/get/timetable', function(res){
                if(res.success){
                    M.Modal.getInstance(progressModal).close()
                    console.log(res)
                    
                    if(res.success.length == 0){
                        M.toast({html: "<h4>No Timetable for Selected Class</h4>", classes: "red rounded"});
                        classtimetable.classList.add('toggleclasstable');
                        return;
                    }

                    let allTimeTable = '';
                    let days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
                    days.forEach(d => {
                        let dayRow = `<tr><th>${d}</th>`;
                        res.success.filter((item, key) => {
                            return item.day === d
                        }).forEach((item)=>{
                            let staff = res.teachersNames.find(teach => item.subject_id == teach.subject_id)
                            dayRow += "<td>"+"<span style='font-weight: bold;'>"+item.title+"</span>"+`${staff ? "<br><small style='color: blue;'>"+staff.firstName+ ' ' + staff.lastName + "</small>" : "<br><small style='color: red;'>"+'N/A'+"</small>" }`+"</td>"
                        })
                        dayRow += "</tr>"
                        allTimeTable += dayRow;
                    })
                    // console.log(dayRow)
                    document.querySelector('#viewClassTimeTable tbody').innerHTML = allTimeTable;
                    classtimetable.classList.remove('toggleclasstable');
                }
               })
            }   
        })

})

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



