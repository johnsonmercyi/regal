import AJAXJS from './ajaxModule.js';

let classLevel = document.querySelector('#classLevel');
let sectionSelect = document.querySelector('#sectionSelect');
let classSuffix = document.querySelector('#classSuffix');
let classSubmit = document.querySelector('#classSubmit');
let schoolId = document.querySelector('#schoolId').value;
let progElem = document.querySelector('#progressModal');
let sectionSubmit = document.querySelector('#submitSectionCreate');

classSubmit && classSubmit.addEventListener('click', function(e){
    e.preventDefault();

    let level = classLevel.value;
    let section_id = sectionSelect.value;
    let suffix = classSuffix.value.trim();

    if(level == '' || section_id == '' || suffix == ''){
        M.toast({html: "<h5>Please fill all fields!</h5>", classes: "red round"});
        return;
    }

    let classObj = JSON.stringify({level, section_id, suffix, schoolId});

    console.log(classObj);
    classSubmit.setAttribute('disabled', 'true');
    progElem.querySelector('.progress').classList.remove('hide');
    M.Modal.getInstance(progElem).open();
  AJAXJS(classObj, 'POST', `/class/new/store`, false, successFxn);  
})

sectionSubmit && sectionSubmit.addEventListener('click', function(e){
  e.preventDefault();

  let sectionName = document.querySelector('#sectionName').value.trim();
  let shortName = document.querySelector('#shortName').value.trim();
  let sectionHead = document.querySelector('#sectionHead').value.trim();
  let sectionHeadSign = document.querySelector('#sectionHeadSign').files[0];

  if(sectionName == '' || shortName == '' || sectionHead == ''){
      M.toast({html: "<h5>Please fill in all fields!</h5>", classes: "red round"});
      return;
  }

  // let classObj = {sectionName, shortName, sectionHead, sectionHeadSign};
  let sectionForm = new FormData(document.querySelector('#schoolSectionForm'));

  // console.log(classObj);
  sectionSubmit.setAttribute('disabled', 'true');
  progElem.querySelector('.progress').classList.remove('hide');
  M.Modal.getInstance(progElem).open();
  AJAXJS(sectionForm, 'POST', `/section/new/store`, true, successFxn);  
})
  

function successFxn(res){
    M.Modal.getInstance(progElem).close();
    if(res.exists){
        M.toast({html:"<h5><i class='material-icons'>close</i> Class already exists!<h5>", classes: "rounded red"});
        classSubmit && classSubmit.removeAttribute('disabled');
    }
    if(res.success){
      M.toast({html:`<h5><i class='material-icons'>check</i> ${sectionSubmit ? 'Section' : 'Class'} created successfully<h5>`, classes: "rounded green"});
      classSubmit && classSubmit.removeAttribute('disabled');
    } else {
      M.toast({html:`<h5><i class='material-icons'>close</i> Unable to create ${sectionSubmit ? 'Section' : 'Class'}<h5>`, classes: "rounded red"});
      classSubmit && classSubmit.removeAttribute('disabled');
    }
  }