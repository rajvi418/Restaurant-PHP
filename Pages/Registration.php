<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Pages</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/font.css">
</head>

<body>
    <section class="registration-container">
        <h1>Registration Page</h1>
        <form action="../index.php" method="post">
            <div class="input-group">
                <label>Name:</label>
                <input type="text" name="name" required>
            </div>

            <div class="input-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>

            <div class="input-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>

            <div class="input-group ">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>

            <a href="../index.php"><button type="submit" class="universal-btn registration-btn">Submit
                    Registration</button></a>
        </form>
    </section>

</body>

</html>