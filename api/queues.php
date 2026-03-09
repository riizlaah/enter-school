<?php
require_once 'core.php';

if($_SERVER["REQUEST_METHOD"] != "GET") return http_response_code(400);

header("content-type: application/json");

if(!isset($_GET["uuid"])) return http_response_code(400);
$uuid = $_GET["uuid"];

if(!is_string($uuid)) return http_response_code(400);

if(!is_exists("devices", "uuid = ?", [$uuid])) return http_response_code(400);

$phones = query("SELECT p.id FROM devices d LEFT JOIN phone_numbers p ON p.device_id = d.id WHERE uuid = ?", [$uuid])->fetch_all(MYSQLI_ASSOC);

if($phones == []) return "[]";

$phones_numbers = "(" . implode(",", array_map(fn($row) => strval($row["id"]), $phones)) . ")";


$datas = query("SELECT uq.id, uq.code, pn.phone_number, q.title, q.description, q.date, q.quota, q.status, uq.called_at, uq.completed_at, uq.created_at FROM user_queues uq LEFT JOIN queues q ON uq.id = q.id LEFT JOIN phone_numbers pn ON pn.id = uq.phone_id WHERE uq.phone_id IN $phones_numbers")->fetch_all(MYSQLI_ASSOC);

echo json_encode($datas);