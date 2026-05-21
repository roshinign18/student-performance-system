<?php
include('../../../config/database.php');

// FETCH STUDENTS
$students = mysqli_query($conn,"SELECT * FROM students ORDER BY semester, section, name");
?>

<!DOCTYPE html>
<html>
<head>
<title>View Students</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<style>
body{
    background:#f5f7fb;
    font-family:Arial;
}
.container{
    max-width:1100px;
    margin:auto;
    padding:20px;
}
th{
    background:#007bff;
    color:white;
    text-align:center;
}
td{
    text-align:center;
}
</style>

</head>

<body>

<div class="container">

<div class="d-flex justify-content-between mb-3">
<h2>All Students</h2>
<a href="admin_dashboard.php" class="btn btn-secondary">Back</a>
</div>

<table class="table table-bordered">

<tr>
<th>Name</th>
<th>Roll Number</th>
<th>Email</th>
<th>Semester</th>
<th>Section</th>
</tr>

<?php while($s = mysqli_fetch_assoc($students)): ?>

<tr>
<td><?= $s['name'] ?></td>
<td><?= $s['roll_number'] ?></td>
<td><?= $s['email'] ?></td>
<td><?= $s['semester'] ?></td>
<td><?= $s['section'] ?></td>
</tr>

<?php endwhile; ?>

</table>

</div>

</body>
</html>