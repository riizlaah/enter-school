<?php
require_once 'api/core.php';

if(!isset($_GET["id"])) return redirect("/antrean-saya.php");
if(!is_numeric($_GET["id"])) return http_response_code(400);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Antrean</title>
</head>
<body>
  <div>
    <h1 id="title"></h1>
    <h2 id="code"></h2>
    <p id="phone_number"></p>
    <p id="date"></p>
    <p id="queue_status"></p>
    <p id="status"></p>
    <p id="position"></p>
    <a href="/antrean-saya.php">Kembali</a>
    <a href="/ambil-antrean.php">Ambil Antrean Lain</a>
  </div>
  <script>
    const queueId = "<?= $_GET["id"] ?>";
    function query(str) {
      return document.querySelector(str);
    }
    function updateQueue() {
      fetch(`/api/queue.php?id=${queueId}`)
      .then(res => {
        if(res.ok) {
          res.json().then(data => {
            query("#title").innerText = data.title;
            query("#code").innerText = data.code;
            query("#phone_number").innerText = data.phone_number;
            query("#date").innerText = data.date;
            query("#queue_status").innerText = "Status Antrean : " +  data.queue_status;
            query("#status").innerText = data.status;
            query("#position").innerText = data.position;
          });
        }
      });
    }
    updateQueue();
    setInterval(() => {
      updateQueue();
    }, 5000);
  </script>
</body>
</html>