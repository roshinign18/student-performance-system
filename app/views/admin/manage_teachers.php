<?php
session_start();
include('../../../config/database.php');

$msg="";

// ADD TEACHER
if(isset($_POST['add'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
   

    mysqli_query($conn,"INSERT INTO teachers(name,email)
    VALUES('$name','$email')");

    $msg="Teacher Added!";
}

$teachers = mysqli_query($conn,"SELECT * FROM teachers");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Teachers</title>

<style>
body{margin:0;font-family:'Segoe UI';background:#f1f5f9;}

.sidebar{
width:220px;height:100vh;background:#111827;color:white;
position:fixed;padding:20px;
}
.sidebar a{
display:block;color:#cbd5e1;text-decoration:none;
margin:15px 0;padding:8px;border-radius:6px;
}
.sidebar a:hover{background:#1f2937;color:white;}

.main{margin-left:240px;padding:20px;}

.card{
background:white;padding:20px;border-radius:10px;
box-shadow:0 4px 10px rgba(0,0,0,0.05);
margin-bottom:20px;
}

input{
padding:8px;margin:5px;border-radius:6px;border:1px solid #ccc;
}

button{
background:#2563eb;color:white;border:none;
padding:8px 12px;border-radius:6px;
}

table{width:100%;border-collapse:collapse;}
th{background:#2563eb;color:white;padding:10px;}
td{padding:10px;border-bottom:1px solid #ddd;text-align:center;}

.msg{color:green;font-weight:bold;}
</style>
</head>

<body>

<div class="sidebar">
<h2>Admin</h2>
<a href="admin_dashboard.php">Dashboard</a>
<a href="manage_students.php">Students</a>
<a href="manage_teachers.php">Teachers</a>
<a href="manage_subjects.php">Subjects</a>
<a href="assign_subjects.php">Assign</a>
<a href="logout.php">Logout</a>
</div>

<div class="main">

<h2>Manage Teachers</h2>

<div class="card">

<h3>Add Teacher</h3>

<form method="POST">
<input name="name" placeholder="Name" required>
<input name="email" placeholder="Email" required>

<button name="add">Add</button>
</form>

<p class="msg"><?= $msg ?></p>

</div>

<div class="card">

<h3>All Teachers</h3>

<table>
<tr>
<th>Name</th>
<th>Email</th>

</tr>

<?php while($t = mysqli_fetch_assoc($teachers)){ ?>
<tr>
<td><?= $t['name'] ?></td>
<td><?= $t['email'] ?></td>

</tr>
<?php } ?>

</table>

</div>

</div>

</body>
</html>