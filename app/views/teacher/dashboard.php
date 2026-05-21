<?php
include('../../../config/database.php');

// Handle filter submission
$selected_semester = isset($_POST['semester']) ? $_POST['semester'] : '';
$selected_section = isset($_POST['section']) ? $_POST['section'] : '';

// Fetch students based on semester & section
$students_query = "SELECT * FROM students";
if($selected_semester != '' && $selected_section != ''){
    $students_query .= " WHERE semester='$selected_semester' AND section='$selected_section'";
}
$students_result = mysqli_query($conn, $students_query);

// Fetch subjects
$subjects_result = mysqli_query($conn,"SELECT * FROM subjects");

// Fetch teachers for dropdown (optional)
$teachers_result = mysqli_query($conn,"SELECT * FROM teachers");
?>

<div style="max-width:900px;margin:30px auto;font-family:Arial;">
    <h2 style="text-align:center;color:#333;">Teacher Dashboard</h2>

    <!-- Filter Section -->
    <form method="POST" style="display:flex;gap:20px;margin-bottom:20px;align-items:center;">
        <div>
            <label>Semester:</label>
            <select name="semester" required>
                <option value="">--Select Semester--</option>
                <?php for($i=1;$i<=6;$i++){ ?>
                    <option value="<?= $i ?>" <?= ($selected_semester==$i)?'selected':'' ?>><?= $i ?></option>
                <?php } ?>
            </select>
        </div>
        <div>
            <label>Section:</label>
            <select name="section" required>
                <option value="">--Select Section--</option>
                <option value="A" <?= ($selected_section=='A')?'selected':'' ?>>A</option>
                <option value="B" <?= ($selected_section=='B')?'selected':'' ?>>B</option>
                <option value="C" <?= ($selected_section=='C')?'selected':'' ?>>C</option>
            </select>
        </div>
        <input type="submit" value="Filter" style="padding:5px 10px;background:#007bff;color:white;border:none;border-radius:5px;cursor:pointer;">
    </form>

    <!-- Students Table -->
    <table style="width:100%;border-collapse:collapse;">
        <thead style="background:#007bff;color:white;">
            <tr>
                <th style="padding:8px;border:1px solid #ccc;">Student Name</th>
                <th style="padding:8px;border:1px solid #ccc;">Roll Number</th>
                <th style="padding:8px;border:1px solid #ccc;">Test 1</th>
                <th style="padding:8px;border:1px solid #ccc;">Midterm</th>
                <th style="padding:8px;border:1px solid #ccc;">Test 2</th>
                <th style="padding:8px;border:1px solid #ccc;">Final Exam</th>
                <th style="padding:8px;border:1px solid #ccc;">Percentage</th>
                <th style="padding:8px;border:1px solid #ccc;">Status</th>
                <th style="padding:8px;border:1px solid #ccc;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($student = mysqli_fetch_assoc($students_result)){ 
                // Fetch marks for this student
                $marks_query = "SELECT * FROM marks WHERE student_id=".$student['student_id'];
                $marks_result = mysqli_query($conn,$marks_query);
                $marks = mysqli_fetch_assoc($marks_result);
            ?>
            <tr>
                <td style="padding:8px;border:1px solid #ccc;"><?= $student['name'] ?></td>
                <td style="padding:8px;border:1px solid #ccc;"><?= $student['roll_number'] ?></td>

                <td style="padding:8px;border:1px solid #ccc;">
                    <input type="number" value="<?= isset($marks['test1'])?$marks['test1']:'' ?>" max="20" min="0" style="width:60px;">
                </td>
                <td style="padding:8px;border:1px solid #ccc;">
                    <input type="number" value="<?= isset($marks['midterm'])?$marks['midterm']:'' ?>" max="30" min="0" style="width:60px;">
                </td>
                <td style="padding:8px;border:1px solid #ccc;">
                    <input type="number" value="<?= isset($marks['test2'])?$marks['test2']:'' ?>" max="50" min="0" style="width:60px;">
                </td>
                <td style="padding:8px;border:1px solid #ccc;">
                    <input type="number" value="<?= isset($marks['final_exam'])?$marks['final_exam']:'' ?>" max="80" min="0" style="width:60px;">
                </td>
                <td style="padding:8px;border:1px solid #ccc;">
                    <?= isset($marks['percentage'])?number_format($marks['percentage'],2).'%' : '-' ?>
                </td>
                <td style="padding:8px;border:1px solid #ccc;color:<?= (isset($marks['status']) && $marks['status']=='Pass')?'green':'red' ?>;">
                    <?= isset($marks['status'])?$marks['status']:'-' ?>
                </td>
                <td style="padding:8px;border:1px solid #ccc;">
                    <button style="padding:5px 10px;background:#28a745;color:white;border:none;border-radius:5px;cursor:pointer;">Mentor</button>
                    <button style="padding:5px 10px;background:#ffc107;color:white;border:none;border-radius:5px;cursor:pointer;">Edit</button>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>