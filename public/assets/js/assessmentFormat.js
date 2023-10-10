import AJAX_OPERATION, {AJAX_REQUEST_METHODS} from '../util/utility.js';
$(function(){

const formatArr = [];
let sectionID = document.querySelector('#sectionId');
let schoolId = document.querySelector('#schoolId').value;
let sectionAssignBtn = document.querySelectorAll('.assignAssess');

let num = 2;
$('#addFormatRow').on('click', function(e){
    e.preventDefault();
    (num < 9) && $('#assessTableBody').append(addNewRow(num++));
    // $('.assessVal').each(sumAssessment);
    $('#assessTableBody').find('.assessVal').each(sumAssessment);

    // console.log($('#assessTableBody tr button').length);
    // $('#assessTableBody tr button').each(function(){
    //     $(this).on('click', function(e){
    //         e.preventDefault();
    //         console.log($(this).parents('tr').attr('id'));
    //         $(this).parents('tr').remove();
    //     })
    // });
});

$('.assignAssess').each(function(){
    $(this).on('click', function(e){
        e.preventDefault();
        let sectionId = $(this).attr('data-id');
        let sectionAssessObj = {
            sectionId,
            schoolId,
            assessmentId: $(`#assess${sectionId}`).val(),
        };
        $('#assessAssignSection .progress').removeClass('hide');
        AJAX_OPERATION('/assess/format/assign', AJAX_REQUEST_METHODS.POST, sectionAssessObj,
            function (response, status, xhr) {
                if(response.updated){
                    M.toast({html:"<h5>Assessment Format Updated Successfully</h5>", classes:"green"}, 4000)
                    $('#assessAssignSection .progress').addClass('hide');
                    $(`#formatAssignTable #row${sectionId} .assessForm`).html(`Format ${sectionAssessObj.assessmentId}`);
                }

            }, function (xhr, status, error) {
                M.toast({html:"<h5>Unable to Assign Format</h5>", classes:"red"}, 4000)
                console.log("Server Error Response:\n" + error);
                /**HANDLE ERROR HERE... */
            }

        )
    })
})

$('#createNewFormat').on('click', function(e){
    e.preventDefault();
    $('#assessAssignSection').addClass('hide');
    $('#formatTablesSection').addClass('hide');
    $('#formatCreateSection').removeClass('hide');
})

$('#submitFormat').on('click', function(e){
    e.preventDefault();
    $('#assessTableBody tr').each(packFormat);
    if(parseInt($('#totalAssess').text()) < 1){
        alert(' Total Assessment cannot be less than 1%');
        formatArr.length = 0;
    }else{
        // console.log(formatArr);
        $('.progress').addClass('hide');
        insertFormat(`/${formatArr[0].school_id}/assessformat`, formatArr);
        $(this).attr('disabled', 'disabled');
        formatArr.length = 0;
    }
});

$('#assessTableBody').find('.assessVal').each(sumAssessment);

function packFormat(){
    const formatObj= {};
    if($(this).find('.percentage').val()){
    formatObj.school_id = schoolId;
    formatObj.formatType = $(this).attr('id');
    formatObj.percentage = $(this).find('.percentage').val();
    formatObj.name = $(this).find('.name').is('input') ? $(this).find('.name').val() :$(this).find('.name').text();
    // formatObj.name = $(this).find('.name').text();
    formatArr.push(formatObj);
    }
}


function removeFormat(){
    $(this).on('click', function(e){
        e.preventDefault();
        $(this).parents('tr').remove();
        console.log($(this).parents('tr').attr('id'));
    })
}

function sumAssessment(){
    $(this).on('keyup', function(){
        let totalAssessment = 0;
        $('.assessVal').each(function(){
            if(!isNaN(parseInt($(this).val()))){
                totalAssessment += parseInt($(this).val());
            } else {
                totalAssessment += 0;
            }
        })
        $('#totalAssess').text(totalAssessment);
        $(this).removeClass('red');
        if(totalAssessment > 100){
            alert('Total assessment cannot be more than 100%');
            $(this).val(0);
            $(this).addClass('red lighten-2');
        }
    });
}

const addNewRow = num => `<tr id="CA${num}"><td>${++num}</td>
    <td><input type="text" style='background-color:#ddd;border-radius:5px' class="name" /></td>
    <td><input type="number" class="assessVal center percentage" 
    style='width:50px;background-color:#ddd;border-radius:5px' /></td>
    </tr>`;
    // <td><button type="submit" class="btn removeRowBtn">Remove</button></td> 

/******************FUNCTION TO CHECK EMPTY VALUES BEFORE SUBMIT THE FORMAT****************************** */
function checkEmpty(){
    let confirm = true;
    const gradeRows = document.querySelectorAll('#assessTableBody tr');
    gradeRows.forEach(row =>{
        let rowInputs = row.querySelectorAll('input');
        for(const inp of rowInputs){
            console.log(inp.value)
            if(inp.value == ''){
                confirm = false;
                break;
            }
        }
    });
    return confirm;
}


/**********AJAX FUNCTION FOR INSERTING ASSESSMENT FORMAT INTO DB ************************* */
const insertFormat =  (actionURL, formatObj)=>{
    AJAX_OPERATION(actionURL, AJAX_REQUEST_METHODS.POST, JSON.stringify(formatObj),
    function (response, status, xhr) {
            
        // $('#submitScores').attr("disabled", true);
        // $('#submitScores').addClass("hide");
        // $('.progress').addClass('hide');
        // $('#updateAct').removeClass("hide");
        if(response.successInfo){
            $('#successModal').modal('open');
            setTimeout(window.location.assign(`/${schoolId}/assessformat/create`), 3000);
        }

        $('#successModal #close').on('click', function(){
            $('#successModal').modal('close');
        });
    }, function (xhr, status, error) {
        console.log("Server Error Response:\n" + error);
        /**HANDLE ERROR HERE... */
    }
    );
}
/**********END OF AJAX FUNCTION FOR INSERTING SCORES INTO DB ********************* */

})