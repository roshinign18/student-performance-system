<?php
session_start();
include('../../../config/database.php');

// GET DATA
$selected_semester = $_GET['semester'] ?? '';
$selected_section = $_GET['section'] ?? '';
$selected_subject = $_POST['subject_id'] ?? '';
$teacher_id = $_SESSION['teacher_id'] ?? '';
$view_mode = isset($_POST['view_marks']);

// ================= SAVE MARKS =================
if(isset($_POST['update_marks'])){

    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];

    // allow empty
    $test1 = ($_POST['test1'] === '') ? NULL : (int)$_POST['test1'];
    $midterm = ($_POST['midterm'] === '') ? NULL : (int)$_POST['midterm'];
    $test2 = ($_POST['test2'] === '') ? NULL : (int)$_POST['test2'];
    $final_exam = ($_POST['final_exam'] === '') ? NULL : (int)$_POST['final_exam'];

    // VALIDATION
    if(($test1>20) || ($midterm>30) || ($test2>50) || ($final_exam>80)){
        echo "<p style='color:red;text-align:center;font-weight:bold;'>Marks exceed allowed limit!</p>";
    } else {

        // calculate only if ALL entered
        if($test1 !== NULL && $midterm !== NULL && $test2 !== NULL && $final_exam !== NULL){
            $total = $test1 + $midterm + $test2 + $final_exam;
            $percentage = ($total/180)*100;
            $status = ($percentage >= 35) ? "Pass" : "Fail";
        } else {
            $percentage = NULL;
            $status = NULL;
        }

        // CHECK EXISTING
        $check = mysqli_query($conn,"SELECT * FROM marks 
        WHERE student_id='$student_id' AND subject_id='$subject_id'");

        if(mysqli_num_rows($check)>0){
            mysqli_query($conn,"UPDATE marks SET
            test1=".($test1===NULL?"NULL":"'$test1'").",
            midterm=".($midterm===NULL?"NULL":"'$midterm'").",
            test2=".($test2===NULL?"NULL":"'$test2'").",
            final_exam=".($final_exam===NULL?"NULL":"'$final_exam'").",
            percentage=".($percentage===NULL?"NULL":"'$percentage'").",
            status=".($status===NULL?"NULL":"'$status'")."
            WHERE student_id='$student_id' AND subject_id='$subject_id'");
        } else {
            mysqli_query($conn,"INSERT INTO marks
            (student_id,subject_id,test1,midterm,test2,final_exam,percentage,status)
            VALUES
            ('$student_id','$subject_id',
            ".($test1===NULL?"NULL":"'$test1'").",
            ".($midterm===NULL?"NULL":"'$midterm'").",
            ".($test2===NULL?"NULL":"'$test2'").",
            ".($final_exam===NULL?"NULL":"'$final_exam'").",
            ".($percentage===NULL?"NULL":"'$percentage'").",
            ".($status===NULL?"NULL":"'$status'").")");
        }
    }
}

// ================= SAVE NOTE =================
if(isset($_POST['save_note'])){
    $student_id = $_POST['student_id'];
    $semester = $_POST['semester'];
    $note = mysqli_real_escape_string($conn,$_POST['note']);

    $subject_id = $_POST['subject_id'];

mysqli_query($conn,"INSERT INTO mentoring(student_id,semester,notes,teacher_id,subject_id)
VALUES('$student_id','$semester','$note','$teacher_id','$subject_id')");
}

// ================= LOAD STUDENTS =================
$students_result = null;

if($selected_subject != ''){
    $students_result = mysqli_query($conn,"
    SELECT * FROM students
    WHERE semester='$selected_semester'
    AND section='$selected_section'
    ");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Teacher Dashboard</title>

<style>
body{font-family:Arial;background:#f5f7fb;}
.container{max-width:1200px;margin:auto;padding:20px;}
h2{text-align:center;color:#007bff;}
.info{background:#e8f0ff;padding:10px;border-radius:6px;margin-bottom:20px;text-align:center;}
table{width:100%;border-collapse:collapse;}
th{background:#007bff;color:white;padding:8px;}
td{padding:8px;border-bottom:1px solid #ddd;text-align:center;}
button{padding:5px 10px;border:none;border-radius:4px;cursor:pointer;}
.save{background:#28a745;color:white;}
.mentor{background:#ffc107;}
.view{background:#17a2b8;color:white;}
.logout{background:#dc3545;color:white;}
.noteBox{display:none;background:#f4f4f4;padding:10px;margin:10px;}
input[type=number]{width:60px;}
</style>

</head>

<body>

<div class="container">

<h2>Teacher Dashboard</h2>

<a href="logout.php" class="logout" style="float:right;padding:6px 12px;text-decoration:none;">Logout</a>

<div class="info">
Semester : <b><?= $selected_semester ?></b>
&nbsp;&nbsp;
Section : <b><?= $selected_section ?></b>
</div>

<!-- VIEW MARKS -->
<form method="POST" style="text-align:center;margin-bottom:10px;">
<input type="hidden" name="subject_id" value="<?= $selected_subject ?>">
<button name="view_marks" class="view">View Marks</button>
</form>

<!-- SUBJECT -->
<form method="POST" style="margin-bottom:20px;">
Subject:
<select name="subject_id" required>
<option value="">Select Subject</option>

<?php
if($selected_semester){
$subjects = mysqli_query($conn,"
SELECT subjects.* FROM subjects
JOIN teacher_subjects 
ON subjects.subject_id = teacher_subjects.subject_id
WHERE teacher_subjects.teacher_id = '$teacher_id'
AND subjects.semester = '$selected_semester'
");

while($s = mysqli_fetch_assoc($subjects)){
$sel = ($selected_subject==$s['subject_id'])?'selected':'';
echo "<option value='{$s['subject_id']}' $sel>{$s['subject_name']}</option>";
}
}
?>
</select>

<button name="load">Load Students</button>
</form>

<?php if($students_result && mysqli_num_rows($students_result)>0){ ?>

<table>
<tr>
<th>Name</th>
<th>Roll</th>
<th>Test1</th>
<th>Midterm</th>
<th>Test2</th>
<th>Final</th>
<th>%</th>
<th>Status</th>
<?php if(!$view_mode) echo "<th>Action</th>"; ?>
</tr>

<?php while($student = mysqli_fetch_assoc($students_result)){ 

$marks = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM marks
WHERE student_id=".$student['student_id']."
AND subject_id='$selected_subject'
"));
?>

<tr>

<td><?= $student['name'] ?></td>
<td><?= $student['roll_number'] ?></td>

<?php if($view_mode){ ?>

<td><?= $marks['test1'] ?? '-' ?></td>
<td><?= $marks['midterm'] ?? '-' ?></td>
<td><?= $marks['test2'] ?? '-' ?></td>
<td><?= $marks['final_exam'] ?? '-' ?></td>
<td><?= isset($marks['percentage']) ? number_format($marks['percentage'],2) : '-' ?></td>
<td><?= $marks['status'] ?? '-' ?></td>

<?php } else { ?>

<form method="POST">

<td><input type="number" name="test1" value="<?= $marks['test1'] ?? '' ?>" max="20" oninput="checkMarks(this,20)"></td>
<td><input type="number" name="midterm" value="<?= $marks['midterm'] ?? '' ?>" max="30" oninput="checkMarks(this,30)"></td>
<td><input type="number" name="test2" value="<?= $marks['test2'] ?? '' ?>" max="50" oninput="checkMarks(this,50)"></td>
<td><input type="number" name="final_exam" value="<?= $marks['final_exam'] ?? '' ?>" max="80" oninput="checkMarks(this,80)"></td>

<td><?= isset($marks['percentage']) ? number_format($marks['percentage'],2) : '-' ?></td>
<td><?= $marks['status'] ?? '-' ?></td>

<td>
<input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">
<input type="hidden" name="subject_id" value="<?= $selected_subject ?>">

<button class="save" name="update_marks">Save</button>

<button type="button" class="mentor"
onclick="document.getElementById('note<?= $student['student_id'] ?>').style.display='block'">
Mentor
</button>

</td>

</form>

<?php } ?>

</tr>

<tr>
<td colspan="9">
<div id="note<?= $student['student_id'] ?>" class="noteBox">

<form method="POST">
<textarea name="note" style="width:100%" rows="3"></textarea>

<input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">
<input type="hidden" name="semester" value="<?= $student['semester'] ?>">
<input type="hidden" name="subject_id" value="<?= $selected_subject ?>">
<br><br>

<button name="save_note">Save Note</button>

<button type="button"
onclick="document.getElementById('note<?= $student['student_id'] ?>').style.display='none'">
Close
</button>
</form>

</div>
</td>
</tr>

<?php } ?>

</table>

<?php } ?>

</div>

<!-- ✅ JS FUNCTION -->
<script>
function checkMarks(input, max){
    if(input.value > max){
        input.style.border = "2px solid red";
        input.style.backgroundColor = "#ffe6e6";
    } else {
        input.style.border = "";
        input.style.backgroundColor = "";
    }
}
</script>

</body>
</html>