<?php
require "app/core.php";


if($_SERVER["REQUEST_METHOD"] == "POST") {
  if(!require_fields(["phone", "locket_id", "service_id"], $_POST)) {
    return abort(400);
  }
  if(!preg_match("/^\+?\d{10,}$/", $_POST["phone"])) return alert("No. Telepon tidak valid.");
  if(!is_numeric($_POST["service_id"]) or !is_exists("services", "id = ?", [$_POST["service_id"]])) {
    return abort(404);
  }
  if(!is_numeric($_POST["locket_id"]) or !is_exists("counters", "id = ?", [$_POST["locket_id"]])) {
    return abort(404);
  }
  global $conn;

  try {
    $service_id = intval($_POST["service_id"]);
    $counter_id = intval($_POST["locket_id"]);

    $conn->begin_transaction();
    $ss = query("SELECT * FROM service_schedules WHERE service_id = ? AND `date` = ?", [$service_id, today_str()])->fetch_assoc();
    if($ss == null || $ss["is_open"] == 0) return alert("Layanan tidak tersedia", "/");
    $booked = query("SELECT COUNT(*) AS row_count FROM queues WHERE service_id = ? AND appointment_date = ? AND `status` != 'skipped'", [$service_id, today_str()])->fetch_assoc()["row_count"];
    if($booked >= $ss["max_quota"]) return alert("Kuota tidak tersedia", "/");
    $next_number = query("SELECT MAX(q.queue_number) AS last_num FROM queues q WHERE service_id = ? AND appointment_date = ?", [$service_id, today_str()])->fetch_assoc()["last_num"] + 1;
    query("INSERT INTO `queues` (service_id, counter_id, visitor_phone, queue_number, appointment_date) VALUES (?, ?, ?, ?, ?)", [$service_id, $counter_id, $_POST["phone"], $next_number, today_str()]);
    $conn->commit();
    $id = query("SELECT * FROM `queues` ORDER BY id DESC LIMIT 1")->fetch_assoc()["id"];
    alert("Berhasil", "/detail-antrean.php?id=$id&phone=".urlencode($_POST["phone"]));
  } catch(mysqli_sql_exception $e) {
    $conn->rollback();
    alert("Gagal", "/");
  }
  return;
}



$lockets = get_lockets();


?>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Enter School</title>
  <link rel="stylesheet" href="assets/style-v1.css" />
</head>

<body>
  <div class="spanduk">
    <p class="judul">Pengambilan Antrean Pendaftaran Sekolah</p>
  </div>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">
        <img src="assets/img/logo-teks.png" alt="" srcset="" />
      </div>
      <nav class="layer-menu">
        <ul class="menu">
          <li><a href="" class="btn dark">Ambil Antrean</a></li>
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
      <h1 class="ambil-antrean">Ambil Antrean</h1>
      <form class="form-group" method="post" action="">
        <div class="form-field">
          <label for="phone">No. Telepon</label>
          <input
            type="tel"
            id="phone"
            placeholder="08xxxxxxxxxx"
            name="phone"
            required />
        </div>

        <!-- <div class="form-field">
          <label for="name">Vis</label>
          <input
            type="text"
            id="name"
            name="name"/>
        </div> -->

        <div class="form-field">
          <label for="locket">Pilih Loket</label>
          <select id="locket" name="locket_id" required>
            <?php foreach($lockets as $locket): ?>
              <option value="<?= $locket["id"]  ?>"><?= $locket["name"] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        
        <div class="form-field">
          <label for="service">Pilih Layanan</label>
          <select id="service" name="service_id" required>
            <?php if(count($lockets) > 0): ?>
              <?php foreach($lockets[0]["services"] as $serv): ?>
                <option value="<?= $serv["id"]  ?>"><?= $serv["name"] ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>

        <button type="submit" class="btn-ambil">Ambil</button>
      </form>

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
  <script src="/js/util.js"></script>
  <script>
    let locket = query("#locket");
    let service_select = query("#service");
    let services = [
      <?php
      foreach($lockets as $locket) {
        $services = array_map(function($item) {
          return "{id: {$item['id']}, name: \"{$item['name']}\"}";
        }, $locket["services"]);
        $services = implode(",", $services);
        echo "{id: {$locket['id']}, services: [$services]},";
      }
      ?>
    ]

    locket.onchange = () => {
      let val = locket.value;
      let services2 = services.find((item) => item.id == val);
      service_select.replaceChildren();
      console.log(services2.services);
      services2.services.forEach((item) => {
        service_select.innerHTML += `<option value="${item.id}">${item.name}</option>`;
      });
    };
  </script>
</body>
</html>