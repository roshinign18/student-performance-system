<?php
include('../../../config/database.php');

if(isset($_POST['activate'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $check = mysqli_query($conn,"SELECT * FROM teachers WHERE email='$email'");

    if(mysqli_num_rows($check) == 1){

        mysqli_query($conn,"
        UPDATE teachers 
        SET password='$password', status='active'
        WHERE email='$email'
        ");

        echo "<script>
        alert('Account Activated Successfully! Now login.');
        window.location='login.php';
        </script>";

    } else {
        echo "<script>alert('Email not found');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Activate Account</title>
<style>
body{font-family:Arial;background:#f5f7fb;}
.box{
width:350px;margin:auto;margin-top:100px;
background:white;padding:20px;border-radius:8px;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}
button{background:#28a745;color:white;padding:8px;border:none;border-radius:5px;width:100%;}
</style>
</head>

<body>

<div class="box">
<h3 style="text-align:center;">Activate Teacher Account</h3>

<form method="POST">

<input type="email" name="email" placeholder="Enter Email" required style="width:100%;padding:8px;margin-bottom:10px;">

<input type="password" name="password" placeholder="Set Password" required style="width:100%;padding:8px;margin-bottom:10px;">

<button name="activate">Activate Account</button>

</form>

<p style="text-align:center;margin-top:10px;">
Already activated? <a href="login.php">Login</a>
</p>

</div>

</body>
</html>