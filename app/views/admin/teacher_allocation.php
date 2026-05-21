<?php
include('../../../config/database.php');
?>

<!DOCTYPE html>
<html>
<head>
<title>Teacher Allocation</title>

<style>
body{
font-family:Arial;
background:#f5f7fb;
}

.container{
max-width:1000px;
margin:auto;
padding:20px;
}

h2{
text-align:center;
color:#007bff;
}

.card{
background:white;
padding:15px;
margin-bottom:20px;
border-radius:8px;
box-shadow:0 2px 5px rgba(0,0,0,0.1);
}

table{
width:100%;
border-collapse:collapse;
}

th{
background:#007bff;
color:white;
padding:8px;
}

td{
padding:8px;
border-bottom:1px solid #ddd;
text-align:center;
}

.sem-title{
margin-top:20px;
color:#333;
}
</style>

</head>

<body>

<div class="container">

<h2>📚 Teacher Allocation (Semester Wise)</h2>

<?php

// GET ALL SEMESTERS
$semesters = mysqli_query($conn,"SELECT DISTINCT semester FROM subjects ORDER BY semester");

while($sem = mysqli_fetch_assoc($semesters)){

$semester = $sem['semester'];

echo "<h3 class='sem-title'>Semester $semester</h3>";

echo "<div class='card'>";

echo "<table>";
echo "<tr>
<th>Subject</th>
<th>Teacher</th>
<th>Section</th>
</tr>";

// JOIN EVERYTHING
$query = mysqli_query($conn,"
SELECT subjects.subject_name, teachers.name AS teacher_name, teacher_subjects.section
FROM teacher_subjects
JOIN subjects ON subjects.subject_id = teacher_subjects.subject_id
JOIN teachers ON teachers.teacher_id = teacher_subjects.teacher_id
WHERE subjects.semester = '$semester'
ORDER BY subjects.subject_name
");

while($row = mysqli_fetch_assoc($query)){

echo "<tr>";
echo "<td>".$row['subject_name']."</td>";
echo "<td>".$row['teacher_name']."</td>";
echo "<td>".$row['section']."</td>";
echo "</tr>";

}

echo "</table>";
echo "</div>";
}

?>

</div>

</body>
</html>