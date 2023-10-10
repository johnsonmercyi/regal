import AJAXJS from './ajaxModule.js';

const selectBtn = document.querySelectorAll('.deleteStaff');
const deleteBtn = document.querySelector('#submitRemovedStaff');
const confirmModal = document.querySelector('#confirmModal');
const removeStaffTable = document.querySelector('#removeStaffTable');
let schoolId = document.querySelector('#schoolId').value;
let deletedStaff = {schoolId, staff: []};

selectBtn && selectBtn.forEach(btn => {
    btn.addEventListener('click', function(e){
        e.preventDefault();
        
        if(btn.classList.contains('grey')){
            btn.classList.remove('grey');
            btn.classList.add('green');
        } else {
            btn.classList.remove('green');
            btn.classList.add('grey');
        }

    });
});

deleteBtn && deleteBtn.addEventListener('click', function(e){
    e.preventDefault();
    deletedStaff.staff = [];

    selectBtn.forEach(btn => {
        if(btn.classList.contains('green')){
            let staffId = btn.dataset.id;
            deletedStaff.staff.push(staffId);
        }
    });

    if(deletedStaff.staff.length > 0){
        confirmModal.querySelector('#confirmQuery').innerHTML = `Are you sure you want to delete ${deletedStaff.staff.length} staff?`;
        M.Modal.getInstance(confirmModal).open();
        // console.log(deletedStaff);
    }
})

confirmModal && confirmModal.querySelector('#yesConfirm').addEventListener('click', function(){
    confirmModal.querySelector('.progress').classList.remove('hide');
    console.log(deletedStaff)
    AJAXJS(JSON.stringify(deletedStaff), 'POST', '/staff/remove', false, (res) => {
        confirmModal.querySelector('.progress').classList.add('hide');

        if(res.removed){
            M.Modal.getInstance(confirmModal).close();
            M.toast({html: "<h5>Staff deleted successfully</h5>", classes:"green"});
            res.removed.forEach(stf => {
                removeStaffTable.querySelector(`#staff${stf}`).classList.add('hide');
            })
        }
    })
})

confirmModal && confirmModal.querySelector('#noConfirm').addEventListener('click', function(){
    M.Modal.getInstance(confirmModal).close();
})