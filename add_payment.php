<?php
include("db.php");

// FETCH BOOKINGS FOR DROPDOWN
$bookings = mysqli_query($conn,"
SELECT bookings.id, customers.name, rooms.room_number
FROM bookings
JOIN customers ON bookings.customer_id = customers.id
JOIN rooms ON bookings.room_id = rooms.id
");

// INSERT PAYMENT
if(isset($_POST['submit'])){
    $booking_id = $_POST['booking_id'];
    $amount = $_POST['amount'];
    $date = $_POST['payment_date'];

    mysqli_query($conn,"
    INSERT INTO payments (booking_id, amount, payment_date)
    VALUES ('$booking_id', '$amount', '$date')
    ");

    echo "<script>alert('Payment Added Successfully'); window.location='payment_report.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Payment</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
background:#f4f6f9;
font-family:Arial;
}

.container{
margin-top:50px;
max-width:500px;
background:white;
padding:25px;
border-radius:10px;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}
</style>

</head>

<body>

<div class="container">

<h3 class="mb-3">💰 Add Payment</h3>

<form method="POST">

<label>Select Booking</label>
<select name="booking_id" class="form-control" required>
<option value="">-- Select Booking --</option>

<?php while($b = mysqli_fetch_assoc($bookings)){ ?>
<option value="<?php echo $b['id']; ?>">
Booking #<?php echo $b['id']; ?> - <?php echo $b['name']; ?> (Room <?php echo $b['room_number']; ?>)
</option>
<?php } ?>

</select>

<br>

<label>Amount (KES)</label>
<input type="number" name="amount" class="form-control" required>

<br>

<label>Payment Date</label>
<input type="date" name="payment_date" class="form-control" required>

<br>

<button type="submit" name="submit" class="btn btn-success w-100">
Add Payment
</button>

</form>

</div>

</body>
</html>