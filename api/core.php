<?php
session_start();

$host = env("db_host"));
$user = env("db_user");
$pass = env("db_pass");
$db_name = env("db_name");
$env = [];

$conn = new mysqli($host, $user, $pass, $db_name);
setlocale(LC_TIME, 'id_ID');

if($conn->connect_error) {
  die("Koneksi gagal: ".$conn->connect_error);
}
load_env();

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

function get_queues() {
  $res = query("SELECT q.* FROM queues q LEFT JOIN user_queues uq ON uq.queue_id = q.id GROUP BY q.id HAVING q.quota > COUNT(uq.id)")
    ->fetch_all(MYSQLI_ASSOC);
  return array_map(function($item) {
    $fmt = datefmt_create("id-ID", IntlDateFormatter::FULL, IntlDateFormatter::FULL, 'Asia/Jakarta', IntlDateFormatter::GREGORIAN, "- d MMMM yyyy");
    $date = date_create_immutable_from_format('Y-m-d', $item["date"]);
    $item["date"] = $fmt->format($date);
    return $item;
  }, $res);
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
  $env_content = file_get_contents(dirname(__DIR__)."/.env");
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
