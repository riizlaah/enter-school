<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Antrean Saya</title>
</head>
<body>
  <div id="queues" style="display: grid; grid-template-columns: repeat(3, 1fr); padding: 24px;">
  </div>
  <script src="/js/device.js"></script>
  <script>
    fetch(`/api/queues.php?uuid=${localStorage.getItem("uuid")}`)
    .then(res => {
      if(res.ok) {
        const queues = document.getElementById("queues");
        queues.innerHTML = "";
        res.json().then(arr => {
          arr.forEach(item => {
            queues.innerHTML += `
            <div style="padding: 8px; box-shadow: 0 0 5px rgba(0,0,0,50);">
              <p>${item.title}</p>
              <p>${item.date}</p>
              <p>${item.code}</p>
              <p>${item.phone_number}</p>
            </div>
            `;
          });
          // .innerText = ;
        });
      }
    });
  </script>
</body>
</html>