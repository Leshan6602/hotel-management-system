<?php
include("db.php");

$result = mysqli_query($conn,"
SELECT payments.*, customers.name
FROM payments
JOIN bookings ON payments.booking_id = bookings.id
JOIN customers ON bookings.customer_id = customers.id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Payments</title>

<style>

table{
width:90%;
border-collapse:collapse;
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

<h2>Payments</h2>

<a href="add_payment.php">Add Payment</a>

<br><br>

<table>

<tr>
<th>ID</th>
<th>Customer</th>
<th>Amount</th>
<th>Payment Date</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['amount']; ?></td>
<td><?php echo $row['payment_date']; ?></td>

</tr>

<?php } ?>

</table>

</body>
</html>