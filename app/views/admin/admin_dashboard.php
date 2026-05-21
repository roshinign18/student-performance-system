<?php
session_start();
include('../../../config/database.php');

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$total_students = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM students"));
$total_teachers = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM teachers"));
$total_subjects = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM subjects"));
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Panel</title>

<style>
body{
margin:0;
font-family:'Segoe UI',sans-serif;
background:#f1f5f9;
}

/* SIDEBAR */
.sidebar{
width:220px;
height:100vh;
background:#111827;
color:white;
position:fixed;
padding:20px;
}

.sidebar h2{
margin-bottom:30px;
}

.sidebar a{
display:block;
color:#cbd5e1;
text-decoration:none;
margin:15px 0;
padding:8px;
border-radius:6px;
}

.sidebar a:hover{
background:#1f2937;
color:white;
}

/* MAIN */
.main{
margin-left:240px;
padding:20px;
}

.cards{
display:flex;
gap:20px;
}

.card{
background:white;
padding:20px;
border-radius:10px;
flex:1;
box-shadow:0 4px 10px rgba(0,0,0,0.05);
text-align:center;
}

.card h3{
margin:0;
color:#64748b;
}

.card h1{
margin-top:10px;
color:#111827;
}
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

<h1>Dashboard</h1>

<div class="cards">

<div class="card">
<h3>Students</h3>
<h1><?= $total_students ?></h1>
</div>

<div class="card">
<h3>Teachers</h3>
<h1><?= $total_teachers ?></h1>
</div>

<div class="card">
<h3>Subjects</h3>
<h1><?= $total_subjects ?></h1>
</div>

</div>

</div>


</body>
</html>