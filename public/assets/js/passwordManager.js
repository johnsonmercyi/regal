document.addEventListener('DOMContentLoaded', () => {
    let randPin = [];
    let classSelect = document.querySelector('#classId');
    let generateBtn = document.querySelector('#generateBtn');
    let printTableBtn = document.querySelector('#printTableBtn');
    let progModal = document.querySelector('#progressModal');
    let classNm = '';

    // Generate Pins
    while(randPin.length <= 100){
        let max = 999999, min = 100000;
        randPin.push(Math.floor(Math.random() * (max - min) + min));
    }
    // console.log(randPin);

    generateBtn.addEventListener('click', (e) => {
        e.preventDefault();
        let class_id = classSelect.value;
        let school_id = document.querySelector('#schoolId').value;
        let academic_session_id = document.querySelector('#sessionId').value;

        if(class_id == ''){
            alert("Please select a class!");
            return;
        }

        classNm = classSelect.selectedOptions[0].innerHTML;

        progModal.querySelector('.progress').classList.remove('hide')
        M.Modal.getInstance(progModal).open();
        
        AJAXJS({class_id, school_id, academic_session_id, randPin}, 'POST', '/generate/passwords', (res) => {
            M.Modal.getInstance(progModal).close();
            if(res.success){
                M.toast({html: "<h5>Pins Generated Successfully</h5>", classes: "green"});
                console.log(res);
                let pinsBody = '', sNum = 1;

                res.newPins.forEach(pin => {
                    pinsBody += `<tr><td>${sNum++}</td><td>${pin.name}</td><td>${pin.regNo}</td><td>${pin.pin}</td></tr>`
                })

                document.querySelector('#studentPinsListTable tbody').innerHTML = pinsBody;
                
                document.querySelector('#pinsTableDiv').classList.remove('hide')

                demoFromHTML()

            } else {
                M.toast({html: "<h5>Failed to Generate Pins</h5>", classes: "red"});                
                // M.Modal.getInstance(progModal).close();
            }
        })
    })

    printTableBtn && printTableBtn.addEventListener('click', function(){
        demoFromHTML()
    })

    function demoFromHTML() {
        // const { jsPDF } = window.jspdf;
        var pdf = new jsPDF('p', 'pt', 'a4');
        // pdf.html(document.querySelector('#pinsTableDiv'));
        source = $('#pinsTableDiv')[0];

        // we support special element handlers. Register them with jQuery-style 
        // ID selector for either ID or node name. ("#iAmID", "div", "span" etc.)
        // There is no support for any other type of selectors 
        // (class, of compound) at this time.
        specialElementHandlers = {
            // element with id of "bypass" - jQuery style selector
            '#bypassme': function (element, renderer) {
                // true = "handled elsewhere, bypass text extraction"
                return true
            }
        };
        margins = {
            top: 20,
            bottom: 60,
            left: 40,
            width: 550
        };
        // all coords and widths are in jsPDF instance's declared units
        // 'inches' in this case
        pdf.fromHTML(
            source, // HTML string or DOM elem ref.
            margins.left, // x coord
            margins.top, { // y coord
                'width': margins.width, // max width of content on PDF
                'elementHandlers': specialElementHandlers
            },

            function (dispose) {
                // dispose: object with X, Y of the last line add to the PDF 
                //          this allow the insertion of new lines after html
                pdf.save(`${classNm}.pdf`);
            }, margins
        );
    }

})










/*****************AJAX FUNCTION TO SELECT THE CHOSEN CLASS, SESSION AND TERM FROM DB****************** */
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
/*************END OF AJAX************** */