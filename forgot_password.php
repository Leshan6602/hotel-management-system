<?php
include("db.php");

if(isset($_POST['reset'])){

$username = $_POST['username'];
$new_password = $_POST['password'];

// check password length
if(strlen($new_password) < 6){
    echo "❌ Password must be at least 6 characters";
    exit();
}

// hash password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// update password
mysqli_query($conn,"UPDATE users SET password='$hashed_password' WHERE username='$username'");

echo "✅ Password Reset Successful";

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
</head>

<body>

<h2>Reset Password</h2>

<form method="POST">

Username:<br>
<input type="text" name="username" required><br><br>

New Password:<br>
<input type="password" name="password" required><br><br>

<button type="submit" name="reset">Reset Password</button>

</form>

</body>
</html>