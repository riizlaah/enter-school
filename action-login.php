<?php
require 'core.php';

if(has_login()) exit;

global $conn;

$phone_num = escape($_POST['phone_num']);
$pass = $_POST['password'];

$data = $conn->query("SELECT `id`,`password` FROM `users` WHERE `phone_num`='$phone_num'")->fetch_assoc();

if(is_null($data)) alert('Akun tidak ada!', 'index.php');

if(!password_verify($pass, $data['password'])) alert('Kredensial salah!', 'index.php');

$_SESSION['login'] = $data['id'];
session_regenerate_id(true);

redirect('dashboard');