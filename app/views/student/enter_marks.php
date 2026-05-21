<?php
include('../../../config/database.php');

// Fetch Students
$students = mysqli_query($conn,"SELECT student_id, username FROM students");

// Fetch Teachers
$teachers = mysqli_query($conn,"SELECT teacher_id, username FROM teachers");

// Fetch Subjects
$subjects = mysqli_query($conn,"SELECT subject_id, subject_name FROM subjects");

// Handle form submission
if(isset($_POST['save'])){

    $student_id = $_POST['student_id'];
    $teacher_id = $_POST['teacher_id'];
    $subject_id = $_POST['subject_id'];
    $test1 = $_POST['test1'];
    $midterm = $_POST['midterm'];
    $test2 = $_POST['test2'];
    $final_exam = $_POST['final_exam'];

    // Validation for max marks
    if($test1 > 20){ echo "<p style='color:red'>Test 1 cannot be more than 20</p>"; exit(); }
    if($midterm > 30){ echo "<p style='color:red'>Midterm cannot be more than 30</p>"; exit(); }
    if($test2 > 50){ echo "<p style='color:red'>Test 2 cannot be more than 50</p>"; exit(); }
    if($final_exam > 80){ echo "<p style='color:red'>Final Exam cannot be more than 80</p>"; exit(); }

    // Calculate total & percentage
    $total = $test1 + $midterm + $test2 + $final_exam;
    $percentage = ($total / 180) * 100;

    $status = ($percentage >= 35) ? "Pass" : "Fail";

    // Insert into marks table
    $sql = "INSERT INTO marks(student_id,subject_id,teacher_id,test1,midterm,test2,final_exam,percentage,status)
            VALUES('$student_id','$subject_id','$teacher_id','$test1','$midterm','$test2','$final_exam','$percentage','$status')";
    
    if(mysqli_query($conn,$sql)){
        echo "<p style='color:green;font-weight:bold'>Marks Saved Successfully!</p>";
    }else{
        echo "<p style='color:red'>Error: ".mysqli_error($conn)."</p>";
    }
}
?>

<!-- Professional Styled Form -->
<div style="max-width:600px;margin:50px auto;padding:30px;border:1px solid #ccc;border-radius:10px;background:#f9f9f9;font-family:Arial;">
    <h2 style="text-align:center;color:#333;">Enter Student Marks</h2>
    <form method="POST" style="display:flex;flex-direction:column;gap:15px;font-size:16px;">
        
        <label>Student:</label>
        <select name="student_id" required>
            <option value="">--Select Student--</option>
            <?php while($s = mysqli_fetch_assoc($students)){ ?>
                <option value="<?= $s['student_id'] ?>"><?= $s['username'] ?></option>
            <?php } ?>
        </select>

        <label>Teacher:</label>
        <select name="teacher_id" required>
            <option value="">--Select Teacher--</option>
            <?php while($t = mysqli_fetch_assoc($teachers)){ ?>
                <option value="<?= $t['teacher_id'] ?>"><?= $t['username'] ?></option>
            <?php } ?>
        </select>

        <label>Subject:</label>
        <select name="subject_id" required>
            <option value="">--Select Subject--</option>
            <?php while($sub = mysqli_fetch_assoc($subjects)){ ?>
                <option value="<?= $sub['subject_id'] ?>"><?= $sub['subject_name'] ?></option>
            <?php } ?>
        </select>

        <label>Test 1 (Max 20):</label>
        <input type="number" name="test1" min="0" max="20" required>

        <label>Midterm (Max 30):</label>
        <input type="number" name="midterm" min="0" max="30" required>

        <label>Test 2 (Max 50):</label>
        <input type="number" name="test2" min="0" max="50" required>

        <label>Final Exam (Max 80):</label>
        <input type="number" name="final_exam" min="0" max="80" required>

        <input type="submit" name="save" value="Save Marks" style="padding:10px;background:#28a745;color:white;border:none;border-radius:5px;font-size:16px;cursor:pointer;">
    </form>
</div>