<?php
session_start();
include('../../../config/database.php');

// ADD SUBJECT
if(isset($_POST['add_subject'])){
    $name = $_POST['subject_name'];
    $semester = $_POST['semester'];

    mysqli_query($conn,"INSERT INTO subjects(subject_name,semester)
    VALUES('$name','$semester')");
}

// SEARCH
$search = $_GET['search'] ?? '';

$query = "SELECT * FROM subjects";

if($search != ''){
    $query .= " WHERE subject_name LIKE '%$search%'";
}

$query .= " ORDER BY semester";

$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Subjects</title>

<style>
body{
    margin:0;
    font-family: 'Segoe UI';
    background:#f4f6fb;
}

.container{
    width:90%;
    margin:auto;
    margin-top:30px;
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

h2{
    color:#333;
}

/* ADD FORM */
.card{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    margin-top:20px;
}

input, select{
    padding:10px;
    width:200px;
    margin-right:10px;
    border-radius:5px;
    border:1px solid #ccc;
}

button{
    padding:10px 15px;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

.add-btn{
    background:#28a745;
    color:white;
}

/* SEARCH */
.search-box{
    margin-top:20px;
}

.search-box input{
    width:300px;
}

/* TABLE */
table{
    width:100%;
    margin-top:20px;
    border-collapse:collapse;
    background:white;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

th{
    background:#007bff;
    color:white;
    padding:12px;
}

td{
    padding:10px;
    text-align:center;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#f1f1f1;
}

/* DELETE */
.delete{
    background:#dc3545;
    color:white;
    padding:5px 10px;
    text-decoration:none;
    border-radius:4px;
}
</style>

</head>

<body>

<div class="container">

<div class="header">
<h2>📘 Manage Subjects</h2>
<a href="admin_dashboard.php" style="text-decoration:none;">⬅ Back</a>
</div>

<!-- ADD SUBJECT -->
<div class="card">

<h3>Add Subject</h3>

<form method="POST">

<input type="text" name="subject_name" placeholder="Subject Name" required>

<select name="semester" required>
<option value="">Semester</option>
<option>1</option>
<option>2</option>
<option>3</option>
<option>4</option>
<option>5</option>
<option>6</option>
</select>

<button name="add_subject" class="add-btn">Add</button>

</form>

</div>

<!-- SEARCH -->
<div class="search-box">
<form method="GET">
<input type="text" name="search" placeholder="Search subject..." value="<?= $search ?>">
<button>Search</button>
</form>
</div>

<!-- SUBJECT LIST -->
<table>

<tr>
<th>ID</th>
<th>Subject</th>
<th>Semester</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>
<td><?= $row['subject_id'] ?></td>
<td><?= $row['subject_name'] ?></td>
<td><?= $row['semester'] ?></td>

<td>
<a class="delete" href="delete_subject.php?id=<?= $row['subject_id'] ?>">
Delete
</a>
</td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>