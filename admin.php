<?php
require 'core.php';

if(has_login()) exit;

if($_SERVER["REQUEST_METHOD"] == "POST") {
  load_env();
  if(!isset($_POST['email']) or !isset($_POST['password'])) return abort(400);
  if(!is_string($_POST['email']) or !is_string($_POST['password'])) return abort(400);
  if( ($_POST['email'] === env('admin_email')) and ($_POST['password'] === env('admin_pass')) ) {
    $_SESSION['admin'] = true;
    return redirect('/dashboard');
  } else {
    return redirect("https://youtu.be/xvFZjo5PgG0");
  }
}

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
    <form action="" method="post">
      <h1 class="t-center">Login</h1>
      <label>
        Email
        <input type="text" name="email">
      </label>
      <label class="pass">
        Password
        <input type="password" name="password">
        <i data-feather="eye"></i>
      </label>
      <button type="submit">Login</button>
    </form>
  </div>
  <script src="/assets/feather.min.js"></script>
  <script>feather.replace();</script>
  <script src="/assets/password_toggle.js"></script>
</body>
</html>