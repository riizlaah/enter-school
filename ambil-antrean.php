<?php
require_once 'api/core.php';

$queues = get_queues();

if($_SERVER["REQUEST_METHOD"] == "POST") {
  return;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ambil Antrean</title>
</head>
<body>
  <h1>Ambil Antrean</h1>
  <form action="" method="post">
    <label>
      No. HP
      <input type="text" inputmode="numeric" placeholder="No. HP...">
    </label>
    <br>
    <label>
      Antrean
      <select name="antrean_id">
        <?php foreach($queues as $q): ?>
          <option value="<?= $q["id"] ?>"><?= $q["title"] . $q["date"] ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <br>
    <button type="submit">Ambil</button>
  </form>
  <script src="/js/device.js"></script>
</body>
</html>