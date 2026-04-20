<?php
include("db.php");

if(isset($_POST['submit'])){

$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];

mysqli_query($conn,"INSERT INTO customers(name,phone,email,address)
VALUES('$name','$phone','$email','$address')");

header("Location: customers.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Customer</title>
</head>

<body>

<h2>Add Customer</h2>

<form method="POST">

Name:<br>
<input type="text" name="name"><br><br>

Phone:<br>
<input type="text" name="phone"><br><br>

Email:<br>
<input type="text" name="email"><br><br>

Address:<br>
<textarea name="address"></textarea>

<br><br>

<button type="submit" name="submit">Add Customer</button>

</form>

</body>
</html>