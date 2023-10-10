import AJAX_OPERATION, {AJAX_REQUEST_METHODS} from '../util/utility.js';
import AJAXJS from './ajaxModule.js';
$(function(){

const schoolColor = document.querySelector('#schoolColor') ? document.querySelector('#schoolColor') .value : null;
const schoolId = document.querySelector('#schoolId') ? document.querySelector('#schoolId').value : null;
// const traitArr = [];
const ratingArr = [];
const traitAssessArr = [];
let idNum = 1;
const traitFormObj = {};
let globalOldStudentTraits = [];        //using let for this array

$('#cat1').on('click', function(e){
    e.preventDefault;
    $('#socialSection').removeClass('hide');
    $('#motorSection').addClass('hide');
})

$('#cat2').on('click', function(e){
    e.preventDefault;
    $('#socialSection').addClass('hide');
    $('#motorSection').removeClass('hide');
})

$('#successModal #close').on('click', function(){
    $('successModal').modal('close');
});

$('#socialBtn').on('click', function(e){
    let traitArr = packTraits();
    // if(traitArr.length < 1){
    //     alert('Please select the required traits');
    //     return false;
    // }
    console.log(traitArr);
    $('#socialSection .progress').removeClass('hide');
    insertTraitObj(`/${traitArr.schoolId}/trait/manage`, traitArr, '<h5>Selected Traits Inserted Successfully!</h5>');
});

$('#motorBtn').on('click', function(e){
    let traitArr = packTraits();
    $('#motorSection .progress').removeClass('hide');
    console.log(traitArr);
    insertTraitObj(`/${traitArr.schoolId}/trait/manage`, traitArr, '<h5>Selected Traits Inserted Successfully!</h5>');
});

$('.checkbtn').each(toggleTrait);

$('#addTraitFormatRow').on('click', function(){
    idNum < 8 && $('#traitFormatTable').append(addNewRow(++idNum));
});

$('#editTraitRating').on('click', function(e){
    e.preventDefault;
    $('#traitIndexTable').addClass('hide');
    $('#traitEditDiv').removeClass('hide');
})

$('#submitTraitFormat').on('click', function(){
    packRating('#traitFormatTable tbody tr');
    if(ratingArr.length < 1){
        alert('Please fill in the ratings');
        return false;
    }
    $('.progress').removeClass('hide');
    $(this).attr('disabled', 'true');
    insertTraitObj(`/${ratingArr[0].schoolId}/trait/format`, ratingArr, '<h5>Trait Rating Format Submitted Successfully</h5>');
    ratingArr.length = 0;
});

//button click action for fetching students list for selected variables
$('#loadClassStudentsTraits').on('click', function(e){
    e.preventDefault();
    let validateFormCount = 0;
    // allScores.length = 0;
    // GET THE VARIABLES TO SELECT A CLASS FOR SCORE ENTRY
    const form = $('#makeTraitAssess');    
    const form_data = form.serializeArray();
    $.each(form_data, function(i, field){                       
        traitFormObj[field.name] = field.value;
    });
    
    // CHECK THE SELECTED VARIABLES (CLASS, SESSION, TERM, SUBJECT) AND THROW ERROR
    $.each(traitFormObj, function(i, field){
        if(field === ""){
            alert(`Please select a ${(i.substring(0, i.length-2)).toUpperCase()}!`);
            return;        
        } else {
            validateFormCount++;
        }
        // $("#scoreTable").removeClass("hide");
    });

    let traitFormIsValid = validateFormCount !== Object.entries(traitFormObj).length ? false : true;
    if(traitFormIsValid){
        $('.progress').removeClass('hide');
        // for(const value of globalOldStudentTraits){
        //     globalOldStudentTraits.pop(value);
        // }
        globalOldStudentTraits.length = 0
        getClassStudentsTraits('/traits/students/'+ schoolId + '/' +traitFormObj.classId , traitFormObj);
    }
    // $("#scoreTable").addClass("hide");
    // $('#submitScores').attr("disabled", false);
    // $('#submitScores').removeClass("hide");
});
/***************END OF BUTTON FOR STUDENT LOAD**************** */

/***************BUTTON CLICK TO SUBMIT STUDENT TRAIT ASSESSMENT*********************** */
$('#packTraitAssessment').on('click', function(e){
    e.preventDefault;
    const studentTraitAssess = packTraitAssessment();
    // console.log('traitAssessArr');
    //REMOVE OLD STUDENT TRAIT FROM GLOBALOLDSTUDENTTRAIT ARRAY
    // for(const value of globalOldStudentTraits){
    //     if(value.studentId == studentTraitAssess.studentId){
    globalOldStudentTraits = globalOldStudentTraits.filter(trait=> trait.studentId != studentTraitAssess.studentId)
    //     }
    // }
    // console.log(globalOldStudentTraits);
    $('#traitModal .progress').removeClass('hide');
    insertTraitObj('/traitAssessment/store', studentTraitAssess, '<h5>Student trait Inserted Successfully</h5>');
})

function toggleTrait(e){
    e.preventDefault;
    $(this).on('click', function(){
        $(this).toggleClass('grey');
        $(this).toggleClass('black');
        // if($(this).hasClass('black')){
        //     $(this).attr('data-check', 'true');
        // }else{
        //     $(this).attr('data-check', 'false');            
        // }    //refactored to below line
        $(this).attr('data-check', $(this).hasClass('black') ? 'true' : 'false');
    })
}

function packTraits(){
    let traitObj = {
        schoolId,
        traitId: {}
    };
    let selectedTraits = [];
    $('#socialTB tr').each(function(){
        if($(this).find('button').attr('data-check') == 'true'){
            selectedTraits.push($(this).attr('id'))
        }
    });
    $('#motorTB tr').each(function(){
        if($(this).find('button').attr('data-check') == 'true'){
            selectedTraits.push($(this).attr('id'))
        }
    });
    traitObj.traitId = JSON.stringify({traits: selectedTraits});
    return traitObj;
}

function packRating(rowsPicker){
    const ratingRow = $(rowsPicker);
    ratingRow.each(function(){
        const ratingObj = {};
        if($(this).find('.description').val()){
        ratingObj['school_id'] = schoolId;
        ratingObj['description'] = $(this).find('.description').val();
        ratingObj['rating'] = $(this).find('.rating').val();
        ratingArr.push(ratingObj);
        }
    });
}

/**********AJAX FUNCTION FOR INSERTING TRAIT INTO DB ************************* */
const insertTraitObj =  (actionURL, traitObj, successText)=>{
    AJAX_OPERATION(actionURL, AJAX_REQUEST_METHODS.POST, JSON.stringify(traitObj),
    function (response, status, xhr) {
        // response.successInfo && console.log("Success");
        // if(response.successInfo){
        //     $('#successModal #successText').text(successText);
        //     $('#successModal').modal('open');
        // }
        if(response.successInfo){
            $('.progress').addClass('hide');
            M.toast({html: successText, classes: "green"}, 3000);
        } else if(response.successInfo == false) {
            $('.progress').addClass('hide');
            M.toast({html: '<h5>Unable to Update Traits</h5>', classes: "red"}, 3000);
        }

        if(response.studentTrait){
            globalOldStudentTraits.push(response.studentTrait);
            $('#traitModal .progress').addClass('hide');
            $('#traitModal').modal('close');            
            M.toast({html: '<h5>Trait Assessment Inserted Successfully</h5>', classes: "green"}, 3000);
        }

    }, function (xhr, status, error) {
        console.log("Server Error Response:\n" + error);
        /**HANDLE ERROR HERE... */
    }
    );
}
/**********END OF AJAX FUNCTION FOR INSERTING TRAIT INTO DB ********************* */

/*****************AJAX operation for fetching students from the database************************/
const getClassStudentsTraits =  (actionURL, traitLoadFormObj)=>{
    AJAX_OPERATION(actionURL, AJAX_REQUEST_METHODS.POST, JSON.stringify(traitLoadFormObj),
    function (response, status, xhr) {
        // Initialize show Dialog Components and Display
        // showGuardianDetailsAfterOperation(response, operationType);
        $('.progress').addClass('hide');
        $("#tableError").addClass("hide");
        $("#traitTable").removeClass("hide");
        if(response.traitFormat.length === 0){
            $("#successModal").modal('open');
            $("#successModal .modal-content h2").html('No Trait Rating Format Found.<br> Please Create a Format!');
        }
        fillTable(response.students);
        // ADD THE RETURNED STUDENT TRAITS TO A GLOBAL ARRAY
        globalOldStudentTraits = [...response.studentsOldTraits];
        $('#traitTable .assessStartBtn').each(function(){
            $(this).on('click', function(e){
                e.preventDefault;
                const stdObj = {};
                stdObj.stdName = $(this).parents('tr').find('.nameCell').text();
                stdObj.stdId = $(this).parents('tr').attr('id');
                // console.log(response.studentsOldTraits)
                console.log(globalOldStudentTraits)
                // const currentOldStudentTraits = [...globalOldStudentTraits, ...response.studentsOldTraits];
                const stdOldTraitObj = globalOldStudentTraits.filter(trait => parseInt(trait.student_id) === parseInt(stdObj.stdId));
                const stdTraitObj = stdOldTraitObj.length !== 0 ? JSON.parse(stdOldTraitObj[0].traitRating) : {};
                // console.log(stdTraitObj)
                // const stdTraitObj = {};
                fillTraitModal(response.traitFormat, response.schoolTraits, stdTraitObj, stdObj);
            })
        });
        const tblrows = $('#traitTable tbody tr');
        // tblrows.each(calcScore);
        //find update buttons and set their function
        // const upBtn = $('#scoreTable tbody tr button');
        // upBtn.each(updateStudentScore);
        
    }, function (xhr, status, error) {
        $('.progress').addClass('hide');
        console.log("Server Error Response:\n" + error);
        /**HANDLE ERROR HERE... */
        $("#tableError").removeClass("hide");
    }
    );
}
/**********END OF AJAX FUNCTION FOR LOADING STUDENTS****************** */

/*************FUNCTION TO ADD STUDENT RECORDS TO TABLE************************* */
const fillTable = (studentList)=>{
    let studentSN = 1;
    // console.log([...studentList].sort(alphaSort));
    let rowString = studentList.sort(alphaSort).map( student => 
        `
        <tr id="${student.id}"><td>${studentSN++}</td>
        <td class="nameCell">${(student.lastName+' '+student.otherName+' '+student.firstName).toUpperCase()}</td>
        <td>${student.regNo}</td>
        <td><button class="btn colCode assessStartBtn">Assess</td>
        </tr>`
    ).toString();
    $('#traitTable tbody').html(rowString);
}

/**************FUNCTION TO ADD CHECKBOX CELLS FOR RATINGS********************* */
const ratingCells = (ratingList, oldTraits, traitId)=>{
    let ratingCells = ``;
    // console.log(oldTraits)
    if(Object.values(oldTraits).length === 0){
        ratingCells = ratingList.map( rating => makeBtn(rating, "grey", "false")).toString();
    } else {
        ratingCells = ratingList.map( rating => rating.rating == oldTraits[traitId] ? makeBtn(rating, "green", "true") : makeBtn(rating, "grey", "false")).toString();        
    }
    // } else {
    //     console.log(Object.values(oldTraits))
    //     ratingCells = ratingList.map( rating => {
    //         for(const value of Object.values(oldTraits)){
    //             console.log(value, rating.rating)
    //             if(value == rating.rating){
    //                 return makeBtn(rating, "green", "true")
    //             } else {
    //                 return makeBtn(rating, "grey", "false")
    //             }
    //         }                
    //     }).toString();
    return ratingCells;
}

/***********FUNCTION TO MAKE TABLE DATA BUTTON**************** */
function makeBtn(rating, colour, check){
    return `
        <td>
            <button data-rating="${rating.rating}" class="btn btn-floating ${colour} traitAssessBtn" data-check="${check}">
            <i class="material-icons">check</i>
            </button>
        </td>
    `
}

/*************FUNCTION TO CREATE MODAL FOR STUDENT TRAIT ASSESSMENT************************** */
const fillTraitModal = (ratingList, schoolTraits, oldTraits, stdObj)=>{
    let ratingListString = ratingList.map( rating => `<th>${rating.description}</th>`).toString();
    let traitTableHead = `<th>Name</th>${ratingListString}`;
    let schoolTraitsString = schoolTraits.map( trait => `
        <tr id='${trait.id}'>
        <td>${trait.name}</td>
        ${ratingCells(ratingList, oldTraits, trait.id)}
        </tr>
    `).toString();

    $('#traitModal .stdInfo').text(stdObj.stdName);
    $('#traitModal table').attr('data-id', stdObj.stdId);
    $('#traitModal thead').html(traitTableHead);
    $('#traitModal tbody').html(schoolTraitsString);
    $('#traitModal tbody tr button').each(makeRadio);
    // console.log(schoolTraitsString)
    $('#traitModal').modal('open');
}

/***********THIS FXN MAKES EACH CHECKBTN ACT AS A RADIO INPUT*********************** */
function makeRadio(){
    $(this).on('click', function(e){
        e.preventDefault;
        let checkSelect = $(this).hasClass('green') ? true : false;
        if(checkSelect){
            $(this).parents('tr').find('button').each(function(){
                $(this).removeClass('green');
                $(this).addClass('grey');
                $(this).attr('data-check', 'false');
            });
        } else {
            $(this).parents('tr').find('button').each(function(){
                $(this).removeClass('green');
                $(this).addClass('grey');
                $(this).attr('data-check', 'false');
            });
            $(this).removeClass('grey');
            $(this).addClass('green');
            $(this).attr('data-check', 'true');
        }
    });
}

/**********THIS FXN WAS SUPPOSED TO REFACTOR THE ABOVE BUT BINDING THIS WAS A PROB************** */
function actRadio(){
    console.log('act');
    $(this).parents('tr').find('button').each(function(){
        $(this).removeClass('black');
        $(this).attr('data-check', 'false');
    });
}


/***********FUNCTION TO PACK THE TRAIT ASSESSMENT FOR A STUDENT FROM THE ASSESSMENT MODAL**************** */
const packTraitAssessment = ()=>{
    const traitRows = $('#traitModal tbody tr');
    const studentTraitAssessObj = {};
    // studentTraitAssessObj.traitId = $(this).attr('id');
    studentTraitAssessObj.classId = traitFormObj.classId;
    studentTraitAssessObj.sessionId = traitFormObj.sessionId;
    studentTraitAssessObj.termId = traitFormObj.termId;
    studentTraitAssessObj.schoolId = schoolId;
    studentTraitAssessObj.studentId = parseInt($('#traitModal table').attr('data-id'));
    const traitRating = {};
    traitRows.each(function(){
        let findSelectedTrait = '';
        $(this).find('button').each(function(){
            if($(this).attr('data-check') === "true"){
                findSelectedTrait = $(this).attr('data-rating');
            }
        });
        traitRating[$(this).attr('id')] = findSelectedTrait;
    });
    studentTraitAssessObj.traitRating = JSON.stringify(traitRating);
    return studentTraitAssessObj;
}

/***********FUNCTION TO PICK THE TRAITS FOR EACH ROW**************** */
const packTraitRow = {};


/***********FUNCTION TO HELP SORT ARRAY OF STUDENTS LIST********* */
const alphaSort = (student1, student2) => {
    if(student1.lastName > student2.lastName){
        return 1;
    } else if(student1.lastName < student2.lastName) {
        return -1;
    } else {
        return 0;
    }
    };
/*************** */

/*************FUNCTION TO ADD NEW ROW****************************** */
const addNewRow = num => `<tr><td>${num}</td>
    <td><input type="text" class="description" style='background-color:#ddd;border-radius:5px' /></td>
    <td><input type="number" class="center rating" 
    style='width:50px;background-color:#ddd;border-radius:5px' /></td>
    </tr>`;

});

let storeEditBtn = document.querySelector('#storeEditedTraitRating');
let schoolId = document.querySelector('#schoolId').value;
let editArr = {schoolId, traits: []};
let ratingTable = document.querySelector("#traitEditTable tbody");
let addRatingBtn = document.querySelector("#addRatingBtn ");

storeEditBtn && storeEditBtn.addEventListener('click', function(e){
    e.preventDefault();
    let valid = true;

    document.querySelectorAll('#traitEditTable tbody tr').forEach(r => {
        let id = r.dataset.id;
        let desc = r.querySelector('.desc').value;
        let rating = r.querySelector('.rating').value;

        if(desc == '' || rating == ''){
            alert('Please fill in all fields!');
            valid = false;
            return;
        }

        editArr.traits.push({id, desc, rating});
    });

    console.log(editArr);
    storeEditBtn.setAttribute('disabled', 'true')
    AJAXJS(JSON.stringify(editArr), 'POST', '/traits/update', false, (res) => {
        console.log(res)
        storeEditBtn.removeAttribute('disabled')
        if(res.status){
            M.toast({html: "<h5>Edited successfully!</h5>", classes:'green'}, 3000)
        }
    })
});

addRatingBtn && addRatingBtn.addEventListener('click', function(e){
    e.preventDefault();

    let newRow = ratingTable.insertRow(ratingTable.rows.length);
    newRow.setAttribute('data-id', '0');
    newRow.innerHTML = `
        <td>
            <input type="text" class="desc" style='width:250px;background-color:#ddd;border-radius:5px'/>
        </td>
        <td class="center">
            <input type="text" class="center rating" style='width:50px;background-color:#ddd;border-radius:5px' />
        </td>
    `;

})