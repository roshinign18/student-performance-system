<?php
include('../../../config/database.php');

if(isset($_POST['register']))
{
$name = $_POST['name'];
$roll = strtolower(trim($_POST['roll']));
$email = $_POST['email'];
$password = $_POST['password'];
$semester = $_POST['semester'];
$section = $_POST['section'];
$check = mysqli_query($conn,"
SELECT * FROM students 
WHERE roll_number='$roll' 
AND semester='$semester' 
AND section='$section'
");

if(mysqli_num_rows($check)>0)
{
echo "<script>alert('Student already registered with this Roll Number');</script>";
}
else
{
$sql = "INSERT INTO students(name,roll_number,email,password,semester,section)
VALUES('$name','$roll','$email','$password','$semester','$section')";

mysqli_query($conn,$sql);

echo "<script>
alert('Registration Successful');
window.location.href='login.php';
</script>";
}
echo "<script>alert('Registration Successful'); window.location='login.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Student Registration</title>

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
width:400px;
}

</style>

</head>

<body>

<div class="form-box">

<h3 class="text-center mb-4">Student Register</h3>

<form method="POST">

<input type="text" name="name" class="form-control mb-3" placeholder="Name" required>

<input type="text" name="roll" class="form-control mb-3" placeholder="Roll Number" required>

<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<select name="semester" class="form-control mb-3">

<option value="">Select Semester</option>
<option>1</option>
<option>2</option>
<option>3</option>
<option>4</option>
<option>5</option>
<option>6</option>

</select>
<br><br>

Section:
<select name="section" required>
<option value="">Select Section</option>
<option value="A">A</option>
<option value="B">B</option>
<option value="C">C</option>
</select>



<br><br>



<button type="submit" name="register" class="btn btn-success w-100">
Register
</button>
<p class="text-center mt-3">
Already have an account? <a href="login.php">Login</a>
</p>

</form>

</div>

</body>
</html>