<?php
// edit_simple.php
require_once '../config/db_connection.php';

// Get offer ID
$id = $_GET['id'];

// If form submitted, update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $valid_till = $conn->real_escape_string($_POST['valid_till']);
    $status = $conn->real_escape_string($_POST['status']);

    $sql = "UPDATE offers SET 
            title='$title',
            description='$description',
            valid_till='$valid_till',
            status='$status'
            WHERE id=$id";

    if ($conn->query($sql)) {
        header("Location: offers.php?success=Offer updated!");
    } else {
        echo "Error: " . $conn->error;
    }
}

// Get current offer data
$sql = "SELECT * FROM offers WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Offer</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }

        form {
            max-width: 500px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
        }

        button {
            background: #7b1f1f;
            color: white;
            padding: 10px 20px;
            border: none;
        }
    </style>
</head>

<body>
    <h2>Edit Offer</h2>
    <form method="POST">
        <div>
            <label>Title:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required>
        </div>
        <div>
            <label>Description:</label>
            <textarea name="description"><?php echo htmlspecialchars($row['description']); ?></textarea>
        </div>
        <div>
            <label>Valid Till:</label>
            <input type="date" name="valid_till" value="<?php echo $row['valid_till']; ?>" required>
        </div>
        <div>
            <label>Status:</label>
            <select name="status">
                <option value="active" <?php echo ($row['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                <option value="expired" <?php echo ($row['status'] == 'expired') ? 'selected' : ''; ?>>Expired</option>
            </select>
        </div>
        <button type="submit">Update Offer</button>
        <button type="button" onclick="window.location.href='offers.php'">Cancel</button>
    </form>
</body>

</html>
<?php $conn->close(); ?>