<?php
include("db.php");

$id = $_GET['id'];

// fetch room data
$result = mysqli_query($conn,"SELECT * FROM rooms WHERE id=$id");
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){

$room_number = $_POST['room_number'];
$room_type = $_POST['room_type'];
$price = $_POST['price'];
$status = $_POST['status'];

$image = $_FILES['image']['name'];

// check if new image is uploaded
if($image != ""){

$tmp = $_FILES['image']['tmp_name'];
move_uploaded_file($tmp,"room_images/".$image);

// update with new image
mysqli_query($conn,"UPDATE rooms SET 
room_number='$room_number',
room_type='$room_type',
price='$price',
status='$status',
image='$image'
WHERE id=$id");

}else{

// update without changing image
mysqli_query($conn,"UPDATE rooms SET 
room_number='$room_number',
room_type='$room_type',
price='$price',
status='$status'
WHERE id=$id");

}

echo "Room Updated Successfully";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Room</title>
</head>

<body>

<h2>Edit Room</h2>

<form method="POST" enctype="multipart/form-data">

Room Number:<br>
<input type="text" name="room_number" value="<?php echo $row['room_number']; ?>"><br><br>

Room Type:<br>
<input type="text" name="room_type" value="<?php echo $row['room_type']; ?>"><br><br>

Price:<br>
<input type="text" name="price" value="<?php echo $row['price']; ?>"><br><br>

Status:<br>
<select name="status">
<option value="Available" <?php if($row['status']=="Available") echo "selected"; ?>>Available</option>
<option value="Booked" <?php if($row['status']=="Booked") echo "selected"; ?>>Booked</option>
</select><br><br>

Current Image:<br>
<?php
if($row['image'] != ""){
echo "<img src='room_images/".$row['image']."' width='100'><br><br>";
}else{
echo "No Image<br><br>";
}
?>

Upload New Image:<br>
<input type="file" name="image"><br><br>

<button type="submit" name="update">Update Room</button>

</form>

</body>
</html>