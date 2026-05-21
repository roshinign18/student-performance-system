<?php
session_start();
include('../../../config/database.php');
$search = $_GET['search'] ?? '';

// ALWAYS initialize query
if($search != ''){
    $students = mysqli_query($conn,"
    SELECT * FROM students 
    WHERE name LIKE '%$search%' 
    OR roll_number LIKE '%$search%'
    OR semester LIKE '%$search%'
    OR section LIKE '%$search%'
    ");
} else {
    $students = mysqli_query($conn,"SELECT * FROM students");
}

// DEBUG (optional)
if(!$students){
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Students</title>

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
}

table{width:100%;border-collapse:collapse;}
th{
background:#2563eb;color:white;padding:10px;
}
td{
padding:10px;border-bottom:1px solid #ddd;text-align:center;
}

.search{
padding:8px;width:300px;margin-bottom:10px;
border-radius:6px;border:1px solid #ccc;
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

<h2>Manage Students</h2>

<div class="card">



<form method="GET" style="margin-bottom:15px;">
<input type="text" name="search" placeholder="Search by name or roll"
value="<?= $search ?>"
style="padding:8px;width:250px;border-radius:5px;border:1px solid #ccc;">

<button type="submit" style="
background:#007bff;
color:white;
border:none;
padding:8px 12px;
border-radius:5px;">
Search
</button>
</form>
<table>
<tr>
<th>Name</th>
<th>Roll</th>
<th>Email</th>
<th>Semester</th>
<th>Section</th>
</tr>

<?php while($s = mysqli_fetch_assoc($students)){ ?>
<tr>
<td><?= $s['name'] ?></td>
<td><?= $s['roll_number'] ?></td>
<td><?= $s['email'] ?></td>
<td><?= $s['semester'] ?></td>
<td><?= $s['section'] ?></td>
</tr>
<?php } ?>

</table>

</div>

</div>

</body>
</html>