<?php
require_once "./core.php";

header("content-type: application/json");
// echo file_get_contents("php://input");

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $input = file_get_contents("php://input");
  $json = json_decode($input, true);
  if(!isset($json["uuid"])) {
    json_msg("uuid is required");
    return;
  }
  $res = query("SELECT * FROM `devices` WHERE `uuid`=?", [$json["uuid"]]);
  if($res == null) return json_msg("query failed.");
  $data = $res->fetch_assoc();
  if($data == null) {
    query("INSERT INTO `devices` (`id`, `uuid`) VALUES (NULL, ?)", [$json["uuid"]]);
    $res = query("SELECT * FROM `devices` WHERE `uuid`=?", [$json["uuid"]]);
    $data = $res->fetch_assoc();
  }
  echo json_encode($data);
}