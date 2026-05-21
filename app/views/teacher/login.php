<?php
session_start();

include('../../../config/database.php');

if(isset($_POST['login']))
{
$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT * FROM teachers 
WHERE email='$email' 
AND password='$password'
AND status='active'";
$result = mysqli_query($conn,$query);

if(mysqli_num_rows($result) == 1)
{
    $teacher = mysqli_fetch_assoc($result);

    $_SESSION['teacher_id'] = $teacher['teacher_id'];

    header("Location: /student-performance-system/app/views/teacher/select_class.php");
    exit();
}
else
{
echo "<script>alert('Invalid login OR account not activated');</script>";
}
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Teacher Login</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<style>

body{
background:linear-gradient(to right,#ff9966,#ff5e62);
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

<h3 class="text-center mb-4">Teacher Login</h3>

<form method="POST">

<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<button type="submit" name="login" class="btn btn-dark w-100">
Login
</button>
<p style="text-align:center;margin-top:10px;">
First time user? <a href="activate_teacher.php">Activate Account</a>
</p>

</form>

</div>

</body>
</html>