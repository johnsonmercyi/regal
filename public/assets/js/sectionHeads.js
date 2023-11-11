
document.addEventListener('DOMContentLoaded', function (event) {
  const sectionHeadForm = document.querySelector('#sectionHeadForm');
  const addNewButton = document.querySelector('.add-new-btn');
  const createDialog = document.querySelector('.create-dialog-wrapper');
  const shdSection2 = document.querySelector('.shd-section-2');
  const sectionSelect = document.querySelector('#sectionSelect');
  const sectionHeadNameField = document.querySelector('.create-dialog .main #sectionHead');
  const sectionHeadSignField = document.querySelector('#sectionHeadSign');
  const submitButton = document.querySelector('.create-dialog .footer .submit-btn');
  const cancelButton = document.querySelector('.create-dialog .footer .cancel-btn');
  const errorMessage = document.querySelector('.create-dialog .main .error-message');
  const schoolId = document.querySelector('.section-heads #schoolId');

  addNewButton && addNewButton.addEventListener('click', () => {
    errorMessage.innerHTML = "";
    setElementStyle(createDialog, "display", "flex");
    animate(createDialog, 0, 500, 1, "ease-in-out", [
      { opacity: 1 }
    ]);
  });

  submitButton && submitButton.addEventListener('click', () => {
    let section = sectionSelect.value.trim();
    let sectionName = sectionSelect.options[sectionSelect.selectedIndex].textContent;
    let sectionHead = sectionHeadNameField.value.trim();
    let sectionHeadSign = sectionHeadSignField.files[0];

    if (section && sectionHead && sectionHeadSign) {
      submitButton.classList.add('disabled');
      submitButton.classList.add('load');
      cancelButton.classList.add('disabled');

      errorMessage.innerHTML = "";

      let sectionForm = new FormData(sectionHeadForm);

      httpClient(`create`,
        HTTP.METHODS.POST, sectionForm).then((resp) => {
          if (resp.ok && resp.status === 200) {
            submitButton.classList.remove('load');
            submitButton.classList.add('success');

            //delay a bit and exit dialog
            setTimeout(() => {
              cancelButton && cancelButton.click();

              // Reset the form
              sectionHeadForm.reset();
              submitButton.classList.remove('disabled');
              submitButton.classList.remove('success');
              cancelButton.classList.remove('disabled');
              
              // Add the new section head to display container
              createSectionHeadCard(sectionHead, sectionName);
            }, 500);
          }
        }, (error) => {
          //error
          console.log("Error: ", error);
        });
    } else {
      errorMessage.innerHTML = "Please fill the required fields";
      animate(errorMessage, 0, 500, 1, "ease-in-out", [
        { opacity: 1 }
      ]);
    }
  });

  cancelButton && cancelButton.addEventListener('click', () => {
    animate(createDialog, 0, 500, 1, "ease-in-out", [
      { opacity: 0 }
    ], () => {
      setElementStyle(createDialog, "display", "none");
    });
  });

  function createSectionHeadCard(sectionHeadName, sectionName) {

    const dataBar = document.createElement('div');
    dataBar.classList.add('data-bar');
    dataBar.style.opacity = 0;

    const name = document.createElement('span');
    name.classList.add('name');
    name.innerHTML = sectionHeadName;

    const section = document.createElement('span');
    section.classList.add('section');
    section.innerHTML = sectionName.toLowerCase();

    const separator1 = document.createElement('span');
    separator1.classList.add('separator');
    separator1.innerHTML = "|";

    const actionBtns = document.createElement('div');
    actionBtns.classList.add("action-btns");

    const editBtn = document.createElement('i');
    editBtn.classList.add("material-icons", "center", "edit-btn");
    editBtn.innerHTML = "edit";
    editBtn.setAttribute("title", "Edit Record");

    const separator2 = document.createElement('span');
    separator2.classList.add('separator');
    separator2.innerHTML = "|";

    const deleteBtn = document.createElement('i');
    deleteBtn.classList.add("material-icons", "center", "delete-btn");
    deleteBtn.innerHTML = "delete";
    deleteBtn.setAttribute("title", "Delete Record");

    actionBtns.appendChild(editBtn);
    actionBtns.appendChild(separator2);
    actionBtns.appendChild(deleteBtn);

    dataBar.appendChild(name);
    dataBar.appendChild(section);
    dataBar.appendChild(separator1);
    dataBar.appendChild(actionBtns);

    shdSection2.appendChild(dataBar);

    animate(dataBar, 500, 500, 1, "ease-in-out", [{ opacity: 1 }])
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

  function httpClient(url, method, data = new FormData(), options = {}) {
    console.log("Data: ", ...data);
    // for (var p of data) {
    //   console.log(">>> ", p);
    // }
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    return fetch(url, {
      ...options,
      method: method,
      headers: {
        // 'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      // body: [...data]
      body: data
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