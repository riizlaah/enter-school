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
  <link rel="stylesheet" href="/assets/tailwind.css">
</head>
<body>
  <form action="?id=<?= $id ?>" method="post" class="w-md p-8 shadow-lg shadow-blue-200 rounded-lg mx-auto mt-4 flex flex-col gap-2">
    <h1 class="text-center font-bold text-3xl">Edit Antrean</h1>
    <label class="flex flex-col">
      Judul
      <input type="text" name="title" placeholder="Pendaftaran xyz" value="<?= $queue["title"] ?>" class="border border-gray-200 outline-none focus:shadow focus:shadow-gray-400 p-2 w-full rounded-lg transition">
    </label class="flex flex-col">
    <label>
      Deskripsi (opsional)
      <textarea name="description" rows="3" class="border border-gray-200 outline-none focus:shadow focus:shadow-gray-400 p-2 w-full rounded-lg transition"><?= $queue["description"] ?></textarea>
    </label>
    <label class="flex flex-col">
      Tanggal
      <input type="date" name="date" min="<?= (new DateTime())->modify("+1 day")->format("Y-m-d") ?>" value="<?= $queue["date"] ?>" class="border border-gray-200 outline-none focus:shadow focus:shadow-gray-400 p-2 w-full rounded-lg transition">
    </label>
    <label class="flex flex-col">
      Kuota
      <input type="text" inputmode="numeric" name="quota" value="<?= $queue["quota"] ?>" placeholder="50..." class="border border-gray-200 outline-none focus:shadow focus:shadow-gray-400 p-2 w-full rounded-lg transition">
    </label>
    <div class="flex gap-3 mt-4">
      <a href="/admin/dashboard.php" class="bg-gray-300 p-2 hover:scale-105 transition-all rounded grow text-center">Kembali</a>
      <button type="submit" class="bg-blue-600 text-white p-2 hover:scale-105 transition-all rounded grow text-center">Simpan</button>
    </div>
  </form>
</body>
</html>