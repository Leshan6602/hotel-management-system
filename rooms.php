<?php
include("db.php");

$result = mysqli_query($conn,"SELECT * FROM rooms");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Rooms</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
padding:20px;
}

h2{
margin-bottom:10px;
}

.add-btn{
display:inline-block;
padding:8px 15px;
background:#2c3e50;
color:white;
text-decoration:none;
border-radius:4px;
}

.add-btn:hover{
background:#34495e;
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

a{
text-decoration:none;
color:blue;
}

img{
border-radius:5px;
}

</style>

</head>

<body>

<h2>Room Management</h2>

<a class="add-btn" href="add_room.php">Add New Room</a>

<br><br>

<table>

<tr>
<th>ID</th>
<th>Room Number</th>
<th>Room Type</th>
<th>Price</th>
<th>Status</th>
<th>Image</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['room_number']; ?></td>

<td><?php echo $row['room_type']; ?></td>

<td><?php echo $row['price']; ?></td>

<td>
<?php
if($row['status'] == "Available"){
    echo "<span style='color:green; font-weight:bold;'>Available</span>";
}else{
    echo "<span style='color:red; font-weight:bold;'>Booked</span>";
}
?>
</td>

<td>

<?php
if($row['image'] != ""){
?>

<img src="room_images/<?php echo $row['image']; ?>" width="80">

<?php
}else{
echo "No Image";
}
?>

</td>

<td>

<a href="edit_room.php?id=<?php echo $row['id']; ?>">Edit</a> |

<a href="delete_room.php?id=<?php echo $row['id']; ?>">Delete</a>

</td>

</tr>

<?php } ?>

</table>

</body>
</html>