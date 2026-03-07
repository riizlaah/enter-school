<?php
session_start();

$host = "localhost";
$user = "nafan";
$pass = "Heks137?";
$db_name = "enter_school";
$env = [];

$conn = new mysqli($host, $user, $pass, $db_name);

if($conn->connect_error) {
  die("Koneksi gagal: ".$conn->connect_error);
}

function escape($val) {
  global $conn;
  return mysqli_real_escape_string($conn, $val);
}

function query($string, $params = null) {
  global $conn;
  $stmt = $conn->prepare($string);
  if($stmt->execute($params)) return $stmt->get_result();
  return null;
}

function redirect($to, $wait = 0) {
  if($wait > 0) {
    return header("refresh:$wait;url=$to");
  }
  return header("location:$to");
}

function alert($msg, $redirect = null) {
  if(!is_null($redirect)) {
    $redirect = "document.location.href = '$redirect';";
    echo "<script>alert('$msg');$redirect</script>";
    exit;
  }
  echo "<script>alert('$msg');</script>";
}

function check_login() {
  if(!isset($_SESSION['login'])) {
    redirect('index.php');
    return false;
  }
  return true;
}

function has_login() {
  if(isset($_SESSION['login'])) {
    redirect('main.php');
    return true;
  }
  return false;
}

function abort($code = 400) {
  return http_response_code($code);
}

function is_authorized(int $id) {
  return $id === intval($_SESSION['login']);
}

function load_env() {
  global $env;
  $env_content = file_get_contents("./.env");
  preg_match_all("/^([a-z_]+)=(.+)$/m", $env_content, $matches);
  foreach($matches[1] as $i => $key) {
    $env[$key] = $matches[2][$i];
  }
}

function env($name, $default = null) {
  global $env;
  if(!isset($env[$name])) return $default;
  return $env[$name];
}

function json_msg($msg, $code = 400) {
  http_response_code($code);
  echo "{\"message\": \"$msg\"}";
}
