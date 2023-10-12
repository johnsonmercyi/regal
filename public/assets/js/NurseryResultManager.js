import * as util from "../util/animation-util.js";

document.addEventListener('DOMContentLoaded', function (event) {

  const spinnerWrapper = document.querySelector('.spinner-wrapper');
  const spinner = document.querySelector('.spinner-wrapper .spinner');
  const studentDisplay = document.querySelector('.student-display');
  const studentCards = document.querySelectorAll('.student-card');
  const loadStudents = document.querySelector('#loadStudents');
  const schoolId = document.querySelector("#schoolId");
  const classId = document.querySelector("#classId");
  const sessionIdSelect = document.querySelector("#sessionIdSelect");
  const termIdSelect = document.querySelector("#termIdSelect");
  const viewResultBtn = document.querySelector(".view-result-btn");
  const classStudentsListTitle = document.createElement("span");
  classStudentsListTitle.innerHTML = "Class Students";
  classStudentsListTitle.classList.add('title');

  init();//initialize

  loadStudents && loadStudents.addEventListener('click', e => {
    e.preventDefault();

    if (String(classId.value).trim() === ""
      || String(sessionIdSelect.value).trim() === ""
      || String(termIdSelect.value).trim() === "") {
      alert(`Cannot load class students!\nPlease ensure to select "Class", "Academic Session", "Term" and click the "load" button to proceed.`);
    } else {
      if (spinnerWrapper.style.display === "none") {
        util.setElementStyle(spinnerWrapper, "display", "flex");
        if (studentDisplay.style.display === "flex") {
          util.setElementStyle(studentDisplay, "display", "none");
        }
      }
      // console.log(spinnerWrapper.style.display);

      util.animate(spinnerWrapper, 100, 500, 1, "ease-in-out", [
        { top: "-10%", opacity: "0" },
        { top: "0", opacity: "1" }
      ], () => {
        //onFinish
        util.animate(spinner, 0, 500, Infinity, "ease-in-out", [
          { transform: "rotate(0deg)" },
          { transform: "rotate(360deg)" }
        ], () => {
          //onFinish
          //************** Do ajax call here *********
          util.ajax({}, "GET", `students_view/class_students?class_id=${classId.value}&session_id=${sessionIdSelect.value}&term_id=${termIdSelect.value}&school_id=${schoolId.value}`, (response) => {
            // console.log("Response: ", response.data);
            // On success
            if (response.data.length > 0) {
              util.animate(spinnerWrapper, 500, 500, 1, "ease-in-out", [
                { top: "0", opacity: "1" },
                { top: "-10%", opacity: "0" }
              ], () => {

                let count = 0, studentData = null;

                //reset element
                studentDisplay.innerHTML = "";
                studentDisplay.classList.remove('record-not-found');
                studentDisplay.appendChild(classStudentsListTitle);

                let interval = setInterval(() => {
                  if (count === response.data.length) {
                    clearInterval(interval);

                    util.setElementStyle(spinnerWrapper, "display", "none");
                    util.setElementStyle(studentDisplay, "display", "flex");
                    let children = document.querySelectorAll('.student-card');
                    children[0].style.borderRadius = "1vw 1vw 0 0";
                    children[children.length - 1].style.borderRadius = "0 0 1vw 1vw";
                    children[children.length - 1].style.borderBottom = "none";

                    util.animate(studentDisplay, 0, 500, 1, "ease-in-out", [
                      { top: "-1.5%", opacity: "0" },
                      { top: "0", opacity: "1" }
                    ], () => {
                      let count = 0, studentData = null, delay = 0;

                      let interval = setInterval(() => {
                        if (count === children.length) {
                          clearInterval(interval);
                        } else {
                          util.animate(children[count], count > 0 ? delay += 100 : delay, 500, 1, "ease-in-out", [
                            { left: "-3%", opacity: "0" },
                            { left: "0", opacity: "1" }
                          ]);
                          count++;
                        }
                      }, 20);
                    });
                  } else {
                    studentData = response.data[count];
                    studentData.class_id = classId.value;
                    studentData.session_id = sessionIdSelect.value;
                    studentData.term_id = termIdSelect.value;
                    studentDisplay.appendChild(initStudentCardTemplate(count, studentData));
                    count++;
                  }
                }, 5);
              });
            } else {
              util.animate(spinnerWrapper, 500, 500, 1, "ease-in-out", [
                { top: "0", opacity: "1" },
                { top: "-10%", opacity: "0" }
              ], () => {
                if (studentDisplay.childElementCount > 0) {
                  studentDisplay.innerHTML = "";
                }

                studentDisplay.classList.add('record-not-found');
                studentDisplay.innerHTML = "No records found!"
                util.setElementStyle(spinnerWrapper, "display", "none");
                util.setElementStyle(studentDisplay, "display", "flex");

                util.animate(studentDisplay, 0, 500, 1, "ease-in-out", [
                  { top: "-1.5%", opacity: "0" },
                  { top: "0", opacity: "1" }
                ]);
              });
            }
          }, () => {
            // console.log("Error: ", error);
            util.animate(spinnerWrapper, 500, 500, 1, "ease-in-out", [
              { top: "0", opacity: "1" },
              { top: "-10%", opacity: "0" }
            ], () => {
              if (studentDisplay.childElementCount > 0) {
                studentDisplay.innerHTML = "";
              }

              studentDisplay.classList.add('record-not-found');

              const robot = document.createElement('span');
              robot.innerHTML = "👾";
              util.setElementStyle(robot, "display", "flex");
              util.setElementStyle(robot, "flex-direction", "column");
              util.setElementStyle(robot, "justify-content", "center");
              util.setElementStyle(robot, "align-items", "center");
              util.setElementStyle(robot, "font-size", "4vw");
              util.setElementStyle(robot, "margin", "0 2vw");
              util.setElementStyle(robot, "line-height", "3vw");

              const textNode = document.createElement("p");
              textNode.innerHTML = "Sorry! Something went wrong.";
              util.setElementStyle(textNode, "font-size", "1.2vw");
              util.setElementStyle(textNode, "font-weight", "400");
              util.setElementStyle(textNode, "color", "red");
              util.setElementStyle(textNode, "margin", "0");

              robot.appendChild(textNode);

              studentDisplay.appendChild(robot);
              
              util.setElementStyle(studentDisplay, "color", "red");
              util.setElementStyle(spinnerWrapper, "display", "none");
              util.setElementStyle(studentDisplay, "display", "flex");

              util.animate(studentDisplay, 0, 500, 1, "ease-in-out", [
                { top: "-1.5%", opacity: "0" },
                { top: "0", opacity: "1" }
              ]);
            });
          });
          //************************************** */
        }, 50);
      }, 200);
    }

  });


  function init() {
    util.setAllElementStyle("display", "none", studentDisplay, spinnerWrapper);
  }

  function initStudentCardTemplate(key = 0, studentObj = {}) {
    // console.log("STUDENT: ", studentObj);
    key = key + 1;

    const studentCard = document.createElement('div');
    studentCard.classList.add("student-card", `student-card-${key}`);

    const student = document.createElement('div');
    student.classList.add('student');

    const name = document.createElement('span');
    name.classList.add('name');
    name.innerHTML = studentObj.name;
    const regNo = document.createElement('span');
    regNo.classList.add('reg-no');
    regNo.innerHTML = studentObj.regNo;

    student.appendChild(name);
    student.appendChild(regNo);

    const buttonWrapper = document.createElement('div');
    buttonWrapper.classList.add('button-wrapper');

    const viewResultBtn = document.createElement('span');
    viewResultBtn.classList.add('view-result-btn');
    viewResultBtn.setAttribute("data-student", studentObj);
    viewResultBtn.innerHTML = "View Result";

    viewResultBtn.addEventListener('click', () => {
      window.open(`student_result_view?st_id=${studentObj.id}&r_no=${studentObj.regNo}&cl_id=${studentObj.class_id}&se_id=${studentObj.session_id}&tm_id=${studentObj.term_id}`, '_blank');
    });

    buttonWrapper.appendChild(viewResultBtn);

    studentCard.appendChild(student);
    studentCard.appendChild(buttonWrapper);

    return studentCard;
  }

});


