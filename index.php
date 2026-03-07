<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Enter School</title>
</head>
<body>
  <script>
    let uuid = localStorage.getItem("uuid");

    if(uuid == null) {
      uuid = `${Date.now().toString(36)}-${Math.random().toString(36).substring(2, 10)}-${Math.random().toString(36).substring(2, 6)}`;
      localStorage.setItem("uuid", uuid);
    }
    let res = getInfo();

    async function getInfo() {
      let res = await fetch("/api/login.php", {
        method: "POST",
        body: `{"uuid": "${uuid}"}`
      });
      if(res.ok) {
        let json = await res.json();
        return json;
      }
      console.log(`code: ${res.status}, msg: ${res.statusText}`);
      return null;
    }
  </script>
</body>
</html>