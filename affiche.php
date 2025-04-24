<?php
$conn = new mysqli("localhost","root","","event");
$result = $conn->query("SELECT id, username, mail, tel FROM `user`");
echo "<pre>", print_r($result->fetch_all(MYSQLI_ASSOC), true ), "</pre>";
