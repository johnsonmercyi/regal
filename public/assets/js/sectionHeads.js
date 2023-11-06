
document.addEventListener('DOMContentLoaded', function (event) {
  const sectionSelect = document.querySelector('#shd_section_select');
  const sectionHeadNameField = document.querySelector('.create-dialog .main #sectionHead');
  const sectionHeadSignField = document.querySelector('#sectionHeadSign');
  const submitButton = document.querySelector('.create-dialog .footer .submit-btn');
  const cancelButton = document.querySelector('.create-dialog .footer .cancel-btn');
  const errorMessage = document.querySelector('.create-dialog .main .error-message');
  const schoolId = document.querySelector('.section-heads #schoolId');

  submitButton && submitButton.addEventListener('click', () => {
    let section = sectionSelect.value.trim();
    let sectionHead = sectionHeadNameField.value.trim();
    let sectionHeadSign = sectionHeadSignField.files[0];

    console.log(section, sectionHead, sectionHeadSign);

    if (section && sectionHead && sectionHeadSign) {
      errorMessage.innerHTML = "";

      const data = {
        section: section,
        section_head: sectionHead
      }

      const formData = new FormData();
      formData.append('section_head_sign', sectionHeadSign);
      formData.append('data', JSON.stringify(data));
      
      httpClient(`create`, 
      HTTP.METHODS.POST, formData).then((resp)=> {
        console.log("Response: ", resp);
      }, (error)=> {
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
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      // body: [...data]
      body: {
        data: JSON.stringify(data.get('data')),
        sign: data.get('section_head_sign')
      }
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