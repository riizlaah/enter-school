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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Antrean</title>
  <link rel="stylesheet" href="/assets/tailwind.css">
</head>
<body>
  <form action="" method="post" class="w-md p-8 shadow-lg shadow-blue-200 rounded-lg mx-auto mt-4 flex flex-col gap-2">
    <div class="flex items-center mb-4">
      <a href="/admin/dashboard.php"><i data-feather="arrow-left"></i></a>
      <h1 class="text-center font-bold text-3xl grow">Buat Antrean</h1>
    </div>
    <label class="flex flex-col">
      Judul
      <input type="text" name="title" placeholder="Pendaftaran xyz" class="border border-gray-200 outline-none focus:shadow focus:shadow-gray-400 p-2 w-full rounded-lg transition">
    </label>
    <label class="flex flex-col">
      Deskripsi (opsional)
      <textarea name="description" rows="3" class="border border-gray-200 outline-none focus:shadow focus:shadow-gray-400 p-2 w-full rounded-lg transition"></textarea>
    </label>
    <label class="flex flex-col">
      Tanggal
      <input type="date" name="date" min="<?= (new DateTime())->modify("+1 day")->format("Y-m-d") ?>" value="<?= (new DateTime())->modify("+1 day")->format("Y-m-d") ?>" class="border border-gray-200 outline-none focus:shadow focus:shadow-gray-400 p-2 w-full rounded-lg transition">
    </label>
    <label>
      Kuota
      <input type="text" inputmode="numeric" name="quota" placeholder="50" class="border border-gray-200 outline-none focus:shadow focus:shadow-gray-400 p-2 w-full rounded-lg transition">
    </label>
    <button type="submit" class="bg-blue-600 text-white p-2 hover:scale-105 transition-all rounded mt-4">Buat</button>
  </form>

  <script src="/js/feather.min.js"></script>
  <script>feather.replace()</script>
</body>
</html>