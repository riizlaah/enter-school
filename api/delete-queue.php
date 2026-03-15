<?php
require_once __DIR__."/core.php";

if(!is_admin()) return abort(404); // lil trick :)
if(!isset($_GET["id"])) return abort();
$id = intval($_GET["id"]);
if($id <= 0) return abort();

query("DELETE FROM queues WHERE `id`=?", [$id]);

