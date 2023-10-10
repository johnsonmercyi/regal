import AJAXJS from './ajaxModule.js';
document.addEventListener('DOMContentLoaded', function(){
    // let createDate = document.querySelector('#dateCreated');
    let submitBtn = document.querySelector('#submitAssignment');
    // let assForm = document.querySelector('#assignmentForm');
    let amentModal = document.querySelector('#amentModal');
    let classPickBtn = document.querySelector('#classPick');
    let classCheck = document.querySelectorAll('#assignClassesTable .amentClassCheck');
    let assignClassUL = document.querySelector('#assignClassList');
    let selectClassArr = [];
    let selectDone = document.querySelector('#amentModal #chooseDoneBtn');
    let selectModal = M.Modal.init(amentModal);
    let downBtns = document.querySelectorAll('.downloadBtn');

    // createDate && (createDate.value = makeDate());
    function makeDate(){
        let d = new Date;
        return `${d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate()}`;
    }

    downBtns.forEach(btn => {
        btn.addEventListener('click', function(e){
            e.preventDefault();
            let file_name = JSON.stringify({file_name: btn.getAttribute('data-file')});
            console.log(file_name);
            AJAXJS(file_name, 'POST', '/assignments/download', false, function(res){
                if(res.success){
                    console.log('Downloaded')
                }
            })

        })
    })

    submitBtn && submitBtn.addEventListener('click', function(e){
        e.preventDefault();
        // const subForm = new FormData(assForm);
        // for(let i of subForm.entries()){
        //     console.log(i)
        // }
        let school_id = document.querySelector('#schoolId').value;
        let date_of_submission = document.querySelector('#submitDate').value;
        let date_created = document.querySelector('#createdDate').value;
        let subject_id = document.querySelector('#selSubject').value;
        let assignmentFile = document.querySelector('#assignmentFile').files[0];
        let formDetails = new FormData();
        // const assignArr = selectClassArr.map(({class_id}) => ({class_id, school_id, subject_id, date_of_submission, date_created, assignmentFile}))
        let classArr = selectClassArr.map(({class_id}) => class_id);
        const assignObj = {class_id: classArr, school_id, subject_id, date_of_submission, date_created};
        formDetails.append('assignmentFile', assignmentFile);
        formDetails.append('details', JSON.stringify(assignObj));
        
        document.querySelector('.progress').classList.remove('hide');
        AJAXJS(formDetails, 'POST', '/assignments/store', true, function(res){
            if(res.failed){
                document.querySelector('.progress').classList.add('hide');
                M.toast({html:"<h4>Unable to Create Assignment!</h4>", classes:"red"}, 3000);
            }
            if(res.success){
                document.querySelector('.progress').classList.add('hide');
                M.toast({html:"<h4>Assignment Created Successfully!</h4>", classes:"green"}, 3000);
                console.log('Success');
            }
        })
    })

    classPickBtn && classPickBtn.addEventListener('click', function(e){
        e.preventDefault();
        selectModal.open();
    })


    classCheck && classCheck.forEach(btn => {
        btn.addEventListener('click', function(e){
            e.preventDefault();
            makeRadio(btn);
            // console.log(selectClassArr)
        })
    })

    function makeRadio(btn){
       if(btn.getAttribute('data-check') == 'true'){
           btn.setAttribute('data-check', 'false');
           btn.classList.remove('black');
           btn.classList.add('grey');
           let btnId = parseInt(btn.getAttribute('data-id'));
           selectClassArr = selectClassArr.filter(({class_id}) => class_id != btnId)
        } else {
           btn.setAttribute('data-check', 'true');
           btn.classList.remove('grey');
           btn.classList.add('black');
           selectClassArr.push({class_id:parseInt(btn.getAttribute('data-id')), name:btn.getAttribute('data-name')})
        }

    }

    selectDone && selectDone.addEventListener('click', function(e){
        e.preventDefault();
        if(selectClassArr.length > 0){
            let selectList = ``;
            selectClassArr.forEach(({name}) => selectList += `<li class='collection-item'>${name}</li>`)
            assignClassUL.innerHTML = selectList;
            // console.log(assignClassUL.offsetHeight)
            // window.scrollTo(assignClassUL.offsetTop, window.scrollY);
        } else {
            assignClassUL.innerHTML = `<li class="collection-item">No Class Selected.</li>`;
        }
        selectModal.close();
    })

})
