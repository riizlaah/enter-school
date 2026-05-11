<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Enter School</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <div class="spanduk"><p class="judul">Pengambilan Antrean Pendaftaran Sekolah</p></div>
    <div class="container">
      <!-- Sidebar -->
      <aside class="sidebar">
        <div class="logo">
          <img src="img/logo-teks.png" alt="" srcset="" />
        </div>
        <nav class="layer-menu">
          <ul class="menu">
            <li><a href="index.php" class="btn dark">Ambil Antrean</a></li>
            <li>
              <a href="antrian-berlangsung.php" class="btn light"
                >Antrean Berlangsung</a
              >
            </li  text-align: center;>
            <li>
              <a href="antrian-milikmu.php" class="btn light"
                >Lihat AntreanMu</a
              >
            </li>
          </ul>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="main-index">
        <h1 class="ambil-antrean">Ambil Antrean</h1>
        <form class="form-group">
          <div class="form-field">
            <label for="phone">No. Telepon</label>
            <input
              type="tel"
              id="phone"
              placeholder="08xxxxxxxxxx"
              required
            />
          </div>

          <div class="form-field">
            <label for="date">Pilih Tanggal</label>
            <select id="date" required>
              <option value="">Pilih Tanggal dan Gelombang</option>
              <option value="option1">5 Juli 2045</option>
              <option value="option2">6 Juli 2045</option>
              <option value="option3">7 Juli 2045</option>
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
        <img src="img/instagram.svg" alt="" />
        <span>@smkloremipsum</span>
      </div>
      <div>
        <img src="img/facebook.svg" alt="" />
        <span>@smkloremipsum</span>
      </div>
      <div>
        <img src="img/phone.svg" alt="" />
        <span>+62 123-2342-345</span>
      </div>
    </footer>
  </body>
</html>