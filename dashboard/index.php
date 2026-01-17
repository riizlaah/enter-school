<?php
require '../core.php';

if(isset($_SESSION['admin'])) {
  include 'admin.php';
} else {
  include 'user.php';
}
