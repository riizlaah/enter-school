<?php
require_once 'api/core.php';


if($_SERVER["REQUEST_METHOD"] == "POST") {
  // memastikan bahwa input 'uuid', 'phone_number', 'queue_id' ada
  if(!isset($_POST["uuid"])) return http_response_code(400);
  if(!isset($_POST["phone_number"])) return http_response_code(400);
  if(!isset($_POST["queue_id"])) return http_response_code(400);
  // memastikan 'phone_number' valid
  if(!preg_match("/^\+?\d{12,}$/", $_POST["phone_number"])) return alert("No. Telepon tidak valid.");
  // memastikan user ada
  $user = query("SELECT id FROM devices WHERE uuid = ?", [$_POST["uuid"]])->fetch_assoc();
  if($user == null) return http_response_code(400);
  // memastikan queue_id ada
  if(!is_numeric($_POST["queue_id"])) return http_response_code(400);
  if(!is_exists("queues", "id = ?", [$_POST["queue_id"]])) return http_response_code(400);
  // memastikan queue masih bisa menampung
  if(query("SELECT q.id, q.quota
    FROM queues q
    LEFT JOIN user_queues uq ON q.id = uq.queue_id
    WHERE q.id = ?
    GROUP BY q.id
    HAVING q.quota > COUNT(uq.id)", [$_POST["queue_id"]])->num_rows == 0) return http_response_code(400);
  // cari no. telp, jika tidak ada maka tambahkan
  $phone = query("SELECT id FROM phone_numbers WHERE phone_number = ?", [$_POST["phone_number"]])->fetch_assoc();
  if($phone == null) {
    query("INSERT INTO phone_numbers (id, device_id, phone_number) VALUES (NULL, ?, ?)", [$user["id"], $_POST["phone_number"]]);
    $phone = query("SELECT id FROM phone_numbers WHERE phone_number = ?", [$_POST["phone_number"]])->fetch_assoc();
  }
  if(is_exists("user_queues", "phone_id = ? AND queue_id = ?", [$phone["id"], $_POST["queue_id"]])) return alert("Anda sudah mendaftar!", "/");
  // insert ke db
  $code = base_convert(bin2hex(random_bytes(5)), 16, 36);
  $code = substr_replace(strtoupper($code), "-", 4, 0);
  while(is_exists("user_queues", "code = ?", [$code])) {
    $code = base_convert(bin2hex(random_bytes(5)), 16, 36);
    $code = substr_replace(strtoupper($code), "-", 4, 0);
  }
  query("INSERT INTO user_queues (id, phone_id, queue_id, device_id, code, called_at, completed_at) VALUES (NULL, ?, ?, ?, ?, NULL, NULL)", [$phone["id"], $_POST["queue_id"], $user["id"], $code]);
  return alert("Sukses mendaftar!", "/");
}

$queues = get_queues();

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
    <input type="hidden" name="uuid" value="" id="device_uuid">
    <label>
      No. HP
      <input type="text" name="phone_number" inputmode="numeric" placeholder="No. HP..." pattern="^\+?\d{12,}$">
    </label>
    <br>
    <label>
      Antrean
      <select name="queue_id">
        <?php foreach($queues as $q): ?>
          <option value="<?= $q["id"] ?>"><?= $q["title"] . $q["date"] ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <br>
    <button type="submit">Ambil</button>
  </form>
  <script src="/js/device.js"></script>
  <script>
    document.getElementById("device_uuid").value = localStorage.getItem("uuid");
  </script>
</body>
</html>