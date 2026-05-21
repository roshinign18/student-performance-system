<?php
session_start();
ob_start();

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    if($username == 'admin' && $password == 'admin123'){

        $_SESSION['admin'] = true;

       header("Location: http://localhost/student-performance-system/app/views/admin/admin_dashboard.php");
exit;

    } else {
        $error = "Invalid Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<style>
body{
    background:linear-gradient(to right,#667eea,#764ba2);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}
.form-box{
    background:white;
    padding:40px;
    border-radius:10px;
    width:350px;
}
</style>

</head>

<body>

<div class="form-box">

<h3 class="text-center mb-4">Admin Login</h3>

<?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

<form method="POST" action="login.php">

<input type="text" name="username" class="form-control mb-3" placeholder="Username" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<button type="submit" name="login" class="btn btn-dark w-100">
Login
</button>

</form>

</div>

</body>
</html>