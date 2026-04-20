<?php
session_start();
include("db.php");

if(isset($_POST['login'])){

    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) > 0){

        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row['password'])){
            $_SESSION['admin'] = $row['username'];
            header("Location: dashboard.php");
            exit();
        }else{
            $error = "Invalid Password";
        }

    }else{
        $error = "User not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Hotel Admin Login</title>

<!-- 🔥 BASE FIX -->
<base href="http://localhost/hotel_admin_system/">

<style>

body{
    margin:0;
    padding:0;
    font-family:Arial;

    background-image: url('images/hotel.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;

    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    position:relative;
}

body::before{
    content:"";
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.5);
}

.login-box{
    position:relative;
    z-index:1;
    background: rgba(255,255,255,0.95);
    padding:30px;
    border-radius:12px;
    width:320px;
    box-shadow:0 0 25px rgba(0,0,0,0.5);
    text-align:center;
}

.logo{
    width:80px;
    margin-bottom:10px;
}

input{
    width:90%;
    padding:10px;
    margin:10px 0;
    border:1px solid #ccc;
    border-radius:5px;
}

button{
    width:100%;
    padding:10px;
    background:#2c3e50;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

button:hover{
    background:#34495e;
}

.error{
    color:red;
    margin-top:10px;
}

.forgot{
    display:block;
    margin-top:10px;
    text-decoration:none;
    color:#2c3e50;
}

</style>
</head>

<body>

<div class="login-box">

<!-- 🔥 LOGO FIXED -->
<img src="images/logo.png" class="logo">

<h2>Hotel Admin Login</h2>

<form method="POST">

<input type="text" name="username" placeholder="Enter Username" required>

<input type="password" name="password" placeholder="Enter Password" required>

<button type="submit" name="login">Login</button>

</form>

<a class="forgot" href="forgot_password.php">Forgot Password?</a>

<?php
if(isset($error)){
    echo "<p class='error'>$error</p>";
}
?>

</div>

</body>
</html>