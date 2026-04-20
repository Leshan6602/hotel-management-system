<?php
include("db.php");

$result = mysqli_query($conn,"
SELECT bookings.*, customers.name, rooms.room_number
FROM bookings
JOIN customers ON bookings.customer_id = customers.id
JOIN rooms ON bookings.room_id = rooms.id
ORDER BY bookings.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Booking History Report</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
}

table{
width:90%;
border-collapse:collapse;
background:white;
}

th,td{
padding:10px;
border:1px solid #ccc;
text-align:center;
}

th{
background:#2c3e50;
color:white;
}

</style>

</head>

<body>

<h2>Booking History Report</h2>

<table>

<tr>
<th>ID</th>
<th>Customer</th>
<th>Room</th>
<th>Check In</th>
<th>Check Out</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['room_number']; ?></td>
<td><?php echo $row['check_in']; ?></td>
<td><?php echo $row['check_out']; ?></td>

</tr>

<?php } ?>

</table>

</body>
</html>