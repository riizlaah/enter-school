<?php
require_once "app/core.php";

// papan pengumuman

$lockets = query("SELECT * FROM `counters` WHERE is_active = 1")->fetch_all(MYSQLI_ASSOC);
foreach($lockets as $idx => $locket) {
  $services = query("SELECT * FROM `services` s LEFT JOIN `counter_service` cs ON s.id = cs.service_id WHERE cs.counter_id = ? AND s.is_active = 1", [$locket["id"]])->fetch_all(MYSQLI_ASSOC);
  foreach($services as $idx2 => $service) {
    $count_query = "SELECT COUNT(*) as count FROM `queues` q WHERE q.counter_id = ? AND q.service_id = ? AND q.appointment_date = ?";
    $count_query_params = [$locket["id"], $service["id"], today_str()];
    $waiting = query("$count_query AND q.status = 'waiting'", $count_query_params)->fetch_assoc();
    $done = query("$count_query AND q.status = 'done'", $count_query_params)->fetch_assoc();
    $total = query("$count_query AND q.status != 'skipped'", $count_query_params)->fetch_assoc();
    $today_schedule = query("SELECT * FROM `service_schedules` ss WHERE ss.service_id = ? AND `date` = ?", [$service["id"], today_str()])->fetch_assoc();
    $remaining_quota = $today_schedule ? max(0, $today_schedule["max_quota"] - $total["count"]) : 0;
    $services[$idx2]["waiting"] = $waiting["count"];
    $services[$idx2]["done"] = $done["count"];
    $services[$idx2]["remaining_quota"] = $remaining_quota;
    $services[$idx2]["current_queue"] = query("SELECT * FROM `queues` q WHERE q.counter_id = ? AND q.service_id = ? AND q.appointment_date = ? AND q.status = 'serving'", $count_query_params)->fetch_assoc();
    $services[$idx2]["is_opened"] = $today_schedule && $today_schedule["is_open"] && ($done["count"] < $today_schedule["max_quota"] || $remaining_quota > 0);
  }
  $lockets[$idx]["services"] = $services;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Enter School</title>
  <link rel="stylesheet" href="/assets/style-v2.css" />
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
            <a href="/antrean-berlangsung.php" class="btn dark">Antrean Berlangsung</a>
          </li>
          <li>
            <a href="/antrean-saya.php" class="btn light">Lihat AntreanMu</a>
          </li>
        </ul>
      </nav>
    </aside>
    <main class="main">
      <div class="loket">
        <?php foreach($lockets as $locket): ?>
          <div class="loket-card">
            <span><?= $locket["name"] ?></span>
            <div>
              <?php if(count($locket["services"]) == 0): ?>
                <span class="empty">Tidak ada layanan yang aktif</span>
              <?php endif; ?>
              <?php foreach($locket["services"] as $service): ?>
                <div class="layanan-card">
                  <div>
                    <span class="layanan"><?= $service["name"] ?></span>
                    <span class="badge <?= $service["is_opened"] ? "green" : "red" ?>"><?= $service["is_opened"] ? "Buka" : "Tutup" ?></span>
                  </div>
                  <span class="no-antrean">
                    <?= $service["current_queue"] ? $service["prefix"] . str_pad((string)$service["current_queue"]["queue_number"], 3, "0", STR_PAD_LEFT) : "- - -" ?>
                  </span>
                  <div class="row">
                    <div class="small-card">
                      <span>Menunggu</span>
                      <span><?= $service["waiting"] ?></span>
                    </div>
                    <div class="small-card">
                      <span>Selesai</span>
                      <span><?= $service["done"] ?></span>
                    </div>
                    <div class="small-card">
                      <span>Sisa Kuota</span>
                      <span><?= $service["remaining_quota"] == 0 ? "-" : $service["remaining_quota"] ?></span>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
        </div>
      </div>
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
    setTimeout(() => {document.location.reload()}, 10000);
  </script>
</body>
</html>