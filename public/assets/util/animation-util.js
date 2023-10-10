function animate(
  el,
  delay,
  duration,
  iterations,
  easing = "ease-in-out",
  frames = [],
  onFinish = () => { },
  impatientDelay
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
    if (impatientDelay >= 0) {
      setTimeout(() => {
        onFinish();
      }, impatientDelay);
    } else {
      setTimeout(() => {
        onFinish();
      }, delay + duration);
    }
  }
}

function setAllElementStyle(property, value, ...elements) {
  for (let i = 0; i < elements.length; i++)
    elements[i].style[property] = value;
}

function setElementStyle(element, property, value) {
  element.style[property] = value;
}

function switchElements(currentEl, replacement, startDelay, duration, transitionType = "slide-in-from-left", onFinish = () => { }) {

  setTimeout(() => {
    setElementStyle(currentEl, "display", "none");
    setElementStyle(replacement, "display", "flex");

    if (!transitionType) {
      doOpacity(replacement, 0, duration, "0", "1");
    } else {
      if (transitionType === "slide-in-from-left") {
        animate(replacement, 0, duration, 1, "ease-out", [
          { left: "-10%", opacity: "0" },
          { left: "0", opacity: "1" }
        ]);
      } else if (transitionType === "slide-in-from-right") {
        animate(replacement, 0, duration, 1, "ease-out", [
          { right: "-10%", opacity: "0" },
          { right: "0", opacity: "1" }
        ]);
      } else if (transitionType === "slide-in-from-top") {
        animate(replacement, 0, duration, 1, "ease-out", [
          { top: "-10%", opacity: "0" },
          { top: "0", opacity: "1" }
        ]);
      } else if (transitionType === "slide-in-from-bottom") {
        animate(replacement, 0, duration, 1, "ease-out", [
          { bottom: "-10%", opacity: "0" },
          { bottom: "0", opacity: "1" }
        ]);
      }
    }

    if (onFinish) {
      setTimeout(() => {
        onFinish();
      }, duration);
    }
  }, startDelay + duration);
}

function ajax(formObj, method, url, onSuccess=()=>{}, onError=()=>{}) {
  let ajaxObj = new XMLHttpRequest();
  ajaxObj.open(method, url);
  ajaxObj.onload = ()=>{
      if(ajaxObj.status == 200){
          let response = JSON.parse(ajaxObj.responseText);
          // console.log(response);
          onSuccess(response);
      } else {
          onError();
      }
  };
  ajaxObj.setRequestHeader('Content-Type', 'application/json');
  ajaxObj.setRequestHeader('X-CSRF-Token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
  ajaxObj.send(JSON.stringify(formObj));
}

export {
  animate,
  setElementStyle,
  setAllElementStyle,
  switchElements,
  ajax
}