<?php
session_start();
include('../../../config/database.php');

if(isset($_POST['login']))
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM students 
              WHERE email='$email' AND password='$password'";

    $result = mysqli_query($conn,$query);

    if(mysqli_num_rows($result)>0)
    {
        $row = mysqli_fetch_assoc($result);

        $_SESSION['student_id'] = $row['student_id'];
        $_SESSION['student_name'] = $row['name'];

        header("Location: student_dashboard.php");
        exit();
    }
    else
    {
        echo "<script>alert('Invalid Email or Password');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Student Login</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<style>

body{
background:linear-gradient(to right,#43cea2,#185a9d);
height:100vh;
display:flex;
justify-content:center;
align-items:center;
}

.login-box{
background:white;
padding:40px;
border-radius:10px;
width:350px;
}

</style>

</head>

<body>

<div class="login-box">

<h3 class="text-center mb-4">Student Login</h3>

<form method="POST">

<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<button type="submit" name="login" class="btn btn-primary w-100">
Login
</button>
<p style="text-align:center;margin-top:10px;">
<a href="forgot_password.php">Forgot Password?</a>
</p>

</form>

</div>

</body>
</html>