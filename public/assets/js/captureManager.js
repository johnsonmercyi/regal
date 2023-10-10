import AJAXJS from './ajaxModule.js';


let staffSelectEl = document.querySelector('#capturedStaff');
let passportBtn = document.querySelector('#passportSubmit');
let passportFile = document.querySelector('#passportFile');
// let showSiggy = document.querySelector('#showSiggy');
let showPassy = document.querySelector('#showPassy');
let classSelect = document.querySelector('#classSelect');
let studentSelect = document.querySelector('#studentSelect');
let studentPassportBtn = document.querySelector('#studentPassportBtn');
let schoolId = document.querySelector('#schoolId').value;

// showSiggy.addEventListener('click', e => {
//   e.preventDefault();
//   document.querySelector('#signaturetab').classList.remove('hide');
//   document.querySelector('#passporttab').classList.add('hide');
// })

showPassy && showPassy.addEventListener('click', e => {
  e.preventDefault();
  document.querySelector('#passporttab').classList.remove('hide');
  document.querySelector('#signaturetab').classList.add('hide');
})

classSelect && classSelect.addEventListener('change', ()=>{
  let classId = classSelect.value;
  if(classId == ''){
    alert('Please select a class');
    return;
  }
  document.querySelector('#selectDiv .progress').classList.remove('hide');
  let sessionId = document.querySelector('#sessionId').value;
  AJAXJS(JSON.stringify({sessionId, schoolId}), 'POST', `/class/${classId}/members/move`, false, fillStudents)
})

staffSelectEl && staffSelectEl.addEventListener('change', ()=>{
  let staffName = staffSelectEl.selectedOptions[0].textContent;
  let nameBox = document.querySelector('#staffName');
  let staffId = document.querySelector('#staff-id');

  nameBox.querySelector('h6').textContent = staffName;
  staffId.value = staffSelectEl.value;
  nameBox.classList.remove('hide');
  // console.log(staffId.value)
})

studentSelect && studentSelect.addEventListener('change', ()=>{
  let studentName = studentSelect.selectedOptions[0].textContent;
  let nameBox = document.querySelector('#studentName');
  let studentId = document.querySelector('#student-id');

  nameBox.querySelector('h6').textContent = studentName;
  studentId.value = studentSelect.value;
  nameBox.classList.remove('hide');
  // console.log(staffId.value)
})

  /****Callback function to populate students list**** */
  function fillStudents(res){
    document.querySelector('#selectDiv .progress').classList.add('hide');
    if(res.members.length < 1){
        M.toast({html: "<h4>No Students Registered in this Class</h4>", classes: "rounded red"});
        return;
    }
    let studentOptions = '<option value="">Select Student</option>';
    res.members.forEach(stud => studentOptions += `
        <option value="${stud.student_id}">${stud.lastName+' '+stud.firstName+' '+stud.otherName}</option>
    `);
    document.querySelector('#studentSelect').innerHTML = studentOptions;
  }

function previewPassport(){
  let previewCompress = document.querySelector('#preview_compress_img');
  let preview = document.querySelector('#src_img');
  let file = document.querySelector('#passportFile').files[0];
  let reader = new FileReader();
  reader.addEventListener('load', function(e){
    preview.src = e.target.result;
    preview.onload = function(){
      compressFile(this, previewCompress);
    }
  }, false);
  if(file){
    reader.readAsDataURL(file);
  }
}

function compressFile(loadedData, preview){
  let quality = 10;

  let mime_type = "image/jpeg";
  
  let cvs = document.createElement('canvas');
  cvs.width = loadedData.width;
  cvs.height = loadedData.height;
  // console.log(loadedData)
  let ctx = cvs.getContext("2d").drawImage(loadedData, 0, 0);
  let newImageData = cvs.toDataURL(mime_type, quality/100);
  let result_image_obj = new Image();
  result_image_obj.src = newImageData;
  preview.src = result_image_obj.src;

  document.querySelector('#cameraIcon').classList.add('hide');
  document.querySelector('#preview_compress_img').classList.remove('hide');
  preview.onload = function(){}

}

passportFile && passportFile.addEventListener('change', function(e){
  console.log('capture')
  e.preventDefault();
  previewPassport();
})

passportBtn && passportBtn.addEventListener('click', (e) => {
  e.preventDefault();
  let staffId = document.querySelector('#staff-id').value;
  if(staffId == ''){
    alert('Please select a staff');
    return;
  }
  let dataURL = document.querySelector('#preview_compress_img').getAttribute('src');
  if(dataURL == ''){
    alert('Please capture a photo');
    return;
  }
  let signObj = {staffId, dataURL};
  let progElem = document.querySelector('#progressModal');
  progElem.querySelector('.progress').classList.remove('hide');
  M.Modal.getInstance(progElem).open();
  AJAXJS(JSON.stringify(signObj), 'POST', `/staff/photo/update/${staffId}`, false, successFxn);
})

studentPassportBtn && studentPassportBtn.addEventListener('click', (e) => {
  e.preventDefault();
  let studentId = document.querySelector('#student-id').value;
  if(studentId == ''){
    alert('Please select a student');
    return;
  }
  let dataURL = document.querySelector('#preview_compress_img').getAttribute('src');
  if(dataURL == ''){
    alert('Please capture a photo');
    return;
  }
  let signObj = {studentId, dataURL};
  let progElem = document.querySelector('#progressModal');
  progElem.querySelector('.progress').classList.remove('hide');
  M.Modal.getInstance(progElem).open();
  AJAXJS(JSON.stringify(signObj), 'POST', `/student/photo/update/${studentId}`, false, successFxn);
})

function successFxn(res){
  M.Modal.getInstance(document.querySelector('#progressModal')).close();
  if(res.success){
    M.toast({html:"<h5><i class='material-icons'>check</i> Photo submitted successfully<h5>", classes: "rounded green"});
  } else {
    M.toast({html:"<h5><i class='material-icons'>close</i> Unable to submit photo<h5>", classes: "rounded red"});
  }
}