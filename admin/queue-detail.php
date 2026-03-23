<?php
require_once dirname(__DIR__).'/api/core.php';

if(!is_admin()) return abort(404);
if(!isset($_GET["id"])) return abort();

$id = intval($_GET["id"]);
if($id == false || $id <= 0) return abort();

// set current called user
if($_SERVER["REQUEST_METHOD"] == "POST") {
  $input = file_get_contents("php://input");
  $json = json_decode($input, true);
  $uq_id = intval($json["uq_id"]);
  if($uq_id == false || $uq_id <= 0) return abort();
  $uq = query("SELECT id, called_at FROM user_queues WHERE id = ?", [$uq_id])->fetch_assoc();
  if($uq == null && $json["status"] == "completed") return abort(404);
  if($uq["called_at"] != null) {
    $called_at = date_create_from_format("Y-m-d H:i:s", $uq["called_at"]);
    $diff = $called_at->diff(date_create());
    if($diff->i < 1 && $diff->h < 1) {
      return json_msg("Mohon tunggu setidaknya 1 menit sebelum menyelesaikan sebuah antrean");
    }
  }
  if($json["status"] == "called") {
    query("UPDATE user_queues SET called_at = ? WHERE id = ?", [date("Y-m-d H:i:s"), $uq_id]);
  } elseif($json["status"] == "completed") {
    query("UPDATE user_queues SET completed_at = ? WHERE id = ?", [date("Y-m-d H:i:s"), $uq_id]);
  }
  json_msg("Updated successfully", 200);
  return;
}

// patch (update status only)
if($_SERVER["REQUEST_METHOD"] == "PATCH") {
  $input = file_get_contents("php://input");
  $json = json_decode($input, true);
  if(!isset($json["status"])) return abort();
  if(!in_array($json["status"], ["running", "stopped", "completed"])) return abort();
  query("UPDATE queues SET `status` = ? WHERE `id` = ?", [$json["status"], $id]);
  header("content-type: application/json");
  echo json_encode([
    "id" => $id,
    "status" => $json["status"]
  ]);
  return;
}

if(isset($_GET["d"])) {
  if($_SERVER["REQUEST_METHOD"] != "GET") return abort();
  if($_GET["d"] == "q") {
    // get queue info
    $data = query("SELECT q.id, q.title, q.status, q.date FROM queues q
    WHERE q.id = ?", [$id])->fetch_assoc();
    $data = [
      "id" => $id,
      "title" => $data["title"],
      "status" => $data["status"],
      "date" => $data["date"]
    ];
    $uq = query("SELECT * FROM
    (
      SELECT uq.id, ROW_NUMBER() OVER (ORDER BY uq.created_at) AS queue_order, uq.code, uq.called_at, uq.completed_at, p.phone_number 
      FROM user_queues uq LEFT JOIN phone_numbers p ON uq.phone_id = p.id 
      WHERE uq.queue_id = ?
    ) r WHERE r.called_at IS NOT NULL AND r.completed_at IS NULL
    ORDER BY r.called_at DESC", 
    [$id])->fetch_assoc();
    $fmt = datefmt_create("id-ID", IntlDateFormatter::FULL, IntlDateFormatter::FULL, 'Asia/Jakarta', IntlDateFormatter::GREGORIAN, "d MMMM yyyy");
    $data["date"] = $fmt->format(date_create($data["date"]));
    $data["curr_queue"] = $uq;
    $data["sec_left"] = 0;
    if($uq != null) {
      $called_at = date_create_from_format("Y-m-d H:i:s", $uq["called_at"]);
      $diff = $called_at->diff(date_create());
      $data["sec_left"] = $diff->i < 1 && $diff->h < 1 ? $diff->s : 0;
    }
    header("content-type: application/json");
    echo json_encode($data);
  } elseif($_GET["d"] == "u") {
    // get users of a queue
    $search = "";
    $params = [$id];
    if(isset($_GET["s"])) {
      $search = "AND code LIKE ? OR phone_number LIKE ?";
      $str = "%".$_GET["s"]."%";
      $params = [$id, $str, $str];
    }
    $users = query("SELECT * FROM
    (SELECT uq.id, uq.code, p.phone_number, called_at, completed_at,
    ROW_NUMBER() OVER (ORDER BY uq.created_at) AS queue_order
    FROM user_queues uq LEFT JOIN phone_numbers p ON uq.phone_id = p.id 
    WHERE uq.queue_id = ?) ranked WHERE called_at IS NULL AND completed_at IS NULL $search", $params)->fetch_all(MYSQLI_ASSOC);
    header("content-type: application/json");
    echo json_encode($users);
  }
  return;
}

?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Antrean</title>
  <link rel="stylesheet" href="/assets/tailwind.css">
</head>
<body>
  <div class="p-4 lg:w-3xl mx-auto">
    <div class="w-full font-semibold text-2xl text-center" id="q_title"></div>
    <div class="flex p-2 gap-2 items-center w-full">
      <a href="/admin/dashboard.php" class="bg-blue-400 p-2 text-white rounded-md">Kembali</a>
      <button type="button" class="bg-blue-400 p-2 text-white rounded-md" id="q_status"></button>
      <span class="grow text-end font-medium" id="q_date"></span>
    </div>
    <div class="flex flex-col">
      <div id="currentUser" class="p-3 rounded-lg bg-gray-100">
        <span class="p-2 text-2xl font-medium">Dipanggil sekarang</span>
        <div class="text-2xl font-medium p-2 hidden" id="no_curr_uq">-</div>
        <div class="items-center mt-4 hidden" id="curr_uq" data-uqid="-1">
          <span class="flex justify-center items-center w-16 font-semibold text-gray-400" id="uq_order">#1</span>
          <div class="flex flex-col grow font-mono">
            <span class="font-bold text-2xl" id="uq_code">4BCD-3FGH</span>
            <span id="uq_phone">087652710082</span>
          </div>
          <button type="button" class="p-4 bg-blue-400 rounded-lg text-white disabled:opacity-25" id="completeBtn">Selesai</button>
        </div>
      </div>
      <input type="text" name="search" id="search" placeholder="Cari No. Pendaftar..." class="m-3 p-2 bg-blue-50 rounded-md grow focus:bg-blue-100 outline-none">
      <div id="users" class="p-2">
        
      </div>
    </div>
  </div>
  <script>
    const addr = "/admin/queue-detail.php?id=<?= $_GET["id"] ?>";
    let inputTime;
    let timeoutId2;
    let cooldown = 0;
    let currUserQueueId = 0;
    let search = query("#search");
    let completeBtn = query("#completeBtn");
    let q_stat = query("#q_status");
    function query(s) {
      return document.querySelector(s);
    }

    function toggleCallBtn(enabled) {
      query("#users").children.forEach((el) => {
        el.disabled = !enabled;
      });
    }

    async function jsonReq(url, body = "", method = "GET") {
      let opt = body.length == 0 ? {method: method} : {body: body, method: method};
      let res = await fetch(url, opt);
      if(res.ok) {
        return await res.json();
      }
      return null;
    }
    getQueueInfo();

    search.oninput = () => {
      clearTimeout(inputTime);
      inputTime = setTimeout(() => {
        getUsersQueue(search.value);
      }, 500);
    };

    q_stat.onclick = () => {
      switch (q_stat.innerText) {
        case "Mulai":
          changeStatus("running");
          break;
          case "Hentikan":
          changeStatus("stopped");
          break;
          case "Lanjutkan":
          changeStatus("running");
          break;
          case "Selesai":
          changeStatus("completed");
          break;
        default:
          break;
      }
    };


    async function getQueueInfo() {
      let data = await jsonReq(addr + "&d=q");
      let cuq = query("#curr_uq");
      let ncuq = query("#no_curr_uq");
      query("#q_title").innerText = data.title;
      query("#q_date").innerText = data.date;
      q_stat.innerText = data.status == null ? "Mulai" : (data.status == "running" ? "Hentikan" : (data.status == "stopped" ? "Lanjutkan" : "Selesai"));
      if(q_stat.innerText == "Selesai") stat.disabled = true;
      if(data.curr_queue == null) {
        ncuq.classList.remove("hidden");
        cuq.classList.add("hidden");
        cuq.classList.remove("flex");
        currUserQueueId = -1;
      } else {
        currUserQueueId = data.curr_queue.id;
        if(!ncuq.classList.contains("hidden"))ncuq.classList.add("hidden");
        cuq.classList.remove("hidden");
        cuq.classList.add("flex");
        query("#uq_order").innerText = "#" + data.curr_queue.queue_order;
        query("#uq_code").innerText = data.curr_queue.code;
        query("#uq_phone").innerText = data.curr_queue.phone_number;
        completeBtn.onclick = () => {completeUser(data.curr_queue.id)};
      }
      cooldown = data.sec_left;
      console.log(cooldown);
      if(cooldown > 0) {
        completeBtn.disabled = true;
        clearTimeout(timeoutId2);
        timeoutId2 = setTimeout(() => {
          toggleCallBtn(true);
          completeBtn.disabled = false;
        }, cooldown * 1000);
      }
      await getUsersQueue(search.value);
    }

    async function getUsersQueue(str = "") {
      let url = addr + "&d=u";
      if(str.trim().length > 0) url += "&s=" + encodeURIComponent(str);
      let users = await jsonReq(url);
      let div = query("#users");
      div.replaceChildren();
      if(users.length == 0) {
        if(str.trim().length == 0) q_stat.innerText = "Selesai";
        return;
      }
      users.forEach((user) => {
        div.innerHTML += `<div class="flex items-center bg-gray-100 p-3 rounded-xl mb-4">
          <span class="flex justify-center items-center w-16 font-semibold text-gray-400">#${user.queue_order}</span>
          <div class="flex flex-col grow font-mono">
            <span class="font-bold text-xl">${user.code}</span>
            <span>${user.phone_number}</span>
          </div>
          <button type="button" class="bg-gray-700 text-white p-2 rounded-lg" ${cooldown > 0 ? "disabled" : ""} onclick="callUser(${user.id})">Panggil</button>
        </div>`
      });
    }

    async function callUser(id) {
      if(currUserQueueId > 0) {
        await completeUser(currUserQueueId, false);
      }
      let body = {
        uq_id: id,
        status: "called"
      };
      let res = await jsonReq(addr, JSON.stringify(body), "POST");
      await getQueueInfo();
    }

    async function completeUser(id, refresh = true) {
      let body = {
        uq_id: id,
        status: "completed"
      };
      let res = await jsonReq(addr, JSON.stringify(body), "POST");
      if(refresh) await getQueueInfo();
    }

    async function changeStatus(stat) {
      let body = {
        status: stat
      };
      q_stat.disabled = true;
      jsonReq(addr, JSON.stringify(body), "PATCH")
      .then(data => {
        q_stat.innerText = data.status == null ? "Mulai" : (data.status == "running" ? "Hentikan" : (data.status == "stopped" ? "Lanjutkan" : "Selesai"));
        if(q_stat.innerText == "Selesai") stat.disabled = true;
        else q_stat.disabled = false;
        // if(data.status == "stopped") {
          // query("#users").add("opacity")
        // }
      });
    }
  </script>
</body>
</html>