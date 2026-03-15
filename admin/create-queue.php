<?php
require_once dirname(__DIR__)."/api/core.php";

if(!is_admin()) return abort(404);

if($_SERVER["REQUEST_METHOD"] == "POST") {
  if(!require_fields(["title", "date", "quota"], $_POST)) return abort();
  $title = htmlspecialchars($_POST["title"]);
  $date = date_create_immutable_from_format("Y-m-d", $_POST["date"]);
  $quota = intval($_POST["quota"]);
  if($quota <= 0) return alert("Kuota tidak valid", "/admin/create-queue.php");
  if($date == false) return abort();
  $diff = (new DateTime())->diff($date);
  if($diff->invert == 1) {
    return alert("Tanggal harus lebih besar dari hari ini", "/admin/create-queue.php");
  }
  $description = htmlspecialchars($_POST["description"] ?? "");
  query("INSERT INTO queues (`id`, `title`, `description`, `date`,`quota`) VALUES (NULL, ?, ?, ?, ?)", [$title, $description, $_POST["date"], $quota]);
  alert("Antrean berhasil dibuat!", "/admin/dashboard.php");
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Antrean</title>
</head>
<body>
  <a href="/admin/dashboard.php">Kembali</a>
  <form action="" method="post">
    <label>
      Judul
      <input type="text" name="title" placeholder="Judul...">
    </label>
    <label>
      Deskripsi (opsional)
      <textarea name="description" rows="3"></textarea>
    </label>
    <label>
      Tanggal
      <input type="date" name="date" min="<?= (new DateTime())->modify("+1 day")->format("Y-m-d") ?>" value="<?= (new DateTime())->modify("+1 day")->format("Y-m-d") ?>">
    </label>
    <label>
      Kuota
      <input type="text" inputmode="numeric" name="quota">
    </label>
    <button type="submit">Buat</button>
  </form>
</body>
</html>