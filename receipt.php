<?php
include("db.php");

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $query = mysqli_query($conn,"
    SELECT payments.*, 
           customers.name, 
           rooms.room_number,
           bookings.check_in,
           bookings.check_out
    FROM payments
    JOIN bookings ON payments.booking_id = bookings.id
    JOIN customers ON bookings.customer_id = customers.id
    JOIN rooms ON bookings.room_id = rooms.id
    WHERE payments.id = '$id'
    ");

    $row = mysqli_fetch_assoc($query);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Hotel Receipt</title>

<style>

body{
font-family:Arial;
padding:30px;
background:#f4f6f9;
}

/* RECEIPT BOX */
.receipt{
width:400px;
margin:auto;
background:white;
border-radius:10px;
padding:20px;
box-shadow:0 0 10px rgba(0,0,0,0.2);
}

/* HEADER */
.header{
text-align:center;
}

.header img{
width:80px;
margin-bottom:5px;
}

h2{
margin:5px 0;
}

/* LINES */
hr{
margin:10px 0;
}

/* BUTTON */
.print-btn{
padding:10px 20px;
background:#2c3e50;
color:white;
border:none;
border-radius:5px;
cursor:pointer;
}

.print-btn:hover{
background:#1abc9c;
}

/* FOOTER */
.footer{
text-align:center;
font-size:12px;
color:gray;
margin-top:10px;
}

</style>

</head>

<body>

<div class="receipt">

<!-- LOGO + TITLE -->
<div class="header">
    <img src="images/logo.png">
    <h2>Hotel Receipt</h2>
    <small>Premium Stay & Services</small>
</div>

<hr>

<!-- RECEIPT INFO -->
<p><b>Receipt ID:</b> <?php echo $row['id']; ?></p>
<p><b>Date:</b> <?php echo date("Y-m-d"); ?></p>

<hr>

<!-- CUSTOMER INFO -->
<p><b>Customer:</b> <?php echo $row['name']; ?></p>
<p><b>Room Number:</b> <?php echo $row['room_number']; ?></p>

<hr>

<!-- BOOKING INFO -->
<p><b>Check In:</b> <?php echo $row['check_in']; ?></p>
<p><b>Check Out:</b> <?php echo $row['check_out']; ?></p>

<hr>

<!-- PAYMENT INFO -->
<p><b>Amount Paid:</b> Ksh <?php echo $row['amount']; ?></p>
<p><b>Payment Date:</b> <?php echo $row['payment_date']; ?></p>

<hr>

<p style="text-align:center;">Thank you for your stay!</p>

<br>

<!-- PRINT BUTTON -->
<div style="text-align:center;">
<button onclick="window.print()" class="print-btn">
🖨 Print / Save PDF
</button>
</div>

<!-- FOOTER -->
<div class="footer">
Powered by Hotel Management System
</div>

</div>

</body>
</html>