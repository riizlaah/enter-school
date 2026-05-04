<?php
require "app/core.php";




$lockets = get_lockets();


?>

<!doctype html>
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
            <a href="" class="btn light">Lihat AntreanMu</a>
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
            required />
        </div>

        <div class="form-field">
          <label for="locket">Pilih Loket</label>
          <select id="locket" required>
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
</body>

</html>