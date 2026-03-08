const prom = import("./fingerprint.js").then(fpjs => fpjs.load());

if(localStorage.getItem("uuid") == null) {
  prom
    .then(fp => fp.get())
    .then(res => {
      localStorage.setItem("uuid", res.visitorId);
      getMyId();
    });
} else {
  getMyId();
}

function getMyId() {
  fetch("/api/login.php", {
        method: "POST",
        body: `{"uuid": "${localStorage.getItem("uuid")}"}`
      })
        .then(response => response.json())
        .then(json => {
          if(localStorage.getItem("userId") == null) {
            localStorage.setItem("userId", `${json.id}`)
          } else if(localStorage.getItem("userId") != `${json.id}`) {
            localStorage.setItem("userId", `${json.id}`)
          }
        });
}
