<!DOCTYPE html>
<html>
<head>

<title>Select Class</title>

<style>

body{
font-family:Arial;
background:linear-gradient(135deg,#667eea,#764ba2);
height:100vh;
display:flex;
justify-content:center;
align-items:center;
}

.box{
background:white;
padding:40px;
border-radius:12px;
text-align:center;
width:350px;
}

select,button{
width:100%;
padding:10px;
margin:10px 0;
}

button{
background:#667eea;
color:white;
border:none;
cursor:pointer;
}

h2{
margin-bottom:20px;
}

</style>

</head>

<body>

<div class="box">

<h2>Select Class</h2>

<form action="teacher_dashboard.php" method="GET">

<select name="semester" required>
<option value="">Select Semester</option>
<option value="1">Sem 1</option>
<option value="2">Sem 2</option>
<option value="3">Sem 3</option>
<option value="4">Sem 4</option>
<option value="5">Sem 5</option>
<option value="6">Sem 6</option>
</select>

<select name="section" required>
<option value="">Select Section</option>
<option value="A">A</option>
<option value="B">B</option>
<option value="C">C</option>
</select>

<button type="submit">Go To Dashboard</button>

</form>

</div>

</body>
</html>