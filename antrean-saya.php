<?php
require_once "app/core.php";


$queues = [];

if(isset($_COOKIE["voucher"]) && $payload = SimpleJWT::decode($_COOKIE["voucher"])) {
  foreach($payload as $phone => $ids) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $queues += query("SELECT q.*, s.name AS `service_name`, s.prefix AS `service_prefix` FROM
     `queues` q LEFT JOIN services s ON s.id = q.service_id WHERE q.visitor_phone = ? AND q.id IN ($placeholders)", [$phone, ...$ids])->fetch_all(MYSQLI_ASSOC);
  }
}

?>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Enter School</title>
  <link rel="stylesheet" href="/assets/style-v1.css" />
</head>

<body>
  <div class="spanduk">
    <p class="judul">Pengambilan Antrean Pendaftaran Sekolah</p>
  </div>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">
        <img src="/assets/img/logo-teks.png" alt="" srcset="" />
      </div>
      <nav class="layer-menu">
        <ul class="menu">
          <li><a href="/" class="btn light">Ambil Antrean</a></li>
          <li>
            <a href="/antrean-berlangsung.php" class="btn light">Antrean Berlangsung</a>
          </li>
          <li>
            <a href="/antrean-saya.php" class="btn dark">Lihat AntreanMu</a>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="main">
      <h1>Antrean Milikmu</h1>

      <div class="cards" id="queue-cards">
        <?php foreach($queues as $queue): ?>
        <a class="card" style="text-decoration: none; color: black;" href="/detail-antrean.php?id=<?= $queue["id"] ?>&phone=<?= urlencode($queue["visitor_phone"]) ?>">
          <div class="card-number"><?= $queue["service_prefix"] ?><?= str_pad((string)$queue["queue_number"], 3, "0", STR_PAD_LEFT) ?></div>
          <div class="card-info">
            <p><?= format_date("(EEEE) d MMM yyyy", $queue["appointment_date"]) ?></p>
            <p><?= $queue["service_name"] ?></p>
            <span><?= $queue["visitor_phone"] ?></span>
          </div>
        </a>
        <?php endforeach; ?>

      <!-- Footer -->
    </main>
  </div>
  <footer class="footer">
    <p class="copyright">&copy;Copyright 2026</p>
    <span>Social Media :</span>
    <div>
      <img src="/assets/img/instagram.svg" alt="" />
      <span>@smkloremipsum</span>
    </div>
    <div>
      <img src="/assets/img/facebook.svg" alt="" />
      <span>@smkloremipsum</span>
    </div>
    <div>
      <img src="/assets/img/phone.svg" alt="" />
      <span>+62 123-2342-345</span>
    </div>
  </footer>
  <script src="/js/util.js"></script>
  <script>
    // Penggunaan
    
  </script>
</body>
</html>