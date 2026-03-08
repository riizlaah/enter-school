let password_inputs = document.querySelectorAll("label.pass>input");
let eye_icon = feather.icons['eye'];
let eye_off_icon = feather.icons['eye-off'];
password_inputs.forEach(e => {
  e.nextElementSibling.onclick = () => {
    if(e.type === "text") {
      e.type = "password";
      e.nextElementSibling.innerHTML = eye_icon.contents;
    } else {
      e.type = "text";
      e.nextElementSibling.innerHTML = eye_off_icon.contents;
    }
  };
});