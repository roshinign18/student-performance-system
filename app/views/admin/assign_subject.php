<?php
include('../../../config/database.php');

// ASSIGN SUBJECT
if(isset($_POST['assign'])){

    $teacher_id = $_POST['teacher_id'];
    $subject_id = $_POST['subject_id'];

    // Prevent duplicate assignment
    $check = mysqli_query($conn,"SELECT * FROM teacher_subjects 
    WHERE teacher_id='$teacher_id' AND subject_id='$subject_id'");

    if(mysqli_num_rows($check)>0){
        $msg = "<div class='alert alert-danger'>Already assigned!</div>";
    } else {

        mysqli_query($conn,"INSERT INTO teacher_subjects(teacher_id,subject_id)
        VALUES('$teacher_id','$subject_id')");

        $msg = "<div class='alert alert-success'>Subject assigned successfully!</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Assign Subject</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<style>
body{
    background:linear-gradient(to right,#43cea2,#185a9d);
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

<h3 class="text-center mb-4">Assign Subject to Teacher</h3>

<?php if(isset($msg)) echo $msg; ?>

<form method="POST">

<!-- TEACHER DROPDOWN -->
<select name="teacher_id" class="form-control mb-3" required>
<option value="">Select Teacher</option>

<?php
$teachers = mysqli_query($conn,"SELECT * FROM teachers");

while($t = mysqli_fetch_assoc($teachers)){
    echo "<option value='{$t['teacher_id']}'>{$t['name']}</option>";
}
?>

</select>

<!-- SUBJECT DROPDOWN -->
<select name="subject_id" class="form-control mb-3" required>
<option value="">Select Subject</option>

<?php
$subjects = mysqli_query($conn,"SELECT * FROM subjects");

while($s = mysqli_fetch_assoc($subjects)){
    echo "<option value='{$s['subject_id']}'>
    {$s['subject_name']} (Sem {$s['semester']})
    </option>";
}
?>

</select>

<button type="submit" name="assign" class="btn btn-warning w-100">
Assign Subject
</button>

<br><br>

<a href="admin_dashboard.php" class="btn btn-secondary w-100">Back</a>

</form>

</div>

</body>
</html>