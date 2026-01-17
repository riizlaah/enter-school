<?php
require 'core.php';

if(has_login()) exit;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
  <div class="container">
    <form action="action-login.php" method="post">
      <h1 class="t-center">Login</h1>
      <label>
        No. Handphone
        <input type="text" name="phone_num" placeholder="+08xxxxxxxxxx">
      </label>
      <label class="pass">
        Password
        <input type="password" name="password">
        <i data-feather="eye"></i>
      </label>
      <label class="row">
        <input type="checkbox" name="remember_me">
        Ingat Saya
      </label>
      <button type="submit">Login</button>
      <span>Belum punya akun? <a href="register.php">Daftar sekarang</a></span>
    </form>
  </div>
  <script src="/assets/feather.min.js"></script>
  <script>feather.replace();</script>
  <script src="/assets/password_toggle.js"></script>
</body>
</html>