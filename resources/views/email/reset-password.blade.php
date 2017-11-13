<!DOCTYPE html>
<html>
<head>
    <style>
        .logo {
            background-color: #d3d3d3;
            padding: 10px 20px;
            display: block;
            margin: 0 auto;
        }
        .title {
            color: #1eb6dc;
            text-align: center;
            text-decoration: underline;
        }
        .center {
            margin-left: 20px;
            margin-right: 20px;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px 30px;
        }
    </style>
</head>
<body>
<h2 class="title">Reset Password Confirmation: </h2>
<div class="center">
    <p>Email:  {{ $email }}</p>
    <br>
    <p>Password: {{ $password}}</p>
</div>


</body>
</html>