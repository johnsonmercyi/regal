$(function(){

const fileForm = new FormData();

$("#submitFileForm").on('click', function(e){
    e.preventDefault();
    const newForm = $('form');
    const fileForm2 = new FormData(newForm[0]);
    // const checkError = validateFormInputs(newForm.serializeArray());
    const checkError = validateFormDataInputs(fileForm2);
    if(checkError == false){
        // alert('Error');
        return false;
    } else {
        alert('Submitted');
        SUBMITFILEFORM('/section/store', fileForm2, function(){}, function(){});        
    }
});

// const validateFormInputs = (formArr)=>{
//     let errorStatus = false;
//     let validCount = 0;
//     for (const input in formArr) {

//         let element = $(`#${formArr[input]['name']}`); //Gets form element id
        
//         let valid = element.is('select') ? true
//                     : (element.hasClass("valid") ? true : element.val() !== '');

//         if (valid) {
//             element.removeClass('invalid').addClass('valid');
//             validCount++;
//         } else {
//             element.removeClass('valid').addClass('invalid');
//         }

//     }
//     if ((formArr.length) === validCount) {
//         errorStatus = true;
//     }
//     return errorStatus;
// }

/************VALIDATE THE FORMDATA INPUTS**************** */
const validateFormDataInputs = (formArr)=>{
    let errorStatus = false;
    let valid = false;
    let validCount = 0;
    for (const[key, input] of formArr.entries()) {

        let element = $(`#${key}`); //Gets form element id

        if(input instanceof File){
            valid = input.name == "" ? false : true;
        } else {
            valid = element.is('select') ? true
                    : (element.hasClass("valid") ? true : element.val() !== '');
        }

        if (valid) {
            element.removeClass('invalid').addClass('valid');
            validCount++;
        } else {
            element.removeClass('valid').addClass('invalid');
        }

    }
    if (Array.from(formArr.values()).length === validCount) {
        errorStatus = true;
    }
    return errorStatus;
}

/*****************HANDLE FILE INPUT VALIDATION******************* */
const validateFileInput = ()=>{
    let checkFile = false;
    $('#checkfile').modal('open');
    checkFile = $('#checkfile #yesbtn').on('click', function(ev){ 
        ev.preventDefault();
        $('#checkfile').modal('close'); 
        // return true;
    });
    $('#checkfile #nobtn').on('click', function(ev){
        ev.preventDefault();
        $('#checkfile').modal('close'); 
    });
    return checkFile;
}


/*******HANDLE THE FILE INPUTS TO THE FORM NOW REDUNDANT******* */
const handleFileInputs = (formObject)=>{
    $.each(formObject.find('input[type="file"]'), function(i, tag){
        // console.log($(tag)[0].files);
        if($(tag)[0].files.length > 0){
            $.each($(tag)[0].files, function(i, file){
                // fileForm.append(tag.name, file)
            })
        } else {
            $(`#${tag.name}`).addClass('invalid');
        }
    })
}


const SUBMITFILEFORM = (actionURL, fileFormData, successFxn, errorFxn)=>{
    
    return $.ajax({
        
        type:'POST',
        url: actionURL,
        data:fileFormData,
        cache:false,
        dataType:'JSON',
        contentType: false,
        processData: false,
        beforeSend: function (xhr, type) {
            if(!type.crossDomain) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            }
        }, 
        successFxn, 
        errorFxn
    }); 

}

});