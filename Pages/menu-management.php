<?php
// --- Connect to the database ---
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restraurant_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error message
$error_message = "";

// Handle Delete Action
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']); // Sanitize input
    if ($delete_id > 0) {
        $delete_sql = "DELETE FROM items WHERE id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $delete_id);

        if ($stmt->execute()) {
            header("Location: " . str_replace("&delete_id=" . $delete_id, "", $_SERVER['REQUEST_URI']));
            exit();
        } else {
            $error_message = "Error deleting item: " . $conn->error;
        }
        $stmt->close();
    } else {
        $error_message = "Invalid item ID for deletion";
    }
}

// Handle Update Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_item'])) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);

    $update_sql = "UPDATE items SET name=?, description=?, price=?, category=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssdsi", $name, $description, $price, $category, $id);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $error_message = "Error updating record: " . $conn->error;
    }
    $stmt->close();
}

// Fetch item data for editing if edit_id is set
$edit_item = null;
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    if ($edit_id > 0) {
        $edit_sql = "SELECT * FROM items WHERE id = ?";
        $stmt = $conn->prepare($edit_sql);
        $stmt->bind_param("i", $edit_id);
        $stmt->execute();
        $edit_result = $stmt->get_result();

        if ($edit_result->num_rows > 0) {
            $edit_item = $edit_result->fetch_assoc();
        }
        $stmt->close();
    }
}

// --- Fetch all items from database ---
$sql = "SELECT * FROM items";
$result = $conn->query($sql);

// Check if query was successful
if (!$result) {
    $error_message = "Error fetching items: " . $conn->error;
    $result = []; // Set empty result to avoid further errors
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Restaurant Menu</title>
    <link rel="stylesheet" href="../CSS/font.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <style>
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .update-btn,
        .delete-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }

        .update-btn {
            background-color: #4CAF50;
            color: white;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
        }

        .update-btn:hover {
            background-color: #45a049;
        }

        .delete-btn:hover {
            background-color: #da190b;
        }

        /* Update Form Styles */
        .update-form-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .update-form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            width: 500px;
            max-width: 90%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .cancel-btn {
            background-color: #6c757d;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .submit-btn {
            background-color: #007bff;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            border: 1px solid #c3e6cb;
        }

        /* Table Styles */
        .menu-management {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .menu-management th,
        .menu-management td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .menu-management .table-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .description-cell {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .no-items {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-style: italic;
        }
    </style>
</head>

<body>
    <header class="dashboard-header">
        <div class="logo">
            <img src="../Logo/logo.png" alt="Logo">
        </div>
        <div class="slider-section">
            <a href="../Pages/Dashboard.php">
                <h3>Dashboard</h3>
            </a>
            <a href="menu-management.php">
                <h3>Menu Management</h3>
            </a>
            <a href="../Pages/staff-management.php">
                <h3>Staff Management</h3>
            </a>

            <a href="../Pages/account.php">
                <h3>Account</h3>
            </a>
        </div>
    </header>
    <section class="content-container">
        <div class="menu-header">
            <h3 class="title">Restaurant Menu</h3>
            <a href="additems.php"><button class="universal-btn">+ Add Item </button></a>
        </div>

        <?php if (isset($error_message) && !empty($error_message)): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 'success'): ?>
            <div class="success-message">
                Item deleted successfully!
            </div>
        <?php endif; ?>

        <table class="menu-management">
            <tr class="table-header">
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
            <?php
            // --- Display each row ---
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr class='table-data'>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td class='description-cell' title='" . htmlspecialchars($row['description']) . "'>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td>$" . number_format($row['price'], 2) . "</td>";
                    echo "<td class='action-buttons'>
                                <a href='?edit_id=" . $row['id'] . "' class='update-btn'>Update</a>
                                <a href='?delete_id=" . $row['id'] . "' class='delete-btn' 
                                   onclick='return confirm(\"Are you sure you want to delete \\\"" . addslashes($row['name']) . "\\\"?\")'>Delete</a>
                              </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='no-items'>No menu items found.</td></tr>";
            }
            ?>
        </table>

        <!-- Update Form (shown when editing) -->
        <?php if ($edit_item): ?>
            <div class="update-form-overlay">
                <div class="update-form-container">
                    <h3>Update Menu Item</h3>
                    <form method="POST" action="">
                        <input type="hidden" name="id" value="<?php echo $edit_item['id']; ?>">

                        <div class="form-group">
                            <label for="name">Item Name:</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($edit_item['name']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea id="description" name="description" rows="3" required><?php echo htmlspecialchars($edit_item['description']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="price">Price:</label>
                            <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo $edit_item['price']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="category">Category:</label>
                            <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($edit_item['category']); ?>">
                        </div>

                        <div class="form-actions">
                            <a href="?" class="cancel-btn">Cancel</a>
                            <button type="submit" name="update_item" class="submit-btn">Update Item</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <footer class="dashboard-footer">
            <p>&copy; 2024 Restaurant Management System. All rights reserved.</p>
        </footer>
    </section>

    <script>
        // Close update form when clicking outside
        document.addEventListener('click', function(event) {
            const overlay = document.querySelector('.update-form-overlay');
            if (event.target === overlay) {
                window.location.href = '?';
            }
        });

        // Close update form with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                window.location.href = '?';
            }
        });

        // Show full description on hover
        document.addEventListener('DOMContentLoaded', function() {
            const descriptionCells = document.querySelectorAll('.description-cell');
            descriptionCells.forEach(cell => {
                cell.addEventListener('mouseenter', function() {
                    this.style.whiteSpace = 'normal';
                    this.style.overflow = 'visible';
                    this.style.position = 'relative';
                    this.style.zIndex = '10';
                    this.style.background = 'white';
                    this.style.boxShadow = '0 2px 5px rgba(0,0,0,0.2)';
                });

                cell.addEventListener('mouseleave', function() {
                    this.style.whiteSpace = 'nowrap';
                    this.style.overflow = 'hidden';
                    this.style.position = 'static';
                    this.style.zIndex = '1';
                    this.style.background = '';
                    this.style.boxShadow = '';
                });
            });
        });
    </script>
</body>

</html>

<?php
$conn->close();
?>