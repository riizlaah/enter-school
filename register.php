<?php
require 'core.php';


if(has_login()) exit;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar</title>
  <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
  <div class="container">
    <form action="action-register.php" method="post">
      <h1 class="t-center">Daftar</h1>
      <label>
        Nama Lengkap
        <input type="text" name="fullname" placeholder="Adi Nugroho...">
      </label>
      <label>
        No. Handphone
        <input type="number" name="phone_num" placeholder="+08xxxxxxxxxx">
      </label>
      <label class="pass">
        Password
        <input type="password" name="password">
        <i data-feather="eye"></i>
      </label>
      <button type="submit">Daftar</button>
      <span>Sudah punya akun? <a href="/">Login</a></span>
    </form>
  </div>
  <script src="/assets/feather.min.js"></script>
  <script>feather.replace();</script>
  <script src="/assets/password_toggle.js"></script>
</body>
</html>