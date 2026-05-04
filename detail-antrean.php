<?php
require_once "app/core.php";

if(!require_fields(["id", "phone"], $_GET)) {
  return abort(400);
}
if(!is_numeric($_GET["id"])) {
  return abort(400);
}
if(!preg_match("/^\+?\d{10,}$/", $_GET["phone"])) return alert("No. Telepon tidak valid.", "/");

$id = intval($_GET["id"]);
$queue = query("SELECT * FROM queues WHERE visitor_phone = ? AND id = ?", [$_GET["phone"], $id])->fetch_assoc();
if($queue == null) return abort(404);
$service = query("SELECT * FROM services WHERE id = ?", [$queue["service_id"]])->fetch_assoc();

?>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Enter School</title>
  <link rel="stylesheet" href="assets/style-v1.css" />
</head>

<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">
        <img src="assets/img/logo-teks.png" alt="" srcset="" />
      </div>
      <nav class="layer-menu">
        <ul class="menu">
          <li><a href="/" class="btn light">Ambil Antrean</a></li>
          <li>
            <a href="" class="btn light">Antrean Berlangsung</a>
          </li>
          <li>
            <a href="/antrean-saya.php" class="btn light">Lihat AntreanMu</a>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-index">
      <h1>Detail Antrean</h1>

      <div class="detailcards">
        <div class="detailcard">
          <div class="detailcard-number"><?= $service["prefix"] ?><?= $queue["queue_number"] ?></div>
          <div class="detailcard-info">
            <p><?= format_date("(EEEE) d MMM yyyy", $queue["appointment_date"]) ?></p>
            <p><?= $service["name"] ?></p>
            <span><?= $queue["visitor_phone"] ?></span>
          </div>
        </div>
      </div>

      <a class="antrian-baru" target="_blank" href="/">Ambil Antrean Baru</a>

      <!-- Footer -->
    </main>
  </div>
  <footer class="footer">
    <p class="copyright">&copy;Copyright 2026</p>
    <span>Social Media :</span>
    <div>
      <img src="assets/img/instagram.svg" alt="" />
      <span>@smkloremipsum</span>
    </div>
    <div>
      <img src="assets/img/facebook.svg" alt="" />
      <span>@smkloremipsum</span>
    </div>
    <div>
      <img src="assets/img/phone.svg" alt="" />
      <span>+62 123-2342-345</span>
    </div>
  </footer>
  <script>
    let savedQueuesJSON = localStorage.getItem("savedQueues");
    if(savedQueuesJSON == null) {
      savedQueuesJSON = "[]";
    }
    try {
      let savedQueues = JSON.parse(savedQueuesJSON);
      if(!savedQueues.some((item) => item.id == <?= $id ?> && item.phone == "<?= $_GET["phone"] ?>")) {
        savedQueues.push({id: <?= $id ?>, phone: "<?= $_GET["phone"] ?>"});
      }
      localStorage.setItem("savedQueues", JSON.stringify(savedQueues));
    } catch(err) {
      localStorage.clear();
      console.error(err.message);
    }
  </script>
</body>
</html>