<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

include("db.php");

// FETCH ADMIN
$admin = $_SESSION['admin'];
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE username='$admin'");
$user_data = mysqli_fetch_assoc($user_query);

// COUNTS
$rooms_total = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM rooms"))['total'];
$customers_total = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM customers"))['total'];
$bookings_total = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM bookings"))['total'];
$payments_total = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM payments"))['total'];
$revenue_total = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(amount) as total FROM payments"))['total'] ?? 0;

// CHART DATA
$chart_data = [];
$query = mysqli_query($conn,"
SELECT DATE_FORMAT(payment_date, '%Y-%m') as month, SUM(amount) as total 
FROM payments 
GROUP BY month
ORDER BY month ASC
");

while($row = mysqli_fetch_assoc($query)){
    $chart_data[] = $row;
}

// 🔔 NOTIFICATIONS
$notif_query = mysqli_query($conn, "SELECT * FROM notifications ORDER BY id DESC LIMIT 5");
$unread_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM notifications"));
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
margin:0;
font-family:'Segoe UI', sans-serif;
background:#eef2f7;
}

/* SIDEBAR */
.sidebar{
position:fixed;
top:0;
left:-230px;
width:230px;
height:100vh;
background:#2c3e50;
color:white;
padding-top:20px;
transition:0.3s;
z-index:999;
}

.sidebar.active{ left:0; }

.sidebar a{
color:white;
display:block;
padding:12px 20px;
text-decoration:none;
}

.sidebar a:hover{ background:#1abc9c; }

/* PROFILE IMAGE */
.profile-img{
width:70px;
height:70px;
border-radius:50%;
object-fit:cover;
margin-bottom:10px;
}

/* MAIN */
.main-content{
margin-left:0;
padding:15px;
transition:0.3s;
}

.main-content.shift{ margin-left:230px; }

/* TOPBAR */
.topbar{
display:flex;
justify-content:space-between;
align-items:center;
background:white;
padding:12px;
border-radius:10px;
margin-bottom:15px;
}

/* PROFILE */
.profile{
display:flex;
align-items:center;
gap:10px;
position:relative;
cursor:pointer;
}

.profile img{
width:35px;
height:35px;
border-radius:50%;
}

/* DROPDOWN */
.dropdown-menu-custom{
position:absolute;
top:45px;
right:0;
background:white;
display:none;
border-radius:8px;
box-shadow:0 5px 10px rgba(0,0,0,0.1);
}

.dropdown-menu-custom a{
display:block;
padding:10px;
color:#333;
text-decoration:none;
}

/* CARDS */
.card{
border:none;
border-radius:12px;
box-shadow:0 4px 12px rgba(0,0,0,0.1);
padding:12px;
}

.card h5{ font-size:13px; }
.card h2{ font-size:18px; }

/* COLORS */
.bg-primary{background:#3498db !important;}
.bg-success{background:#2ecc71 !important;}
.bg-warning{background:#f1c40f !important;}
.bg-danger{background:#e74c3c !important;}
.bg-dark{background:#2c3e50 !important;}

/* CHART */
.chart-box{
background:white;
padding:15px;
margin-top:15px;
border-radius:12px;
min-height:250px;
}

#revenueChart{
width:100% !important;
height:250px !important;
}

/* NOTIFICATION BOX */
#notifBox{
display:none;
position:absolute;
top:70px;
right:20px;
background:white;
width:260px;
box-shadow:0 5px 10px rgba(0,0,0,0.1);
border-radius:10px;
z-index:999;
padding:10px;
max-height:300px;
overflow-y:auto;
}

/* DESKTOP */
@media(min-width:768px){
.sidebar{ left:0; }
.main-content{ margin-left:230px; }
}

/* MOBILE */
@media(max-width:768px){

.topbar{
flex-direction:column;
align-items:flex-start;
gap:8px;
}

.profile span{ display:none; }

}
</style>

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar text-center">

<img src="uploads/<?php echo $user_data['profile_image']; ?>" 
     class="profile-img"
     onerror="this.src='uploads/default.png'">

<h5>Hotel Admin</h5>

<hr>

<a href="dashboard.php">Dashboard</a>
<a href="rooms.php">Rooms</a>
<a href="bookings.php">Bookings</a>
<a href="customers.php">Customers</a>
<a href="payment_report.php">Payments</a>
<a href="settings.php">Settings</a>
<a href="logout.php">Logout</a>

</div>

<div class="main-content">

<div class="topbar">

<button onclick="toggleSidebar()" class="btn btn-dark btn-sm">☰</button>

<h5>Dashboard</h5>

<div style="display:flex; align-items:center; gap:15px; position:relative;">

<!-- 🔔 BELL -->
<div style="position:relative; cursor:pointer;" onclick="toggleNotifications()">

<span style="font-size:20px;">🔔</span>

<?php if($unread_count > 0){ ?>
<span style="
position:absolute;
top:-5px;
right:-10px;
background:red;
color:white;
border-radius:50%;
padding:3px 7px;
font-size:12px;">
<?php echo $unread_count; ?>
</span>
<?php } ?>

</div>

<!-- PROFILE -->
<div class="profile" onclick="toggleMenu()">
<img src="uploads/<?php echo $user_data['profile_image']; ?>" 
     onerror="this.src='uploads/default.png'">

<span><?php echo $_SESSION['admin']; ?></span>

<div class="dropdown-menu-custom" id="menu">
<a href="settings.php">Settings</a>
<a href="logout.php">Logout</a>
</div>
</div>

</div>

</div>

<!-- 🔔 NOTIFICATIONS -->
<div id="notifBox">

<h6>Notifications</h6>
<hr>

<?php while($n = mysqli_fetch_assoc($notif_query)){ ?>
<p style="font-size:13px;"><?php echo $n['message']; ?></p>
<hr>
<?php } ?>

</div>

<div class="row g-2">

<div class="col-6 col-md-3">
<div class="card bg-primary text-white text-center">
<h5>Rooms</h5>
<h2><?php echo $rooms_total; ?></h2>
</div>
</div>

<div class="col-6 col-md-3">
<div class="card bg-success text-white text-center">
<h5>Customers</h5>
<h2><?php echo $customers_total; ?></h2>
</div>
</div>

<div class="col-6 col-md-3">
<div class="card bg-warning text-center">
<h5>Bookings</h5>
<h2><?php echo $bookings_total; ?></h2>
</div>
</div>

<div class="col-6 col-md-3">
<div class="card bg-danger text-white text-center">
<h5>Payments</h5>
<h2><?php echo $payments_total; ?></h2>
</div>
</div>

<div class="col-12">
<div class="card bg-dark text-white text-center">
<h5>Total Revenue</h5>
<h2>Ksh <?php echo $revenue_total; ?></h2>
</div>
</div>

</div>

<div class="chart-box">
<h5>Monthly Revenue</h5>
<canvas id="revenueChart"></canvas>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

function toggleSidebar(){
document.querySelector(".sidebar").classList.toggle("active");
document.querySelector(".main-content").classList.toggle("shift");
}

function toggleMenu(){
var menu = document.getElementById("menu");
menu.style.display = (menu.style.display === "block") ? "none" : "block";
}

// 🔔 TOGGLE
function toggleNotifications(){
var box = document.getElementById("notifBox");
box.style.display = (box.style.display === "block") ? "none" : "block";
}

// CHART FIXED
const ctx = document.getElementById('revenueChart').getContext('2d');

new Chart(ctx,{
type:'line',
data:{
labels: <?php echo json_encode(array_column($chart_data, 'month')); ?>,
datasets:[{
label:'Revenue',
data: <?php echo json_encode(array_column($chart_data, 'total')); ?>,
borderWidth:2,
fill:true
}]
},
options:{
responsive:true,
maintainAspectRatio:false
}
});

</script>

</body>
</html>