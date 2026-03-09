<?php
require_once 'core.php';


if(!isset($_GET["id"])) return http_response_code(400);
if(!is_numeric($_GET["id"])) return http_response_code(400);

header("content-type: application/json");


$queue = query("SELECT
  uq.id, q.id as queue_id, uq.code, pn.phone_number, q.title, q.description,
  q.date, q.quota, q.status AS queue_status, uq.called_at, uq.completed_at, uq.created_at
  FROM user_queues uq
  LEFT JOIN queues q ON uq.queue_id = q.id
  LEFT JOIN phone_numbers pn ON pn.id = uq.phone_id
  WHERE uq.id = ?", [intval($_GET["id"])]
  )->fetch_assoc();

if($queue == null) {
  echo '{}';
  return;
}

$queue_order = query("SELECT row_num FROM
  (SELECT uq.id, ROW_NUMBER() OVER (ORDER BY uq.created_at) AS row_num
  FROM user_queues uq WHERE uq.queue_id = ?) ranked WHERE ranked.id = ?", [$queue["queue_id"], $_GET["id"]])->fetch_assoc();

$queue["queue_status"] = match ($queue["queue_status"]) {
   null => "Belum dimulai",
   "running" => "Berlangsung",
   "stopped" => "Dihentikan",
   "completed" => "Selesai",
};
if($queue["called_at"] == null && $queue["completed_at"] == null) {
  $queue["status"] = "Mengantre";
} elseif($queue["called_at"] != null && $queue["completed_at"] == null) {
  $queue["status"] = "Dipanggil";
} else {
  $queue["status"] = "Selesai";
}
$queue["position"] = $queue_order["row_num"];

echo json_encode($queue);