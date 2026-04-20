<?php
session_start();
include("db.php");

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$admin = $_SESSION['admin'];

// FETCH CURRENT USER
$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$admin'");
$data = mysqli_fetch_assoc($query);

if(isset($_POST['update'])){

    $new_username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_password = $_POST['password'];

    // ✅ VALIDATE PASSWORD
    if(strlen($new_password) < 6){
        $error = "❌ Password must be at least 6 characters";
    } else {

        // HASH PASSWORD
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // DEFAULT: keep old image
        $image_name = $data['profile_image'];

        // ✅ IMAGE UPLOAD
        if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){

            $image_name = time() . "_" . $_FILES['image']['name'];
            $target = "uploads/" . $image_name;

            move_uploaded_file($_FILES['image']['tmp_name'], $target);
        }

        // ✅ UPDATE DATABASE
        mysqli_query($conn, "UPDATE users SET 
            username='$new_username', 
            password='$hashed_password',
            profile_image='$image_name'
            WHERE username='$admin'
        ");

        // UPDATE SESSION
        $_SESSION['admin'] = $new_username;

        $success = "✅ Profile updated successfully";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Settings</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
background:#eef2f7;
font-family:Segoe UI;
}

.box{
max-width:420px;
margin:60px auto;
background:white;
padding:30px;
border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
text-align:center;
}

img{
width:100px;
height:100px;
border-radius:50%;
object-fit:cover;
margin-bottom:15px;
}

input{
margin-bottom:12px;
}
</style>

</head>

<body>

<div class="box">

<h4>⚙ Update Profile</h4>

<!-- PROFILE IMAGE -->
<img src="uploads/<?php echo $data['profile_image']; ?>" 
     onerror="this.src='uploads/default.png'">

<form method="POST" enctype="multipart/form-data">

<input type="text" name="username" class="form-control" 
       value="<?php echo $data['username']; ?>" required>

<input type="password" name="password" class="form-control" 
       placeholder="New Password (min 6 characters)" required>

<input type="file" name="image" class="form-control">

<button name="update" class="btn btn-dark w-100 mt-2">Update Profile</button>

</form>

<?php 
if(isset($error)){
    echo "<p class='text-danger mt-3'>$error</p>";
}
if(isset($success)){
    echo "<p class='text-success mt-3'>$success</p>";
}
?>

</div>

</body>
</html>