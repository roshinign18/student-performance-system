<?php
session_start();
include('../../../config/database.php');

$student_id = $_SESSION['student_id'] ?? '';

if(!$student_id){
    header("Location: login.php");
    exit();
}

// STUDENT DETAILS
$student = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM students WHERE student_id='$student_id'
"));

// MARKS
$marks_query = mysqli_query($conn,"
SELECT subjects.subject_name, marks.*
FROM marks
JOIN subjects ON subjects.subject_id = marks.subject_id
WHERE marks.student_id='$student_id'
");

// AVG %
$avg = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT AVG(percentage) as avg_per FROM marks
WHERE student_id='$student_id' AND percentage IS NOT NULL
"));

// MENTORING
$mentoring = mysqli_query($conn,"
SELECT m.*, 
t.name AS teacher_name, 
COALESCE(s.subject_name,'N/A') AS subject_name
FROM mentoring m
LEFT JOIN teachers t ON t.teacher_id = m.teacher_id
LEFT JOIN subjects s ON s.subject_id = m.subject_id
WHERE m.student_id='$student_id'
ORDER BY m.mentoring_id DESC
");

// SAVE ACTIVITY
if(isset($_POST['add_activity'])){
    mysqli_query($conn,"
    INSERT INTO study_activity(student_id,date,hours_studied,focus_area,tests_taken)
    VALUES(
        '$student_id',
        '{$_POST['date']}',
        '{$_POST['hours']}',
        '{$_POST['focus']}',
        '{$_POST['tests']}'
    )");
}

// 🔥 COMBINED ACTIVITY PER DAY
$activity_chart = mysqli_query($conn,"
SELECT date, SUM(hours_studied) as total_hours
FROM study_activity
WHERE student_id='$student_id'
GROUP BY date
ORDER BY date ASC
");

// PIE CHART DATA (SUBJECT WISE TOTAL MARKS)
$pie_chart = mysqli_query($conn,"
SELECT subjects.subject_name,
COALESCE(test1,0)+COALESCE(midterm,0)+COALESCE(test2,0)+COALESCE(final_exam,0) as total_marks
FROM marks
JOIN subjects ON subjects.subject_id = marks.subject_id
WHERE student_id='$student_id'
AND test1 IS NOT NULL 
AND midterm IS NOT NULL 
AND test2 IS NOT NULL 
AND final_exam IS NOT NULL
");

// NORMAL ACTIVITY TABLE
$activities = mysqli_query($conn,"
SELECT * FROM study_activity
WHERE student_id='$student_id'
ORDER BY date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Dashboard</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{font-family:Arial;background:#f5f7fb;}
.container{max-width:1200px;margin:auto;padding:20px;}
h2{text-align:center;color:#007bff;}

.section{
background:white;
padding:15px;
border-radius:8px;
margin-bottom:20px;
box-shadow:0 2px 5px rgba(0,0,0,0.1);
}

.cards{
display:flex;
gap:20px;
margin-bottom:20px;
}

.card{
flex:1;
background:white;
padding:15px;
border-radius:8px;
text-align:center;
box-shadow:0 2px 5px rgba(0,0,0,0.1);
}

table{
width:100%;
border-collapse:collapse;
margin-top:10px;
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

.logout{
float:right;
background:#dc3545;
color:white;
padding:6px 12px;
border-radius:4px;
text-decoration:none;
}
</style>
</head>

<body>

<div class="container">

<h2>Student Dashboard</h2>
<a href="logout.php" class="logout">Logout</a>

<!-- INFO -->
<div class="section">
<h3>Welcome, <?= $student['name'] ?></h3>
<p>Semester: <?= $student['semester'] ?> | Section: <?= $student['section'] ?></p>
</div>

<!-- CARDS -->
<div class="cards">
<div class="card">
<h4>Average %</h4>
<h2><?= number_format($avg['avg_per'] ?? 0,2) ?>%</h2>
</div>

<div class="card">
<h4>Total Subjects</h4>
<h2><?= mysqli_num_rows($marks_query) ?></h2>
</div>

<div class="card">
<h4>Mentoring</h4>
<h2><?= mysqli_num_rows($mentoring) ?></h2>
</div>
</div>

<!-- MARKS -->
<div class="section">
<h3>📘 Your Marks</h3>

<table>
<tr>
<th>Subject</th>
<th>Test1</th>
<th>Midterm</th>
<th>Test2</th>
<th>Final</th>
<th>%</th>
<th>Status</th>
</tr>

<?php 
mysqli_data_seek($marks_query, 0);
while($m = mysqli_fetch_assoc($marks_query)){ ?>
<tr>
<td><?= $m['subject_name'] ?></td>
<td><?= $m['test1'] ?? '-' ?></td>
<td><?= $m['midterm'] ?? '-' ?></td>
<td><?= $m['test2'] ?? '-' ?></td>
<td><?= $m['final_exam'] ?? '-' ?></td>
<td><?= $m['percentage'] ? number_format($m['percentage'],2) : '-' ?></td>
<td><?= $m['status'] ?? '-' ?></td>
</tr>
<?php } ?>
</table>
</div>

<!-- STUDY ACTIVITY GRAPH -->
<div class="section">
<h3>📊 Study Activity (Daily Combined)</h3>
<canvas id="barChart"></canvas>
</div>

<!-- PIE BUTTON -->
<div class="section" style="text-align:center;">
<button onclick="showPie()" style="
background:#007bff;
color:white;
padding:10px 20px;
border:none;
border-radius:6px;
cursor:pointer;">
Show Subject Performance
</button>

<div id="pieBox" style="display:none;margin-top:20px;">
<canvas id="pieChart" style="max-width:400px;margin:auto;"></canvas>
</div>
</div>

<!-- ACTIVITY -->
<div class="section">
<h3>📅 Study Activity</h3>

<form method="POST">
<input type="date" name="date" required>
<input type="number" step="0.5" name="hours" placeholder="Hours" required>
<select name="focus">
<option value="">Select Subject</option>
<?php
$sub = mysqli_query($conn,"SELECT subject_name FROM subjects WHERE semester='".$student['semester']."'");
while($s = mysqli_fetch_assoc($sub)){
?>
<option value="<?= $s['subject_name'] ?>"><?= $s['subject_name'] ?></option>
<?php } ?>
</select>
<input type="number" name="tests" placeholder="Tests">
<button name="add_activity">Add</button>
</form>

<table>
<tr>
<th>Date</th>
<th>Hours</th>
<th>Focus</th>
<th>Tests</th>
</tr>

<?php while($a = mysqli_fetch_assoc($activities)){ ?>
<tr>
<td><?= $a['date'] ?></td>
<td><?= $a['hours_studied'] ?></td>
<td><?= $a['focus_area'] ?></td>
 <td><?= $a['tests_taken'] ?></td>
</tr>
<?php } ?>
</table>
</div>

</div>
<!-- ✅ PASTE MENTORING HERE -->
<div class="section">
<h3>📌 Mentoring Notes</h3>

<?php if(mysqli_num_rows($mentoring) > 0){ ?>

<table>
<tr>
<th>Teacher</th>
<th>Subject</th>
<th>Semester</th>
<th>Notes</th>
</tr>

<?php mysqli_data_seek($mentoring, 0); ?>

<?php while($m = mysqli_fetch_assoc($mentoring)){ ?>
<tr>
<td><?= $m['teacher_name'] ?? 'N/A' ?></td>
<td><?= $m['subject_name'] ?? 'N/A' ?></td>
<td><?= $m['semester'] ?></td>
<td><?= $m['notes'] ?></td>
</tr>
<?php } ?>

</table>

<?php } else { ?>
<p style="text-align:center;">No mentoring notes available</p>
<?php } ?>

</div>


</div> <!-- container ends -->

<script>

// BAR GRAPH
const ctx = document.getElementById('barChart');

if(ctx){

const barLabels = [];
const barData = [];

<?php 
mysqli_data_seek($activity_chart,0);
while($row = mysqli_fetch_assoc($activity_chart)){ ?>
barLabels.push("<?= $row['date'] ?>");
barData.push(<?= $row['total_hours'] ?? 0 ?>);
<?php } ?>

if(barLabels.length > 0){
new Chart(ctx, {
type: 'bar',
data: {
labels: barLabels,
datasets: [{
label: 'Hours Studied',
data: barData
}]
}
});
} else {
ctx.outerHTML = "<p>No activity data</p>";
}

}

// PIE GRAPH
function showPie(){
document.getElementById("pieBox").style.display="block";

const pieLabels = [];
const pieData = [];

<?php 
mysqli_data_seek($pie_chart,0);
while($row = mysqli_fetch_assoc($pie_chart)){ ?>
pieLabels.push("<?= $row['subject_name'] ?>");
pieData.push(<?= $row['total_marks'] ?? 0 ?>);
<?php } ?>

if(pieLabels.length > 0){
new Chart(document.getElementById('pieChart'), {
type: 'pie',
data: {
labels: pieLabels,
datasets: [{
data: pieData
}]
}
});
}
}

</script>
<script>
function showPie(){
document.getElementById("pieBox").style.display="block";

const pieLabels = [];
const pieData = [];

<?php 
mysqli_data_seek($pie_chart,0);
while($row = mysqli_fetch_assoc($pie_chart)){ ?>
pieLabels.push("<?= $row['subject_name'] ?>");
pieData.push(<?= $row['total_marks'] ?? 0 ?>);
<?php } ?>

if(pieLabels.length > 0){
new Chart(document.getElementById('pieChart'), {
type: 'pie',
data: {
labels: pieLabels,
datasets: [{
data: pieData
}]
}
});
} else {
document.getElementById('pieBox').innerHTML = "<p>No subject data available</p>";
}
}
</script>

</body>
</html>