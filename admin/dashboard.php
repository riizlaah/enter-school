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
  <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
  <div class="column padding">
    <a href="/logout.php" role="button" style="width: fit-content;">Log Out</a>
    <div class="row">
      <a href="/admin/create-queue.php" role="button">Antrean Baru</a>
      <input type="search" name="search" id="search" placeholder="Cari...">
    </div>
    <div id="queues" class="cols-3">

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
              <div>
                <h1>${data.title}</h1>
                <h2>${data.date}</h2>
                <p>${(data.description ?? "").substring(0, 32)}...</p>
                <p>${data.registrar_count}/${data.quota} Pendaftar</p>
                <div class="buttons">
                  <a href="/admin/edit-queue.php?id=${data.id}" role="button">Edit</a>
                  <a href="/admin/queue-detail.php?id=${data.id}" role="button">Detail</a>
                  <button onclick="deleteQueue(${data.id}, '${data.title}')">Hapus</button>
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