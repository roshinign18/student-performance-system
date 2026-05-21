<?php
include('../../../config/database.php');

$id = $_GET['id'];

mysqli_query($conn,"DELETE FROM subjects WHERE subject_id='$id'");

header("Location: add_subject.php");
?>