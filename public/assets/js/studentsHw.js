document.addEventListener('DOMContentLoaded', function (event) {

  const section4 = document.querySelector(".shw_section_4");

  const schoolId = document.querySelector("#schoolId");
  const addShwBtn = document.querySelector('.add_btn');
  const cancelShwBtn = document.querySelector('.shw_cancel_btn');
  const submitShwBtn = document.querySelector('.shw_submit_btn');
  const editBtn = document.querySelectorAll('.edit_btn');
  const deleteBtn = document.querySelectorAll('.delete_btn');
  const searchBtn = document.querySelector('.shw_search_btn');


  const shwAcademicSessionId = document.querySelector("#shw_academic_session_id");
  const shwTermId = document.querySelector("#shw_term_id");
  const shwFormAsSelect = document.querySelector("#shw_as_select");
  const shwFormTermSelect = document.querySelector("#shw_term_select");
  const shwFormStudentSelect = document.querySelector("#shw_student_select");

  const h1Input = document.querySelector('#h1Input');
  const h2Input = document.querySelector('#h2Input');
  const w1Input = document.querySelector('#w1Input');
  const w2Input = document.querySelector('#w2Input');

  const searchField = document.querySelector('#shwSearchField');

  const submitBtnTxt = document.querySelector('.btn_txt');
  const submitLoader = document.querySelector('.submit_loader');
  const submitLoaderDone = document.querySelector('.submit_loader_done');

  const dialogContainer = document.querySelector('.shw_dialog_container');
  const deleteDialogContainer = document.querySelector('.shw_delete_dialog_container');
  const utilityPageLoader = document.querySelector('.utility_page_loader');
  const loaderSpinner = document.querySelector('.loader_spinner');

  const deleteDialogBody = document.querySelector('.delete_dialog .body');
  const deleteDialogCancelBtn = document.querySelector('.footer .cancel_btn');
  const deleteDialogOkBtn = document.querySelector('.footer .ok_btn');

  let action = "";

  initApp();

  addShwBtn && addShwBtn.addEventListener('click', () => {
    action = "create";
    setElementStyle(dialogContainer, "display", "flex");
    shwFormAsSelect.value = shwAcademicSessionId.value;
    shwFormTermSelect.value = shwTermId.value;
    shwFormAsSelect.setAttribute("disabled", "true");
    shwFormTermSelect.setAttribute("disabled", "true");
    animate(dialogContainer, 0, 500, 1, "ease-in-out", [
      { opacity: "0" },
      { opacity: "1" }
    ]);
  });

  cancelShwBtn && cancelShwBtn.addEventListener('click', dialogCancelButton);

  submitShwBtn && submitShwBtn.addEventListener('click', () => {
    if (validateForm(h1Input, w1Input, shwFormAsSelect, shwFormTermSelect, shwFormStudentSelect)) {
      setElementStyle(submitBtnTxt, "display", "none");
      setElementStyle(submitLoader, "display", "flex");
      animate(submitLoader, 0, 500, Infinity, "ease-in-out", [
        { transform: "rotate(0deg)" },
        { transform: "rotate(360deg)" },
      ], () => {
        let method;
        if (action === "create") {
          method = HTTP.METHODS.POST;
        } else if (action === "update") {
          method = HTTP.METHODS.PUT;
        } else if (action === "delete") {
          method = HTTP.METHODS.DELETE;
        }

        httpClient(action, method, {
          school_id: schoolId.value ? schoolId.value : "NULL",
          student_id: shwFormStudentSelect.value ? shwFormStudentSelect.value : "NULL",
          academic_session_id: shwFormAsSelect.value ? shwFormAsSelect.value : "NULL",
          term: shwFormTermSelect.value ? shwFormTermSelect.value : "NULL",
          h1: h1Input.value ? h1Input.value : "NULL",
          h2: h2Input.value ? h2Input.value : "NULL",
          w1: w1Input.value ? w1Input.value : "NULL",
          w2: w2Input.value ? w2Input.value : "NULL",
        }).then(response => {
          setElementStyle(submitLoader, "display", "none");
          setElementStyle(submitLoaderDone, "display", "flex");

          setTimeout(() => {
            dialogCancelButton();
            setTimeout(() => location.reload(), 100);
          }, 400);
          console.log("DATA: ", response.json());
        }).catch(err => {
          console.log(err);
        });
      });
    }
  });

  deleteDialogCancelBtn && deleteDialogCancelBtn.addEventListener('click', deleteDialogCancelButton);

  deleteDialogOkBtn && deleteDialogOkBtn.addEventListener('click', () => {
    const submitBtnTxt = document.querySelector('.footer .ok_btn .btn_txt');
    const submitLoader = document.querySelector('.footer .ok_btn .submit_loader');
    const submitLoaderDone = document.querySelector('.footer .ok_btn .submit_loader_done');
    setElementStyle(submitBtnTxt, "display", "none");
    setElementStyle(submitLoader, "display", "flex");
    animate(submitLoader, 0, 500, Infinity, "ease-in-out", [
      { transform: "rotate(0deg)" },
      { transform: "rotate(360deg)" },
    ], () => {
      data = JSON.parse(localStorage.getItem("delete_details"));

      httpClient(action, HTTP.METHODS.DELETE, {
        student_id: data.st_id,
        academic_session_id: data.as_id,
        term: data.tm_id
      }).then(response => {
        setElementStyle(submitLoader, "display", "none");
        setElementStyle(submitLoaderDone, "display", "flex");

        setTimeout(() => {
          deleteDialogCancelButton();
          setTimeout(() => location.reload(), 100);
        }, 400);
        console.log("DATA: ", response);
      }).catch(err => {
        console.log(err);
      });

    });
  });

  searchBtn && searchBtn.addEventListener('click', () => {
    if (String(searchField.value).trim()) {

      setElementStyle(utilityPageLoader, "display", "flex");

      animate(utilityPageLoader, 0, 500, 1, "ease-in-out", [
        { opacity: "0" },
        { opacity: "1" }
      ], () => {
        animate(loaderSpinner, 0, 1000, Infinity, "ease-in-out", [
          { transform: "rotate(0)", borderTop: "5px solid transparent", borderRight: "5px solid #06d6a0", borderBottom: "5px solid #06d6a0", borderLeft: "5px solid #06d6a0" },

          { transform: "rotate(90deg)", borderTop: "5px solid transparent", borderRight: "5px solid #06d6a0", borderBottom: "5px solid #06d6a0", borderLeft: "5px solid #06d6a0" },

          { transform: "rotate(180deg)", borderTop: "5px solid #06d6a0", borderRight: "5px solid #06d6a0", borderBottom: "5px solid #06d6a0", borderLeft: "5px solid #06d6a0" },

          { transform: "rotate(360deg)", borderTop: "5px solid #06d6a0", borderRight: "5px solid #06d6a0", borderBottom: "5px solid #06d6a0", borderLeft: "5px solid #06d6a0" }
          
        ], () => { 
          httpClient("search", HTTP.METHODS.POST, {
            search: searchField.value,
            academic_session_id: shwAcademicSessionId.value ? shwAcademicSessionId.value : "NULL",
            term: shwTermId.value ? shwTermId.value : "NULL"
          }).then(response =>response.json()).then(data => {

            setElementStyle(utilityPageLoader, "display", "none");
            // searchField.value = "";
            section4.innerHTML = "";
            const tasids = data.tasids[0];

            if (data.data.length) {
              data.data.map(obj=> {
  
                let container = document.createElement('div');
                addElementClasses(container, "shw_data_card_container", `shw_data_card_container_${obj.id}`);
  
                let details = document.createElement('div');
                addElementClasses(details, "st_details");
  
                let name = document.createElement('div');
                addElementClasses(details, "st_name");
                name.innerHTML = obj.name;
  
                let utilityBar = document.createElement('div');
                addElementClasses(utilityBar, "st_utility_bar");
                utilityBar.setAttribute("data-as-id", tasids.academic_session_id);
                utilityBar.setAttribute("data-tm-id", tasids.current_term_id);
                utilityBar.setAttribute("data-st-name", obj.name);
                utilityBar.setAttribute("data-st-id", obj.id);
                utilityBar.setAttribute("data-st-h1", obj.h1);
                utilityBar.setAttribute("data-st-h2", obj.h2);
                utilityBar.setAttribute("data-st-w1", obj.w1);
                utilityBar.setAttribute("data-st-w2", obj.w2);
  
                let gender = document.createElement('span');
                addElementClasses(gender, "st_gender");
                gender.innerHTML = obj.gender === "M" ? "Male" : "Female";
  
                let verticalBar = document.createElement('span');
                addElementClasses(verticalBar, "vertical_bar");
                gender.innerHTML = "|";
  
                let stClass = document.createElement('span');
                addElementClasses(stClass, "st_class");
                gender.innerHTML = obj.student_class;
  
                let editIconWrapper = document.createElement('span');
                addElementClasses(editIconWrapper, "right");
  
                let editIcon = document.createElement('i');
                addElementClasses(editIcon, "material-icons", "right", "edit_btn");
                editIcon.setAttribute("title", "Edit record");
                editIcon.innerHTML = "edit";
  
                editIcon.addEventListener('click', (e) => {
                  action = "update";
                  const data = e.target.parentElement.parentElement.dataset;
          
                  setElementStyle(dialogContainer, "display", "flex");
                  shwFormAsSelect.value = shwAcademicSessionId.value;
                  shwFormTermSelect.value = shwTermId.value;
                  shwFormAsSelect.setAttribute("disabled", "true");
                  shwFormTermSelect.setAttribute("disabled", "true");
          
                  shwFormStudentSelect.innerHTML = "";
          
                  const optionElement = document.createElement("option");
                  optionElement.value = data.stId;
                  optionElement.text = data.stName;
                  optionElement.setAttribute("selected", "true");
          
                  shwFormStudentSelect.appendChild(optionElement);
                  shwFormStudentSelect.setAttribute("disabled", "true");
          
                  h1Input.value = data.stH1 === "N/A" ? "" : data.stH1;
                  h2Input.value = data.stH2 === "N/A" ? "" : data.stH2;
                  w1Input.value = data.stW1 === "N/A" ? "" : data.stW1;
                  w2Input.value = data.stW2 === "N/A" ? "" : data.stW2;
          
                  animate(dialogContainer, 0, 500, 1, "ease-in-out", [
                    { opacity: "0" },
                    { opacity: "1" }
                  ]);
                });
  
                editIconWrapper.appendChild(editIcon);
  
                let deleteIconWrapper = document.createElement('span');
                addElementClasses(deleteIconWrapper, "right");
  
                let deleteIcon = document.createElement('i');
                addElementClasses(deleteIcon, "material-icons", "right", "delete_btn");
                deleteIcon.setAttribute("title", "Delete record");
                deleteIcon.innerHTML = "delete";
  
                deleteIcon.addEventListener('click', (e) => {
                  action = "delete";
                  const data = e.target.parentElement.parentElement.dataset;
                  localStorage.setItem("delete_details", JSON.stringify({
                    st_id: data.stId,
                    as_id: data.asId,
                    tm_id: data.tmId
                  }));
          
                  console.log(localStorage.getItem("delete_details"));
                  deleteDialogBody.innerHTML = `This action will delete Height and Weight record for ${data.stName}<br><br>Do you want to proceed?`;
                  setElementStyle(deleteDialogContainer, "display", "flex");
          
                  animate(deleteDialogContainer, 0, 500, 1, "ease-in-out", [
                    { opacity: "0" },
                    { opacity: "1" }
                  ]);
                });
  
                deleteIconWrapper.appendChild(deleteIcon);
  
                utilityBar.appendChild(gender);
                utilityBar.appendChild(verticalBar);
                utilityBar.appendChild(stClass);
                utilityBar.appendChild(editIconWrapper);
                utilityBar.appendChild(deleteIconWrapper);
  
                let utilityBar2 = document.createElement('div');
                addElementClasses(utilityBar2, "st_utility_bar", "shw_bar");
  
                let h1 = document.createElement('span');
                addElementClasses(h1, "shw_data", "shw_data_h1");
                h1.innerHTML = `<strong>H1</strong>: `;
                h1.innerHTML += obj.h1 === "N/A" ? obj.h1 : `${obj.h1 }cm`;
  
                let h2 = document.createElement('span');
                addElementClasses(h2, "shw_data", "shw_data_h2");
                h2.innerHTML = `<strong>H2</strong>: `;
                h2.innerHTML += obj.h2 === "N/A" ? obj.h2 : `${obj.h2 }cm`;
  
                let w1 = document.createElement('span');
                addElementClasses(w1, "shw_data", "shw_data_w1");
                w1.innerHTML = `<strong>W1</strong>: `;
                w1.innerHTML += obj.w1 === "N/A" ? obj.w1 : `${obj.w1 }kg`;
  
                let w2 = document.createElement('span');
                addElementClasses(w2, "shw_data", "shw_data_w2");
                w2.innerHTML = `<strong>W2</strong>: `;
                w2.innerHTML += obj.w2 === "N/A" ? obj.w2 : `${obj.w2 }kg`;
  
                utilityBar2.appendChild(h1);
                utilityBar2.appendChild(h2);
                utilityBar2.appendChild(w1);
                utilityBar2.appendChild(w2);
  
                details.appendChild(name);
                details.appendChild(utilityBar);
                details.appendChild(utilityBar2);
  
                container.appendChild(details);
  
                section4.appendChild(container);
  
              });
            } else {
              section4.innerHTML = "<span class='no-data'>No data matched your search<span class='emoji'>👾</span></span>";
            }

            // console.log("RESPONSE: ", data);
          }).catch(err => {
            console.log(err);
          });
        });        
      });
    }
  });

  function initApp() {
    setElementStyle(dialogContainer, "display", "none");
    setElementStyle(deleteDialogContainer, "display", "none");
    setElementStyle(utilityPageLoader, "display", "none");

    registerFormComponentEvents(h1Input, h2Input, w1Input, w2Input, shwFormAsSelect, shwFormTermSelect, shwFormStudentSelect);
    registerEvents();
  }

  function dialogCancelButton() {
    action = "";
    animate(dialogContainer, 0, 100, 1, "ease-in-out", [
      { opacity: "1" },
      { opacity: "0" }
    ], () => {
      setElementStyle(dialogContainer, "display", "none");
      clearFields(h1Input, h2Input, w1Input, w2Input, shwFormAsSelect, shwFormTermSelect, shwFormStudentSelect);
    });
  }

  function deleteDialogCancelButton() {
    action = "";
    localStorage.removeItem("delete_details");
    animate(deleteDialogContainer, 0, 100, 1, "ease-in-out", [
      { opacity: "1" },
      { opacity: "0" }
    ], () => {
      setElementStyle(deleteDialogContainer, "display", "none");
    });
  }

  function registerFormComponentEvents(...fields) {
    for (let i = 0; i < fields.length; i++) {
      fields[i].addEventListener('input', (e) => {
        if (String(e.target.value).trim() === "") {
          setElementStyle(fields[i], "border", "1px solid red");
        } else {
          setElementStyle(fields[i], "border", "initial");
        }
      });
    }
  }

  function registerEvents() {
    for (let i = 0; i < editBtn.length; i++) {
      editBtn[i].addEventListener('click', (e) => {
        action = "update";
        const data = e.target.parentElement.parentElement.dataset;

        setElementStyle(dialogContainer, "display", "flex");
        shwFormAsSelect.value = shwAcademicSessionId.value;
        shwFormTermSelect.value = shwTermId.value;
        shwFormAsSelect.setAttribute("disabled", "true");
        shwFormTermSelect.setAttribute("disabled", "true");

        shwFormStudentSelect.innerHTML = "";

        const optionElement = document.createElement("option");
        optionElement.value = data.stId;
        optionElement.text = data.stName;
        optionElement.setAttribute("selected", "true");

        shwFormStudentSelect.appendChild(optionElement);
        shwFormStudentSelect.setAttribute("disabled", "true");

        h1Input.value = data.stH1 === "N/A" ? "" : data.stH1;
        h2Input.value = data.stH2 === "N/A" ? "" : data.stH2;
        w1Input.value = data.stW1 === "N/A" ? "" : data.stW1;
        w2Input.value = data.stW2 === "N/A" ? "" : data.stW2;

        animate(dialogContainer, 0, 500, 1, "ease-in-out", [
          { opacity: "0" },
          { opacity: "1" }
        ]);
      });
    }

    for (let i = 0; i < deleteBtn.length; i++) {
      deleteBtn[i].addEventListener('click', (e) => {
        action = "delete";
        const data = e.target.parentElement.parentElement.dataset;
        localStorage.setItem("delete_details", JSON.stringify({
          st_id: data.stId,
          as_id: data.asId,
          tm_id: data.tmId
        }));

        console.log(localStorage.getItem("delete_details"));
        deleteDialogBody.innerHTML = `This action will delete Height and Weight record for ${data.stName}<br><br>Do you want to proceed?`;
        setElementStyle(deleteDialogContainer, "display", "flex");

        animate(deleteDialogContainer, 0, 500, 1, "ease-in-out", [
          { opacity: "0" },
          { opacity: "1" }
        ]);
      });
    }
  }

  function validateForm(...fields) {
    let count = 0;
    for (let i = 0; i < fields.length; i++) {
      if (String(fields[i].value).trim() === "") {
        setElementStyle(fields[i], "border", "1px solid red");
      } else {
        setElementStyle(fields[i], "border", "initial");
        count++;
      }
    }
    return count === fields.length;
  }

  function clearFields(...fields) {
    for (let i = 0; i < fields.length; i++) {
      fields[i].value = "";
      setElementStyle(fields[i], "border", "initial");
    }
  }

  function animate(
    el,
    delay,
    duration,
    iterations,
    easing = "ease-in-out",
    frames = [],
    onFinish = () => { },
    onFinishDelay
  ) {
    let keyframes = new KeyframeEffect(el, frames, {
      id: "animate_element_",
      easing: easing,
      iterations: iterations,
      fill: "forwards",
      duration: duration,
      delay: delay,
    });

    let animation = new Animation(keyframes, document.timeline);
    animation.play();

    if (onFinish) {
      if (onFinishDelay) {
        setTimeout(() => {
          onFinish();
        }, delay + onFinishDelay);
      } else {
        setTimeout(() => {
          onFinish();
        }, delay + duration);
      }
    }
  }

  function addElementClasses(el, ...classes) {
    for (let i = 0; i < classes.length; i++) {
      el.classList.add(classes[i]);
    }
  }

  function setAllElementStyle(property, value, ...elements) {
    for (let i = 0; i < elements.length; i++)
      elements[i].style[property] = value;
  }

  function setElementStyle(element, property, value) {
    element.style[property] = value;
  }

  function httpClient(url, method, data = {}, options = {}) {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    return fetch(url, {
      ...options,
      method: method,
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify({ data: { ...data } })
    });
  }

  const HTTP = {
    METHODS: {
      POST: "POST",
      GET: "GET",
      PUT: "PUT",
      DELETE: "DELETE"
    }
  }
});