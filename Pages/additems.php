<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = htmlspecialchars($_POST['id']);
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $price = htmlspecialchars($_POST['price']);
    $category = htmlspecialchars($_POST['category']);

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "restraurant_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO items (id, name, description, price, category)
            VALUES ('$id', '$name', '$description', '$price', '$category')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Item added successfully!');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/font.css">
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<style>
    .add-items-container {
        width: 30%;
        padding: 30px;
        color: #470c0c;
        border: 2px solid #000000;
        text-align: center;
        margin: auto;
        margin-top: 50px;
        display: flex;
        flex-direction: column;
        border-radius: 10px;
        margin-bottom: 30px;
        justify-content: space-between;
    }

    .add-items-container input {
        margin-bottom: 20px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .add-container {
        padding: 15px;
    }

    .item-opt {
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
    }
</style>

<body>
    <section class="add-items-container">
        <h1>Add Items</h1>
        <form action="#" method="post">
            <div class="add-container">
                <div class="item-opt">
                    <label>
                        ID:
                    </label>
                    <input type="text" name="id">
                </div>
                <div class="item-opt">
                    <label>
                        Name:
                    </label>
                    <input type="text" name="name">
                </div>
                <div class="item-opt">
                    <label>
                        Description:
                    </label>
                    <input type="text" name="description">
                </div>
                <div class="item-opt">
                    <label>
                        Price:
                    </label>
                    <input type="number" min="0" step="1" name="price">
                </div>
                <div class="item-opt">
                    <label>
                        Category:
                    </label>
                    <input type="text" name="category">
                </div>
            </div>
            <div>
                <button class="universal-btn" type="submit">Add Item</button>
            </div>
        </form>
    </section>
</body>

</html>