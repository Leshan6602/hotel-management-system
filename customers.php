<?php
include("db.php");

$result = mysqli_query($conn,"SELECT * FROM customers");
?>

<!DOCTYPE html>
<html>
<head>
<title>Customers</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
}

table{
width:90%;
border-collapse:collapse;
margin:20px;
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

<h2>Customer Management</h2>

<a href="add_customer.php">Add New Customer</a>

<br><br>

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Phone</th>
<th>Email</th>
<th>Address</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['phone']; ?></td>
<td><?php echo $row['email']; ?></td>
<td><?php echo $row['address']; ?></td>

<td>
<a href="edit_customer.php?id=<?php echo $row['id']; ?>">Edit</a> |
<a href="delete_customer.php?id=<?php echo $row['id']; ?>">Delete</a>
</td>

</tr>

<?php } ?>

</table>

</body>
</html>