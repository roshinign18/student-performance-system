<?php
include('../../../config/database.php');

if(isset($_POST['add_teacher'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists
    $check = mysqli_query($conn,"SELECT * FROM teachers WHERE email='$email'");

    if(mysqli_num_rows($check)>0){
        $msg = "<div class='alert alert-danger'>Teacher already exists!</div>";
    } else {

        mysqli_query($conn,"INSERT INTO teachers(name,email,status)
        VALUES('$name','$email','$inactive')");

        $msg = "<div class='alert alert-success'>Teacher added successfully!</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Teacher</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<style>
body{
    background:linear-gradient(to right,#36d1dc,#5b86e5);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}
.form-box{
    background:white;
    padding:40px;
    border-radius:10px;
    width:400px;
}
</style>

</head>

<body>

<div class="form-box">

<h3 class="text-center mb-4">Add Teacher</h3>

<?php if(isset($msg)) echo $msg; ?>

<form method="POST">

<input type="text" name="name" class="form-control mb-3" placeholder="Teacher Name" required>

<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<button type="submit" name="add_teacher" class="btn btn-primary w-100">
Add Teacher
</button>

<br><br>

<a href="admin_dashboard.php" class="btn btn-secondary w-100">Back</a>

</form>

</div>

</body>
</html>