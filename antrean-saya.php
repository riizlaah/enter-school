<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Antrean Saya</title>
</head>
<body>
  <a href="/">Kembali</a>
  <div id="queues" style="display: grid; grid-template-columns: repeat(3, 1fr); padding: 24px; gap: 12px;">
  </div>
  <script src="/js/device.js"></script>
  <script>
    fetch(`/api/queues.php?uuid=${localStorage.getItem("uuid")}`)
    .then(res => {
      if(res.ok) {
        const queues = document.getElementById("queues");
        queues.innerHTML = "";
        res.json().then(arr => {
          console.log(arr);
          arr.forEach(item => {
            queues.innerHTML += `
            <a href="/antrean.php?id=${item.id}" style="text-decoration: none; color: black;">
              <div style="padding: 8px; box-shadow: 0 0 10px 0px rgba(50,50,50,10); border-radius: 8px;">
                <p>${item.title}</p>
                <p>${item.date}</p>
                <p>${item.code}</p>
                <p>${item.phone_number}</p>
              </div>
            </a>
            `;
          });
          // .innerText = ;
        });
      }
    });
  </script>
</body>
</html>