<?php
include("db.php");

$id = $_GET['id'];

mysqli_query($conn,"DELETE FROM rooms WHERE id=$id");

header("Location: rooms.php");
?>