<?php
require "core.php";

if(has_login()) exit;


if(!isset($_POST['fullname']) or !isset($_POST['phone_num']) or !isset($_POST['password'])) {
  return redirect('register.php');
}
if(!is_string($_POST['fullname'])) {
  abort(400);
  exit;
}
if(strlen($_POST['fullname']) === 0) {
  alert("Nama lengkap tidak boleh kosong!", 'register.php');
}
if(!preg_match("/^\d{12,14}$/", $_POST['phone_num'])) {
  alert('Bukan nomor telepon yang valid!', 'register.php');
}
if(!is_string($_POST['password'])) {
  abort(400);
  exit;
}
if(strlen($_POST['password']) < 8) {
  alert('Password setidaknya memiliki panjang 8 karakter!', 'register.php');
}


$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$fullname = escape($_POST['fullname']);
$phone_num = escape($_POST['phone_num']);

global $conn;

$phone_num_used = $conn->query("SELECT `id` FROM `users` WHERE `phone_num`='$phone_num'")->fetch_assoc();

if(!is_null($email_used)) {
  alert('Email ini sudah dipakai!', 'register.php');
}

$conn->query("INSERT INTO `users` (id, fullname, img, phone_num, password, remember_token) VALUES (NULL, '$fullname', 'default.jpg', '$phone_num', '$hashed_password', NULL)");

alert("Sukses!", 'index.php');
