<?php
require_once dirname(__DIR__)."/api/core.php";

if(!is_admin()) return abort(404);

if(!isset($_GET["id"])) return abort();
$id = intval($_GET["id"]);
if($id <= 0) return abort();
if(!is_exists("queues", "id = ?", [$id])) return abort(404);

if($_SERVER["REQUEST_METHOD"] == "POST") {
  if(!require_fields(["title", "date", "quota"], $_POST)) return abort();
  $title = htmlspecialchars($_POST["title"]);
  $date = date_create_immutable_from_format("Y-m-d", $_POST["date"]);
  $quota = intval($_POST["quota"]);
  if($quota <= 0) return alert("Kuota tidak valid", "/admin/edit-queue.php?id=$id");
  if($date == false) return abort();
  $diff = (new DateTime())->diff($date);
  if($diff->invert == 1) {
    return alert("Tanggal harus lebih besar dari hari ini", "/admin/edit-queue.php?id=$id");
  }
  $user_queue = query("SELECT COUNT(*) AS registrar_count FROM user_queues WHERE queue_id = ?", [$id])->fetch_assoc();
  if($user_queue["registrar_count"] > $quota) return alert("Kuota tidak boleh lebih kecil dari jumlah pendaftar yang ada", "/admin/edit-queue.php?id=$id");
  $description = htmlspecialchars($_POST["description"] ?? "");
  query("UPDATE queues SET `title` = ?, `description` = ?, `date` = ?, `quota` = ? WHERE `id` = ?", [$title, $description, $_POST["date"], $quota, $id]);
  alert("Antrean berhasil diubah!", "/admin/dashboard.php");
}

$queue = query("SELECT * FROM queues WHERE `id` = ?", [$id])->fetch_assoc();

?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Antrean</title>
</head>
<body>
  <form action="?id=<?= $id ?>" method="post">
    <label>
      Judul
      <input type="text" name="title" placeholder="Judul..." value="<?= $queue["title"] ?>">
    </label>
    <label>
      Deskripsi (opsional)
      <textarea name="description" rows="3"><?= $queue["description"] ?></textarea>
    </label>
    <label>
      Tanggal
      <input type="date" name="date" min="<?= (new DateTime())->modify("+1 day")->format("Y-m-d") ?>" value="<?= $queue["date"] ?>">
    </label>
    <label>
      Kuota
      <input type="text" inputmode="numeric" name="quota" value="<?= $queue["quota"] ?>" placeholder="50...">
    </label>
    <a href="/admin/dashboard.php">Kembali</a>
    <button type="submit">Ubah</button>
  </form>
</body>
</html>