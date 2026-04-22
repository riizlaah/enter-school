<?php
require dirname(__DIR__).'/api/core.php';

if(isset($_SESSION["admin"])) exit;

if($_SERVER["REQUEST_METHOD"] == "POST") {
  if(!isset($_POST['email']) or !isset($_POST['password'])) return abort(400);
  if(!is_string($_POST['email']) or !is_string($_POST['password'])) return abort(400);
  if( ($_POST['email'] === env('admin_email')) and ($_POST['password'] === env('admin_pass')) ) {
    $_SESSION['admin'] = true;
    return redirect('/admin/dashboard.php');
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
  <link rel="stylesheet" href="/assets/tailwind.css">
</head>
<body>
  <form action="" method="post" class="w-md p-8 shadow-lg shadow-blue-200 rounded-lg mx-auto mt-4 flex flex-col gap-2">
    <h1 class="text-center font-bold text-3xl">Login</h1>
    <label class="flex flex-col">
      Email
      <input type="email" name="email" class="border border-gray-200 outline-none focus:shadow focus:shadow-gray-400 p-2 w-full rounded-lg transition">
    </label>
    <label class="pass flex flex-col relative">
      Password
      <input type="password" name="password" class="border border-gray-200 outline-none focus:shadow focus:shadow-gray-400 p-2 pr-9 rounded-lg transition">
      <i data-feather="eye" class="absolute right-2 bottom-2.5"></i>
    </label>
    <button type="submit" class="bg-blue-600 text-white p-2 hover:scale-105 transition-all rounded mt-4">Login</button>
  </form>
  <script src="/js/feather.min.js"></script>
  <script>feather.replace();</script>
  <script src="/js/password_toggle.js"></script>
</body>
</html>