<?php
require_once dirname(__DIR__).'/api/core.php';

if(!is_admin()) return abort(404);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="/assets/tailwind.css">
</head>
<body>
  <div class="flex flex-col p-8 gap-2">
    <div class="flex gap-2">
      <a href="/logout.php" role="button" class="p-3 font-medium rounded bg-blue-400 text-white w-fit">Log Out</a>
      <a href="/admin/create-queue.php" role="button" class="p-3 font-medium rounded bg-blue-400 text-white w-fit">Antrean Baru</a>
    </div>
    <div class="flex gap-2">
      <div class="flex w-30 p-2 rounded border border-gray-400">
        <select name="filter" id="filter" class="outline-none grow">
          <option value="all">Semua</option>
          <option value="latest" selected>Terbaru</option>
          <option value="completed">Selesai</option>
        </select>
      </div>
      <input type="search" name="search" id="search" placeholder="Cari..." class="p-3 rounded border border-gray-400 focus:shadow focus:border-gray-400 outline-none w-80">
    </div>
    <div class="grid grid-cols-3 gap-4 p-4" id="queues">

    </div>
  </div>
  <script>
    let timer = 0;
    function deleteQueue(id, title) {
      if(!confirm(`Yakin ingin menghapus ${title}?`)) return;
      fetch(`/api/delete-queue.php?id=${id}`, {method: "DELETE"})
      .then(res => {
        if(res.ok) {
          document.location.reload();
        } else {
          console.log(res)
        }
      }).catch(e => console.error(e));
    }
    function search(str = "") {
      fetch(`/api/queues.php${str == "" ? "" : "?s=" + encodeURIComponent(str)}`, {
        credentials: "include"
      })
      .then(res => {
        if(res.ok) {
          res.json().then(datas => {
            const queues = document.getElementById("queues");
            queues.innerHTML = "";
            datas.forEach(data => {
              queues.innerHTML += `
              <div class="p-4 rounded-2xl shadow-md">
                <h1 class="font-bold text-2xl border-b border-b-gray-300 pb-1 mb-1">${data.title}</h1>
                <span>${data.date}</span>
                <p>${(data.description ?? "").substring(0, 32)}...</p>
                <p>${data.registrar_count}/${data.quota} Pendaftar</p>
                <div class="flex gap-1 mt-2">
                  <a href="/admin/edit-queue.php?id=${data.id}" role="button" class="grow bg-amber-400 p-2 font-medium rounded-xl text-center">Edit</a>
                  <a href="/admin/queue-detail.php?id=${data.id}" role="button" class="grow bg-blue-500 text-white p-2 font-medium rounded-xl text-center">Detail</a>
                  <button onclick="deleteQueue(${data.id}, '${data.title}')" class="grow bg-red-500 text-white p-2 font-medium rounded-xl text-center">Hapus</button>
                </div>
              </div>`;
            });
          });
        }
      }).catch(e => console.error(e));
    }
    search();
    document.getElementById("search").oninput = () => {
      clearTimeout(timer);
      timer = setTimeout(() => {
        search(document.getElementById("search").value);
      }, 500);
    };
  </script>
</body>
</html>