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
$phone = $_GET["phone"];
$queue = query("SELECT * FROM queues WHERE visitor_phone = ? AND id = ?", [$phone, $id])->fetch_assoc();
if($queue == null) return abort(404);
$service = query("SELECT * FROM services WHERE id = ?", [$queue["service_id"]])->fetch_assoc();


if(isset($_COOKIE["voucher"]) && $payload = SimpleJWT::decode($_COOKIE["voucher"])) {
  if(!in_array($id, $payload[$phone])) {
    $payload[$phone][] = $id;
    $payload[$phone] = array_unique($payload[$phone]);
    $voucher = SimpleJWT::encode($payload);
  } else {
    $voucher = "";
  }
} else {
  $payload = [$phone => [$id]];
  $voucher = SimpleJWT::encode($payload);
}
if($voucher != "") setcookie("voucher", $voucher, time()+60*60*24*30);

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
            <a href="/antrean-berlangsung.php" class="btn light">Antrean Berlangsung</a>
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

      <div class="detailcards" id="detailCard">
        <div class="detailcard">
          <div class="detailcard-number"><?= $service["prefix"] ?><?= str_pad((string)$queue["queue_number"], 3, "0", STR_PAD_LEFT) ?></div>
          <div class="detailcard-info">
            <p><?= format_date("(EEEE) d MMM yyyy", $queue["appointment_date"]) ?></p>
            <p><?= $service["name"] ?></p>
            <span><?= $queue["visitor_phone"] ?></span>
          </div>
        </div>
      </div>

      <a class="antrian-baru" target="_blank" href="/">Ambil Antrean Baru</a>
      <a class="antrian-baru" id="downloadImg">Unduh Gambar</a>

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
  <script src="/js/html2canvas.min.js"></script>
  <script>
    async function captureTicket() {
      const element = query("#detailCard");
      
      try {
        // Konversi HTML ke canvas
        const canvas = await html2canvas(element, {
            scale: 2, // Kualitas lebih tinggi
            backgroundColor: '#ffffff',
            logging: false,
            useCORS: true // Jika ada gambar dari domain lain
        });
        
        // Download sebagai PNG
        const link = query("#downloadImg");
        link.download = 'tiket-antrean.png';
        link.href = canvas.toDataURL();
        // link.click();
        
        // Atau tampilkan preview
        // document.body.appendChild(canvas);
        
      } catch (error) {
        console.error('Gagal konversi:', error);
        alert('Gagal membuat gambar, silakan coba screenshot manual');
      }
    }
    captureTicket();
  </script>
</body>
</html>