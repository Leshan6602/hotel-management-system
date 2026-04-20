<?php
include("db.php");

$search = "";

if(isset($_GET['search'])){
$search = $_GET['search'];

$result = mysqli_query($conn,"
SELECT bookings.*, customers.name, rooms.room_number
FROM bookings
JOIN customers ON bookings.customer_id = customers.id
JOIN rooms ON bookings.room_id = rooms.id
WHERE customers.name LIKE '%$search%'
");

}else{

$result = mysqli_query($conn,"
SELECT bookings.*, customers.name, rooms.room_number
FROM bookings
JOIN customers ON bookings.customer_id = customers.id
JOIN rooms ON bookings.room_id = rooms.id
");

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Bookings</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
padding:20px;
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

.search-box{
margin-bottom:20px;
}

a{
text-decoration:none;
color:blue;
}

</style>

</head>

<body>

<h2>Booking Management</h2>

<a href="add_booking.php">Add Booking</a>

<br><br>

<div class="search-box">

<form method="GET">

<input type="text" name="search" placeholder="Search Customer Name" value="<?php echo $search; ?>">

<button type="submit">Search</button>

</form>

</div>

<table>

<tr>
<th>ID</th>
<th>Customer</th>
<th>Room</th>
<th>Check In</th>
<th>Check Out</th>
<th>Receipt</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['room_number']; ?></td>
<td><?php echo $row['check_in']; ?></td>
<td><?php echo $row['check_out']; ?></td>

<td>
<a href="receipt.php?id=<?php echo $row['id']; ?>">Print</a>
</td>

<td>
<a href="edit_booking.php?id=<?php echo $row['id']; ?>">Edit</a> |
<a href="delete_booking.php?id=<?php echo $row['id']; ?>">Delete</a>
</td>

</tr>

<?php } ?>

</table>

</body>
</html>