import AJAX_OPERATION, 
{ initMaterializeComponents,
    isEmailValid, 
    resetAllFormElements, 
    OPERATION_TYPES, 
    AJAX_REQUEST_METHODS,
    AJAX_OP2 
} from '../util/utility.js';

$(document).ready(function () {

    /**guardian Object Data */
    const guardianObj = {};
    let academic_session_id = document.querySelector('#sessionId') ? document.querySelector('#sessionId').value : null;
    let school_id = document.querySelector('#schoolId') ? document.querySelector('#schoolId').value : null;
    // declare an object for all the possible forms or form content
    const formObj = {"staff":{}, "nok":{}, "guardian":{}, "std":{}, "prt":{}, "section":{}, "schDet":{ academic_session_id, school_id }};

    /**Initializes DataTable */
    
    // declare datatable for schools index
    $('#schoolData').DataTable();
    $('#staffData').DataTable();
    $('#guardianData').DataTable();

    /**Adjusts the columns to fit the container to avoid
     * page distortion and horizontal scroll
     * */
    // dataTable.columns.adjust();

    // console.log(document.querySelectorAll('.tooltipped').length);

    /**Initializes All Materialize Components */
    initMaterializeComponents();

    /**Validate Components Function */
    $('input[type="text"]').on('input', function (e) {
        if ($(this).val() === '') {
            $(this).addClass('invalid');
        } else {
            $(this).removeClass('invalid').add('valid');
        }
    });

    /**Validate email address */
    $('input[type="email"]').on('input', function (e) {
        if ($(this).val() === '' || !isEmailValid($(this).val()) ) {
            $(this).addClass('invalid');
        } else {
            $(this).removeClass('invalid').add('valid');
        }
    });

    /********SHow Progress Modal****** */
    function showProgress(){
        let modalElem = document.querySelector('#progressModal');
        const statModal = M.Modal.getInstance(modalElem);
        modalElem.querySelector('.progress').classList.remove('hide');
        statModal.open();
    }

    /*************Hide progress modal************* */
    function hideProgress(){
        let modalElem = document.querySelector('#progressModal');
        const statModal = M.Modal.getInstance(modalElem);
        modalElem.querySelector('.progress').classList.add('hide');
        statModal.close();
    }

    /**Add guardian record event handler */
    let addForm = document.querySelector('#addForm');
    addForm && addForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const form = $('#addForm');
        const formBtn = $('#addRecordSubmit');
        
        console.log('submit')
        // select the route for action 
        if (form) {
            console.log('submit')
            if(formBtn.attr('data-id') === "staff"){
                validateFormAndSubmit('/staff/store', form, OPERATION_TYPES.CREATE);
            }
            else if(formBtn.attr('data-id') === "guardian"){
                validateFormAndSubmit('/guardians', form, OPERATION_TYPES.CREATE);
            }
            else if(formBtn.attr('data-id') === "std"){
                validateFormAndSubmit('/students/store', form, OPERATION_TYPES.CREATE);
            }
            // else if($(this).attr('data-id') === "section"){
            //     validateFormAndSubmit('/section/store', form, OPERATION_TYPES.CREATE);
            // }
            else{
            }
        }

    });


    /**Add Guardian record event handler */
    $('#editRecordSubmit').on('click', function (e) {
        e.preventDefault();

        const form = $('#editForm');
        const actId = e.target.dataset.id;

        if (e.isDefaultPrevented()) {
            // if ($(this).attr('data-act') === "guardian") {
            //     validateFormAndSubmit('/guardians/'+actId, form, OPERATION_TYPES.UPDATE);
            // }
            if ($(this).attr('data-act') === "std") {
                validateFormAndSubmit('/students/'+actId, form, OPERATION_TYPES.UPDATE);
            }
            else if ($(this).attr('data-act') === "stf") {
                validateFormAndSubmit('/staff/update/'+actId, form, OPERATION_TYPES.UPDATE);
            }
            else if ($(this).attr('data-act') === "section") {
                validateFormAndSubmit('/section/'+actId, form, OPERATION_TYPES.UPDATE);
            }
            else{
                return;
            }
        }

    });

    function validateInput(newForm, formType){
        let validInput = true;
        let ignoreArr = ['stfimgFile', 
            'stffslccert', 
            'stffslcgrade', 
            'stfnysccert', 
            'stfnyscyear', 
            'oldfslcgrade', 
            'stfsignature', 
            'stfsscebody2', 
            'stfsscecert2'];
            
        for(let [key, val] of newForm.entries()){
            // console.log(key, val);
            let inputItem = document.querySelector(`input[name=${key}]`);

            if(!inputItem){
                inputItem = document.querySelector(`select[name=${key}]`);
            }
            // if(!inputItem){
            //     inputItem = document.querySelector(`input[name=${key}]`);
            // }
            inputItem && (inputItem.classList.remove('invalidBorder'));
            document.querySelector(`#${key}`) && document.querySelector(`#${key}`).classList.remove('invalidBorder');
            if(!ignoreArr.includes(key)  && inputItem.type == 'file' && inputItem.files.length == 0 && formType == 'reg'){
                // console.log(key)
               (document.querySelector(`#${key}`) && document.querySelector(`#${key}`).classList.add('invalidBorder'));
              validInput = false;                            
            }else if( !ignoreArr.includes(key)  && inputItem.type != 'file' && val.trim() == ''){
                validInput = false;                            
                inputItem && (inputItem.classList.add('invalidBorder'));
            }
        }
        return validInput;
    }

    function validStudent(stdform){
        let ignoreArr = ['stdphysicalchallenge', 'stdhealthchallenge', 'stdphoneNo', 'stdimgFile', 'stddoa', 'stdotherName'];
        let valid = true;

        let fatherDetails = stdform.filter(({name, value}) => ['prtfather_firstName', 'prtfather_lastName', 'prtfather_phoneNo'].includes(name) && value == '').length;
        let motherDetails = stdform.filter(({name, value}) => ['prtmother_firstName', 'prtmother_lastName', 'prtmother_phoneNo'].includes(name) && value == '').length;
        if(fatherDetails > 0 && motherDetails > 0){
            valid = false;
            M.toast({html: "<h5>Please Father or Mother is required</h5>", classes: 'red rounded'}, 4000);
        }
        
        stdform.forEach(({name, value}) => {
            let invalidElem = document.querySelector(`input[name=${name}]`);
            if(!invalidElem) {
                invalidElem = document.querySelector(`select[name=${name}]`)
            }
            invalidElem && invalidElem.classList.remove('invalidBorder');
            if(!ignoreArr.includes(name) && name.substr(0, 3) == 'std' && value.trim() == ''){
                valid = false;
                invalidElem && invalidElem.classList.add('invalidBorder');
            }
        })

        return valid
    }

    /**
     *
     * @param {JQuery<HTMLElement>} form
     */
    const validateFormAndSubmit = (actionURL, form, operationType) => {

        const form_data = form.serializeArray();
        let error_free = false;
        let validCount = 0;
        let newForm = null;

        if(actionURL == "/staff/store") newForm = new FormData(document.querySelector('#addForm'));
        if(actionURL.substr(0, 13) == "/staff/update") newForm = new FormData(document.querySelector('#editForm'));

        for (var input in form_data) {

            let element = $('#' + form_data[input]['name']); //Gets form element id
            
            var valid = element.is('select') ? true
                        : (element.hasClass("valid") ? true : element.val() !== '');

            if (valid) {
                element.removeClass('invalid').addClass('valid');
                validCount++;
            } else {
                element.removeClass('valid').addClass('invalid');
            }

            if ((form_data.length) === validCount) {
                error_free = true;
            }

        }

        // $.each(form.find('input[type="file"]'), function(i, tag){
        //     $.each($(tag)[0].files, function(i, file){
        //         form_data[tag.name] = file
        //     });
        // });
        const successResFxn = res => {
            // M.Modal.getInstance(document.querySelector('#progressModal')).close();
            const statModal = document.querySelector('#disModal');
            statModal.querySelector('.progress').classList.add('hide');
            let otherName = res.otherName ? res.otherName : (res.otherNames ? res.otherNames : '');
            const staffRes = `
                <div class="col m4">
                    <img src="/storage/images/${res.prefix}/passports/${res.img}" />
                </div>
                <div class="col m8 center">
                    <p><span class="titext">Reg. Number:</span> ${res.regNo} </p>
                    <p><span class="titext">Name:</span> ${res.lastName+' '+res.firstName+' '+otherName} </p>
                    ${res.model == 'student' ?
                        `<p><span class="titext">Class:</span> ${res.classroom} </p>`
                        :
                        `<p><span class="titext">Phone Number:</span> ${res.phoneNo} </p>`                                            
                    }
                    
                    <canvas id="qr-code">
                    </canvas>
                </div>
            `;            
            let profileLink = res.model == 'staff' ? `/${school_id}/staff/${res.id}/details` : `/${school_id}/student/${res.id}`;
            statModal.querySelector('#profileLink').setAttribute('href', profileLink);
            statModal.querySelector('#modalSuccess').classList.remove('hide');
            statModal.querySelector('#modalSuccess').innerHTML += staffRes;
            M.Modal.getInstance(statModal).open();
            M.Modal.getInstance(document.querySelector('#progressModal')).close();
            // (function() {
            //     new QRious({
            //     element: document.querySelector('#qr-code'),
            //     size: 200,
            //     value: window.location.hostname + `/signature-pad/staff-profile-signature.php?StaffID=${res.id}`
            //     });
            // })();
        };

        if (error_free) {
            /**
             * TODO: Do some POST Ajax Request here!
             */
            console.log(form_data);
            // console.log(Array.from(newForm.entries()));
            $.each(form_data, function(i, field){
                if(field.name.substring(0,3) === 'std'){                        
                    formObj.std[field.name.substring(3)] = field.value;
                }
                // else if(field.name.substring(0,3) === 'nok'){            
                //     formObj.nok[field.name.substring(3)] = field.value;
                // }
                // else if(field.name.substring(0,3) === 'stf'){            
                //     formObj.std[field.name.substring(3)] = field.value;
                // }
                else if(field.name.substring(0,3) === 'prt'){            
                    formObj.prt[field.name.substring(3)] = field.value;
                }
                else if(field.name.substring(0,3) === 'sec'){            
                    formObj.section[field.name.substring(3)] = field.value;
                }
                else{    
                    formObj.guardian[field.name] = field.value;
               }
            });

            
            // console.log(formObj)
            // showProgress()

            if (operationType === OPERATION_TYPES.CREATE) {
                // console.log(guardianObj);

                // let sentData = newForm != null ? newForm : JSON.stringify(formObj);
                if(newForm){
                   
                    if(!validateInput(newForm, 'reg')) {
                        M.toast({html:`<h5>Please enter all required fields!</h5>`, classes:"red"}, 3000);
                        return;
                    }

                    showProgress()
                    // M.Modal.init(progModal).open();

                    AJAX_OP2(actionURL, AJAX_REQUEST_METHODS.POST, newForm,
                        function (res, status, xhr) {
                            // console.log(response);
                            // Initialize show Dialog Components and Display
                            if(res.status && res.status == "Error"){
                                M.toast({html: "<h5>An error occured. Please check your form.</h5>", classes: "red rounded"}, 3000);
                                // M.Modal.init(progModal).close();
                                hideProgress();
                                return;
                            }
                            
                            successResFxn(res);
                        }, function (xhr, status, error) {
                            M.toast({html:"<h5>Unable to Create. Please Check the Form and Try Again</h5>", classes:"red"}, 4000);
                            hideProgress();

                            console.log("Server Error Response:\n" + error);

                            /**HANDLE ERROR HERE... */
                        }
                    );
                } else {
                    if(!validStudent(form_data)){
                        M.toast({html:"<h5>Please enter all required fields</h5>", classes:"red rounded"});
                        return;
                    }
                    showProgress()
                    // M.Modal.init(progModal).open();

                    AJAX_OPERATION(actionURL, AJAX_REQUEST_METHODS.POST, JSON.stringify(formObj), function(res){
                        //
                        if(res.status && res.status == "Error"){
                            M.toast({html: "<h5>An error occured. Please check your form.</h5>", classes: "red rounded"}, 3000);
                            return;
                        }
                        console.log('sent');
                        // M.Modal.init(progModal).close();
                        hideProgress();

                        successResFxn(res);
                    }, function(){
                        console.error("Error!");
                        hideProgress();
                    });
                }

            } else if (operationType === OPERATION_TYPES.UPDATE) {
                if(newForm){
                   
                    if(!validateInput(newForm, 'edit')) {
                        M.toast({html:`<h5>Please enter all required fields!</h5>`, classes:"red"}, 3000);
                        return;
                    }

                    // M.Modal.init(progModal).open();
                    showProgress()
                    AJAX_OP2(actionURL, AJAX_REQUEST_METHODS.POST, newForm,
                        function (res, status, xhr) {
                            // console.log(response);
                            // Initialize show Dialog Components and Display
                            if(res.status && res.status == "Error"){
                                M.toast({html: "<h5>An error occured. Please check your form.</h5>", classes: "red rounded"}, 3000);
                                // M.Modal.init(progModal).close();
                                hideProgress();
                                return;
                            }
                            
                            // M.Modal.init(progModal).close();
                            hideProgress();
                            M.toast({html: "<h5>Staff Details Updated Successfully.</h5>", classes: "green rounded"});
                            window.location.href = `/${school_id}/staff/${res.id}/details`

                            // successResFxn(res);
                        }, function (xhr, status, error) {
                            M.toast({html:"<h5>Unable to Update. Please Check the Form and Try Again</h5>", classes:"red"}, 4000);
                            hideProgress();

                            console.log("Server Error Response:\n" + error);

                            /**HANDLE ERROR HERE... */
                        }
                    );
                } else {

                    if(!validStudent(form_data)){
                        M.toast({html:"<h5>Please enter all required fields</h5>", classes:"red rounded"});
                        return;
                    }
                    showProgress()

                    AJAX_OPERATION(actionURL, AJAX_REQUEST_METHODS.PATCH, JSON.stringify(formObj),
                        function (res, status, xhr) {
                            
                            if(res.status == 'success'){
                                if(res.type == 'student'){
                                    M.toast({html:"<h5>Student Details Updated Successfully!</h5>", classes:"green rounded"}, 2000);
            
                                    hideProgress();
                                    window.location.href = `/${res.resobj.school_id}/student/${res.resobj.id}`
                                    // console.log(status);
                                    // console.log(xhr);
                                }
                            }
                        }, function (xhr, status, error) {
                            M.toast({html:"<h5>Unable to Edit. Please Check the Form and Try Again</h5>", classes:"red"}, 4000);
                            hideProgress();

                            console.log("Server Error Response:\n" + error);

                            /**HANDLE ERROR HERE... */
                        }
                    );
                }

            } else {

            }

        }
    }

    /**Displays Guardian details after registration */
    const showGuardianDetailsAfterOperation = (response, operation) => {

        const resobj = response.resobj;

        if(!resobj){M.toast({html:"<h5>Unable to Create. Please Check the Form and Try Again</h5>", classes:"red"}, 3000); return false;}

        if (operation === OPERATION_TYPES.CREATE) {

            $('#showGuardianModal').find('#name').html((resobj.title ? resobj.title : '' )+ ' ' + resobj.firstName + ' ' + resobj.lastName + ' ' + resobj.otherName);
            $.each(resobj, function(i, field){
                $('#showGuardianModal').find('#'+i).html(field);
                $('#showGuardianModal').find('#'+i).parent().removeClass('hide');
            });
            /*
            $('#showGuardianModal').find('#maritalStatus').html(guardian.maritalStatusId);
            $('#showGuardianModal').find('#Email').html(guardian.Email);
            $('#showGuardianModal').find('#phone').html(guardian.phoneNo);
            $('#showGuardianModal').find('#occupation').html(guardian.occupation);
            $('#showGuardianModal').find('#officePhone').html(guardian.officePhone);
            $('#showGuardianModal').find('#officeAddress').html(guardian.officeAddress);
            */
            if ($('#showGuardianModal').hasClass('hide')) {
                $('#showGuardianModal').removeClass('hide').addClass('show');
                $('#showGuardianModal').find('#m-foot').addClass('hide');
            }

            /**Set achor tags href attributes */
            // $('#showGuardianModal').find("#editGuardianLink")
            //     .attr('href', '/guardians/' + guardian.id + '/edit');

            $('#showGuardianModal').find("#addGuardianLink")
                .attr('href', '/guardians/create');


            $('#showGuardianModal').modal('open');

        } else if (operation === OPERATION_TYPES.UPDATE) {

            /**Hides Uneccessary controls */
            M.toast({html:"<h5>Profile Successfully Updated</h5>", classes:"green"}, 4000);
            /*$('#showGuardianModal').find('#editGuardianLink').hide();
            $('#showGuardianModal').find('#addGuardianLink').hide();

            $('#showGuardianModal').find('#name').html((resobj.title ? resobj.title : '' )+ ' ' + resobj.firstName + ' ' + resobj.lastName + ' ' + resobj.otherName);
            $.each(resobj, function(i, field){
                $('#showGuardianModal').find('#'+i).html(field);
                $('#showGuardianModal').find('#'+i).parent().removeClass('hide');
            });
            if ($('#showGuardianModal').hasClass('hide')) {
                $('#showGuardianModal').removeClass('hide').addClass('show');
                $('#showGuardianModal').find('#m-foot').addClass('hide');
            }*/

            /*$('#showGuardianModal').find('#header').html(guardian.title + ' ' + guardian.firstName + ' ' + guardian.lastName + ' ' + guardian.otherName + '\'s Record is Updated!');

            $('#showGuardianModal').find('#name').html(guardian.title + ' ' + guardian.firstName + ' ' + guardian.lastName + ' ' + guardian.otherName);
            $('#showGuardianModal').find('#maritalStatus').html(guardian.maritalStatusId);
            $('#showGuardianModal').find('#Email').html(guardian.Email);
            $('#showGuardianModal').find('#phone').html(guardian.phoneNo);
            $('#showGuardianModal').find('#occupation').html(guardian.occupation);
            $('#showGuardianModal').find('#officePhone').html(guardian.officePhone);
            $('#showGuardianModal').find('#officeAddress').html(guardian.officeAddress);
            */

            // if ($('#showGuardianModal').hasClass('hide')) {
            //     $('#showGuardianModal').removeClass('hide').addClass('show')
            // }

            /**Set achor tag attribute */
            // $('#showGuardianModal').find("#close").attr('href', '/schools/'+resobj.schoolId+'/guardians');

            // $('#showGuardianModal').modal('open');

        } else {

            /**Change Text Style */
            $('#showGuardianModal').find('#header')
                .removeClass(['red-text', 'text-darken-1'])
                .addClass(['green-text', 'text-darken-1', 'center-align']);

            /**Hides Uneccessary controls */
            $('#showGuardianModal').find('#content').remove();
            $('#showGuardianModal').find('#editGuardianLink').hide();
            $('#showGuardianModal').find('#addGuardianLink').hide();

            /**Shows this control */
            $('#showGuardianModal').find('#viewGuardiansLink').show();

            /**Sets this control text */
            $('#showGuardianModal').find('#viewGuardiansLink').html("Close");

            $('#showGuardianModal').find('#header').html(response);

            if ($('#showGuardianModal').hasClass('hide')) {
                $('#showGuardianModal').removeClass('hide').addClass('show')
            }

            /**Set achor tag attribute */
            // $('#showGuardianModal').find("#close").attr('href', '/guardians');

            $('#showGuardianModal').modal('open');

        }

    }

    /**Displays Guardian Details */
    const showGuardianDetails = (guardian, operation) => {

        // console.log(guardian);

        if (operation === OPERATION_TYPES.DELETE) {

            /**Removes Default Text Style */
            $('#showGuardianModal').find('#header').removeClass('green-text').addClass('red-text');

            /**Hides Uneccessary controls */
            $('#showGuardianModal').find('#viewGuardiansLink').hide();

            /**Set Show Dialog elements' value */
            $('#showGuardianModal').find('#header').html("Delete " + guardian.name + '\'s Record?');

            $('#showGuardianModal').find('#name').html(guardian.name);
            $('#showGuardianModal').find('#maritalStatus').html(guardian.maritalStatusId);
            $('#showGuardianModal').find('#Email').html(guardian.Email);
            $('#showGuardianModal').find('#phone').html(guardian.phone);
            $('#showGuardianModal').find('#occupation').html(guardian.occupation);
            $('#showGuardianModal').find('#officePhone').html(guardian.officePhone);
            $('#showGuardianModal').find('#officeAddress').html(guardian.officeAddress);

            /**Set achor tag attribute */
            $('#showGuardianModal').find("#editGuardianLink").attr('href', 'javacript:void(0)');
            $('#showGuardianModal').find("#editGuardianLink").attr('data-id', guardian.id);
            $('#showGuardianModal').find("#editGuardianLink").html('Delete');

            $('#showGuardianModal').find("#addGuardianLink").attr('href', 'javacript:void(0)');
            $('#showGuardianModal').find("#addGuardianLink").html('Cancel');

            $('#showGuardianModal').modal('open');

        } else{

            /**Removes Default Text Style */
            const header = $('#showGuardianModal').find('#header')
            if (header.hasClass('green-text') || header.hasClass('red-text')) {
                header.removeClass(['green-text', 'red-text'])
            }

            /**Hides Uneccessary controls */
            $('#showGuardianModal').find('#viewGuardiansLink').hide();
            $('#showGuardianModal').find('#addGuardianLink').hide();

            /**Set Show Dialog elements' value */
            header.html(guardian.name + '\'s Details');

            $('#showGuardianModal').find('#name').html(guardian.name);
            $('#showGuardianModal').find('#maritalStatus').html(guardian.maritalStatusId);
            $('#showGuardianModal').find('#Email').html(guardian.Email);
            $('#showGuardianModal').find('#phone').html(guardian.phone);
            $('#showGuardianModal').find('#occupation').html(guardian.occupation);
            $('#showGuardianModal').find('#officePhone').html(guardian.officePhone);
            $('#showGuardianModal').find('#officeAddress').html(guardian.officeAddress);

            /**Set achor tag attribute */
            // $('#showGuardianModal').find("#editGuardianLink").attr('href', '/guardians/' + guardian.id + '/edit');

            $('#showGuardianModal').modal('open');

        }

    }

    function getViewLink (row) {
        return `<a href="#showGuardianModal" class="btn btn-floating waves-effect waves-light red lighten-1 col s6 modal-trigger viewGuardian" id="viewGuardian"` + row.id + ` data-id=` + row.id + `><i class="material-icons">pageview</i></a>`;
    }

    function getEditLink (row) {
        return `<a href="/guardians/` + row.id + `/edit" data-id=` + row.id + ` class="btn btn-floating waves-effect waves-light red lighten-1 col s6 editGuardian"><i class="material-icons">edit</i></a>`;
    }

    function getDeleteLink (row) {
        return `<a href="#showGuardianModal" class="btn btn-floating waves-effect waves-light red lighten-1 col s6 modal-trigger deleteGuardian" data-id=` + row.id + ` id='deleteGuardian'` + row.id + `><i class="material-icons">delete</i></a>`;
    }

});
