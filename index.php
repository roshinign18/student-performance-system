<!DOCTYPE html>
<html>
<head>
    <title>Student Performance Tracking System</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>

        body{
            background: linear-gradient(to right,#4facfe,#00f2fe);
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            font-family:Arial;
        }

        .main-box{
            background:white;
            padding:40px;
            border-radius:10px;
            box-shadow:0 10px 30px rgba(0,0,0,0.2);
            text-align:center;
            width:400px;
        }

        h2{
            margin-bottom:30px;
        }

        .btn{
            width:100%;
            margin-bottom:15px;
        }

    </style>
</head>

<body>

<div class="main-box">

<h2>Student Performance System</h2>

<a href="app/views/admin/login.php" class="btn btn-dark">Admin Login</a>

<a href="app/views/teacher/login.php" class="btn btn-primary">Teacher Login</a>

<a href="app/views/student/login.php" class="btn btn-success">Student Login</a>

<a href="app/views/student/register.php" class="btn btn-warning">Student Register</a>

</div>

</body>
</html>