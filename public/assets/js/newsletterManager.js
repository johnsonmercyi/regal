import AJAX_JS from './ajaxModule.js';

document.addEventListener('DOMContentLoaded', () => {
    let classModal = M.Modal.init(document.querySelector('#newsletterModal'));
    let classPickBtn = document.querySelector('#classPick');
    let selectDone = document.querySelector('#chooseDoneBtn');
    let classCheck = document.querySelectorAll('#newsletterModal .newsClassCheck');
    let selectClassArr = [{class_id: 'All', name: 'All Classes'}];
    let assignClassUL = document.querySelector('#assignClassList')
    let submitNewsletter = document.querySelector('#submitNewsletter')
    let progModal = document.querySelector('#progressModal');

    classPickBtn.addEventListener('click', (e) => {
        e.preventDefault();
        classModal.open()
    })

    document.querySelector("#newsletterModal #close").addEventListener('click', ()=>{
        classModal.close();
    })


    selectDone.addEventListener('click', function(e){
        e.preventDefault();
        if(selectClassArr.length > 0){
            let selectList = ``;
            selectClassArr.forEach(({name}) => selectList += `<li class='collection-item center'>${name}</li>`)
            assignClassUL.innerHTML = selectList;
            // console.log(assignClassUL.offsetHeight)
            // window.scrollTo(assignClassUL.offsetTop, window.scrollY);
        } else {
            assignClassUL.innerHTML = `<li class="collection-item center">No Class Selected.</li>`;
        }
        classModal.close();
    })

    classCheck.forEach(btn => {
        btn.addEventListener('click', function(e){
            e.preventDefault();
            makeRadio(btn);
            // console.log(selectClassArr)
        })
    })

    function makeRadio(btn){
        let containAll = selectClassArr.filter(({class_id}) => class_id == 'All' || class_id == 'JSS' || class_id == 'SS');
        if(containAll.length > 0 && !['All', 'JSS', 'SS'].includes(btn.dataset.id)){
            return;
        }
        
        if(btn.dataset.id === 'All' && btn.dataset.check == 'false'){
           btn.setAttribute('data-check', 'true');
           classCheck.forEach(btn => {
                toBlack(btn);
            })
            selectClassArr = [{class_id: 'All', name: 'All Classes'}];
        }else if(btn.dataset.id === 'All' && btn.dataset.check == 'true'){
           btn.setAttribute('data-check', 'false');
           classCheck.forEach(btn => {
               toGrey(btn)
            })
            selectClassArr = [];
        }else if(btn.dataset.id === 'JSS' && btn.dataset.check == 'false'){
            btn.setAttribute('data-check', 'true');
            classCheck.forEach(btn => {                
                toGrey(btn);
                let btnClass = btn.dataset.name.substr(0, 3);
                if(btnClass == 'JSS'){
                    toBlack(btn);
                }
            })
            toBlack(btn)
            selectClassArr = [{class_id: 'JSS', name: 'All Junior Classes'}];
         }else if(btn.dataset.id === 'SS' && btn.dataset.check == 'true'){
            btn.setAttribute('data-check', 'false');
            classCheck.forEach(btn => {
                 toGrey(btn);
            })
            selectClassArr = [];
         }else if(btn.dataset.id === 'SS' && btn.dataset.check == 'false'){
            btn.setAttribute('data-check', 'true');
            classCheck.forEach(btn => {                
                toGrey(btn);
                let btnClass = btn.dataset.name.substr(0, 2);
                if(btnClass == 'SS'){
                    toBlack(btn);
                }
            })
            toBlack(btn)
            selectClassArr = [{class_id: 'SS', name: 'All Senior Classes'}];
         }else if(btn.dataset.id === 'SS' && btn.dataset.check == 'true'){
            btn.setAttribute('data-check', 'false');
            classCheck.forEach(btn => {
                 toGrey(btn);
            })
            selectClassArr = [];
         }else if(btn.dataset.check == 'true'){
           btn.setAttribute('data-check', 'false');
           toGrey(btn);
           let btnId = btn.dataset.id;
           selectClassArr = selectClassArr.filter(({class_id}) => class_id != btnId)
        } else {
           btn.setAttribute('data-check', 'true');
           toBlack(btn);
           selectClassArr.push({class_id: btn.dataset.id, name: btn.dataset.name})
        }
        console.log(selectClassArr)

    }

    /****Add Grey Colour to button */
    function toGrey(btn){
        btn.classList.remove('black');
        btn.classList.add('grey');
    }
    
    /****Add Black Colour to button */
    function toBlack(btn){
        btn.classList.remove('grey');
        btn.classList.add('black');
    }


    submitNewsletter && submitNewsletter.addEventListener('click', e => {
        e.preventDefault();
        let school_id = document.querySelector('#schoolId').value;
        let session_id = document.querySelector('#sessionId').value;
        let term_id = document.querySelector('#termId').value;
        let newsletter = document.querySelector('#newsletterFile').files[0];
        let formDetails = new FormData();
        let chosenClass = selectClassArr.map(({class_id}) => class_id);

        formDetails.append('details', JSON.stringify({'class_id': chosenClass, school_id, term_id, session_id}));
        formDetails.append('newsletter', newsletter);


        M.Modal.init(progModal).open();
        progModal.querySelector('.progress').classList.remove('hide');

        AJAX_JS(formDetails, 'POST', '/newsletter/upload', true, (res) => {
            M.Modal.init(progModal).close();
            progModal.querySelector('.progress').classList.add('hide');

            if(res.failed){
                // document.querySelector('.progress').classList.add('hide');
                M.toast({html:"<h5>Unable to upload!</h5>", classes:"red"}, 3000);
            }
            if(res.success){
                // document.querySelector('.progress').classList.add('hide');
                M.toast({html:"<h5>Newsletter uploaded successfully!</h5>", classes:"green"}, 3000);
                console.log('Success');
            }
        })
    })

})