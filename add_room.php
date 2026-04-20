<?php
include("db.php");

if(isset($_POST['submit'])){

$room_number = $_POST['room_number'];
$room_type = $_POST['room_type'];
$price = $_POST['price'];
$status = $_POST['status'];

$image = $_FILES['image']['name'];
$tmp = $_FILES['image']['tmp_name'];

move_uploaded_file($tmp,"room_images/".$image);

mysqli_query($conn,"INSERT INTO rooms(room_number,room_type,price,status,image)
VALUES('$room_number','$room_type','$price','$status','$image')");

header("Location: rooms.php");

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Room</title>
</head>

<body>

<h2>Add Room</h2>

<form method="POST" enctype="multipart/form-data">

Room Number:<br>
<input type="text" name="room_number" required><br><br>

Room Type:<br>
<input type="text" name="room_type" required><br><br>

Price:<br>
<input type="text" name="price" required><br><br>

Status:<br>
<select name="status">
<option value="Available">Available</option>
<option value="Booked">Booked</option>
</select><br><br>

Room Image:<br>
<input type="file" name="image"><br><br>

<button type="submit" name="submit">Save Room</button>

</form>

</body>
</html>
