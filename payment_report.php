<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
}

include("db.php");

// TOTAL INCOME
$total = 0;
$result = mysqli_query($conn,"SELECT * FROM payments");

while($row = mysqli_fetch_assoc($result)){
    $total += $row['amount'];
}

// DAILY INCOME
$daily_data = [];
$daily_query = mysqli_query($conn,"
SELECT DATE(payment_date) as day, SUM(amount) as total 
FROM payments 
GROUP BY day
");

while($row = mysqli_fetch_assoc($daily_query)){
    $daily_data[] = $row;
}

// MONTHLY INCOME
$monthly_data = [];
$monthly_query = mysqli_query($conn,"
SELECT DATE_FORMAT(payment_date, '%Y-%m') as month, SUM(amount) as total 
FROM payments 
GROUP BY month
");

while($row = mysqli_fetch_assoc($monthly_query)){
    $monthly_data[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment Report</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

body{
margin:0;
font-family:Arial;
background:#f4f6f9;
}

/* SIDEBAR */
.sidebar{
position:fixed;
top:0;
left:0;
width:220px;
height:100vh;
background:#2c3e50;
color:white;
padding-top:20px;
}

.sidebar a{
color:white;
display:block;
padding:12px 15px;
text-decoration:none;
}

.sidebar a:hover{
background:#1abc9c;
}

/* MAIN */
.main-content{
margin-left:220px;
padding:20px;
}

/* CARD */
.card{
background:white;
padding:20px;
margin-bottom:20px;
width:300px;
box-shadow:0 2px 5px rgba(0,0,0,0.1);
border-radius:10px;
}

/* TABLE */
table{
width:100%;
border-collapse:collapse;
background:white;
}

th,td{
padding:10px;
border:1px solid #ddd;
text-align:center;
}

th{
background:#2c3e50;
color:white;
}

/* CHART BOX */
.chart-box{
background:white;
padding:20px;
margin-top:30px;
border-radius:10px;
box-shadow:0 2px 5px rgba(0,0,0,0.1);
max-width:900px;
}

canvas{
width:100% !important;
height:300px !important;
}

</style>

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

<div class="text-center">
<img src="images/logo.png" width="70">
<h5>Hotel Admin</h5>
</div>

<hr>

<a href="dashboard.php">🏠 Dashboard</a>
<a href="rooms.php">🛏 Manage Rooms</a>
<a href="bookings.php">📅 Bookings</a>
<a href="customers.php">👥 Customers</a>
<a href="payment_report.php">💰 Payments</a>
<a href="settings.php">⚙ Settings</a>
<a href="logout.php">🚪 Logout</a>

</div>

<!-- MAIN -->
<div class="main-content">

<h2>💰 Payment Report</h2>

<div class="card">
<h4>Total Income</h4>
<h2>KES <?php echo $total; ?></h2>
</div>

<table>

<tr>
<th>ID</th>
<th>Booking ID</th>
<th>Amount</th>
<th>Date</th>
<th>Receipt</th>
</tr>

<?php
$result = mysqli_query($conn,"SELECT * FROM payments");

while($row = mysqli_fetch_assoc($result)){
?>

<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['booking_id']; ?></td>
<td><?php echo $row['amount']; ?></td>
<td><?php echo $row['payment_date']; ?></td>
<td>
<a href="receipt.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
Download
</a>
</td>
</tr>

<?php } ?>

</table>

<!-- DAILY CHART -->
<div class="chart-box">
<h5>📊 Daily Income</h5>
<canvas id="dailyChart"></canvas>
</div>

<!-- MONTHLY CHART -->
<div class="chart-box">
<h5>📈 Monthly Income</h5>
<canvas id="monthlyChart"></canvas>
</div>

</div>

<script>

// DAILY DATA
const dailyLabels = [
<?php foreach($daily_data as $d){ echo "'".$d['day']."',"; } ?>
];

const dailyValues = [
<?php foreach($daily_data as $d){ echo $d['total'].","; } ?>
];

new Chart(document.getElementById('dailyChart'), {
type: 'bar',
data: {
labels: dailyLabels,
datasets: [{
label: 'Daily Income (KES)',
data: dailyValues,
borderWidth: 1
}]
},
options: {
responsive: true,
maintainAspectRatio: false
}
});

// MONTHLY DATA
const monthlyLabels = [
<?php foreach($monthly_data as $m){ echo "'".$m['month']."',"; } ?>
];

const monthlyValues = [
<?php foreach($monthly_data as $m){ echo $m['total'].","; } ?>
];

new Chart(document.getElementById('monthlyChart'), {
type: 'line',
data: {
labels: monthlyLabels,
datasets: [{
label: 'Monthly Income (KES)',
data: monthlyValues,
borderWidth: 2,
tension: 0.3
}]
},
options: {
responsive: true,
maintainAspectRatio: false
}
});

</script>

</body>
</html>