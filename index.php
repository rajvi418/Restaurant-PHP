<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/font.css">
</head>

<body>
    <section class="login-container">
        <h1>Admin Login</h1>
        <form action="Pages/Dashboard.php" method="post">
            <input type="text" placeholder="Username" required>
            <input type="password" placeholder="Password" required>
            <a href="Pages/Dashboard.php"><button type="submit">Login</button></a>
        </form>
        <section>
            <h6>New Here? <a href="Pages/Registration.php">Register</a></h6>
        </section>
    </section>
</body>

</html>