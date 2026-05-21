<?php
session_start();
include('../../../config/database.php');

$msg = "";

// ASSIGN
if(isset($_POST['assign'])){
    $teacher = $_POST['teacher'];
    $subject = $_POST['subject'];

    $check = mysqli_query($conn,"
    SELECT * FROM teacher_subjects 
    WHERE teacher_id='$teacher' AND subject_id='$subject'
    ");

    if(mysqli_num_rows($check)>0){
        $msg = "Already assigned!";
    } else {
        mysqli_query($conn,"
        INSERT INTO teacher_subjects(teacher_id,subject_id)
        VALUES('$teacher','$subject')
        ");
        $msg = "Assigned successfully!";
    }
}

// DELETE
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn,"DELETE FROM teacher_subjects WHERE id='$id'");
}

$teachers = mysqli_query($conn,"SELECT * FROM teachers");
$subjects = mysqli_query($conn,"SELECT * FROM subjects");

$assigned = mysqli_query($conn,"
SELECT ts.id, t.name AS teacher, s.subject_name, s.semester
FROM teacher_subjects ts
JOIN teachers t ON t.teacher_id = ts.teacher_id
JOIN subjects s ON s.subject_id = ts.subject_id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Assign Subjects</title>

<style>
body{font-family:'Segoe UI';background:#f1f5f9;margin:0;}
.container{margin-left:240px;padding:20px;}
.card{
background:white;
padding:20px;
border-radius:10px;
margin-bottom:20px;
box-shadow:0 4px 10px rgba(0,0,0,0.05);
}
select,button{
padding:8px;
margin:5px;
border-radius:6px;
border:1px solid #ccc;
}
button{
background:#2563eb;
color:white;
border:none;
}
table{
width:100%;
border-collapse:collapse;
}
th{
background:#2563eb;
color:white;
padding:10px;
}
td{
padding:10px;
border-bottom:1px solid #ddd;
text-align:center;
}
.delete{
background:#dc2626;
padding:5px 10px;
color:white;
text-decoration:none;
border-radius:5px;
}
.msg{
color:green;
font-weight:bold;
}
</style>
</head>

<body>

<div class="container">

<h2>Assign Subjects</h2>

<div class="card">

<form method="POST">

<select name="teacher" required>
<option value="">Select Teacher</option>
<?php while($t = mysqli_fetch_assoc($teachers)){ ?>
<option value="<?= $t['teacher_id'] ?>"><?= $t['name'] ?></option>
<?php } ?>
</select>

<select name="subject" required>
<option value="">Select Subject</option>
<?php while($s = mysqli_fetch_assoc($subjects)){ ?>
<option value="<?= $s['subject_id'] ?>"><?= $s['subject_name'] ?></option>
<?php } ?>
</select>

<button name="assign">Assign</button>

</form>

<p class="msg"><?= $msg ?></p>

</div>

<div class="card">

<h3>Assigned Subjects</h3>

<table>
<tr>
<th>Teacher</th>
<th>Subject</th>
<th>Semester</th>
<th>Action</th>
</tr>

<?php while($a = mysqli_fetch_assoc($assigned)){ ?>
<tr>
<td><?= $a['teacher'] ?></td>
<td><?= $a['subject_name'] ?></td>
<td><?= $a['semester'] ?></td>
<td>
<a href="?delete=<?= $a['id'] ?>" class="delete">Remove</a>
</td>
</tr>
<?php } ?>

</table>

</div>

</div>

</body>
</html>