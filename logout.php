<?php
require 'core.php';

if(!check_login()) exit;

$_SESSION = [];

$params = session_get_cookie_params();
setcookie(session_name(), '', time() - 42000,
  $params["path"], $params["domain"],
  $params["secure"], $params["httponly"]
);

session_destroy();

session_regenerate_id();

redirect('/');