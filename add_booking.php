<?php
include("db.php");

// FETCH DATA
$customers = mysqli_query($conn,"SELECT * FROM customers");
$rooms = mysqli_query($conn,"SELECT * FROM rooms WHERE status='Available'");

if(isset($_POST['submit'])){

$customer_id = $_POST['customer_id'] ?? '';
$room_id = $_POST['room_id'] ?? '';
$check_in = $_POST['check_in'] ?? '';
$check_out = $_POST['check_out'] ?? '';

// 🔒 VALIDATION
if(empty($customer_id) || empty($room_id) || empty($check_in) || empty($check_out)){
    echo "<script>alert('All fields are required');</script>";
} else {

    // CHECK IF ROOM EXISTS
    $check_room = mysqli_query($conn,"SELECT * FROM rooms WHERE id='$room_id'");
    
    if(mysqli_num_rows($check_room) == 0){
        echo "<script>alert('Invalid room selected');</script>";
    } else {

        // INSERT BOOKING
        $insert = mysqli_query($conn,"INSERT INTO bookings(customer_id,room_id,check_in,check_out)
        VALUES('$customer_id','$room_id','$check_in','$check_out')");

        if($insert){

            // UPDATE ROOM STATUS
            mysqli_query($conn,"UPDATE rooms SET status='Booked' WHERE id='$room_id'");

            // 🔔 ADD NOTIFICATION
            $message = "New booking added for Room ID: $room_id";
            mysqli_query($conn,"INSERT INTO notifications (message) VALUES ('$message')");

            echo "<script>alert('Booking added successfully'); window.location='bookings.php';</script>";

        } else {
            echo "Error: " . mysqli_error($conn);
        }

    }
}

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Booking</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body{
font-family: Arial;
padding:20px;
background:#f4f6f9;
}

form{
background:white;
padding:20px;
border-radius:10px;
max-width:400px;
margin:auto;
box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

select,input,button{
width:100%;
padding:10px;
margin-top:5px;
border-radius:5px;
border:1px solid #ccc;
}

button{
background:#2c3e50;
color:white;
border:none;
cursor:pointer;
}

button:hover{
background:#1abc9c;
}
</style>

</head>

<body>

<h2 style="text-align:center;">Add Booking</h2>

<form method="POST">

<label>Customer:</label>
<select name="customer_id" required>
<option value="">Select Customer</option>

<?php while($c = mysqli_fetch_assoc($customers)){ ?>
<option value="<?php echo $c['id']; ?>">
<?php echo $c['name']; ?>
</option>
<?php } ?>

</select>

<br><br>

<label>Room:</label>
<select name="room_id" required>
<option value="">Select Room</option>

<?php 
if(mysqli_num_rows($rooms) > 0){
while($r = mysqli_fetch_assoc($rooms)){ ?>
<option value="<?php echo $r['id']; ?>">
Room <?php echo $r['room_number']; ?> (<?php echo $r['room_type']; ?>)
</option>
<?php } 
}else{
echo "<option value=''>No available rooms</option>";
}
?>

</select>

<br><br>

<label>Check In:</label>
<input type="date" name="check_in" required>

<br><br>

<label>Check Out:</label>
<input type="date" name="check_out" required>

<br><br>

<button type="submit" name="submit">Book Room</button>

</form>

</body>
</html>