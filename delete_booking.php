<?php
include("db.php");

$id = $_GET['id'];

$get = mysqli_query($conn,"SELECT * FROM bookings WHERE id='$id'");
$data = mysqli_fetch_assoc($get);

$room_id = $data['room_id'];

mysqli_query($conn,"DELETE FROM payments WHERE booking_id='$id'");

mysqli_query($conn,"DELETE FROM bookings WHERE id='$id'");

mysqli_query($conn,"UPDATE rooms SET status='Available' WHERE id='$room_id'");

header("Location: bookings.php");

?>