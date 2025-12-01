<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "restraurant_db";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create menu table if not exists
$create_table = "CREATE TABLE IF NOT EXISTS menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(100)
)";

$conn->query($create_table);

// Handle actions
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? 0;

// Delete item
if ($action == 'delete' && $id > 0) {
    $conn->query("DELETE FROM menu WHERE id=$id");
    header("Location: menu.php?msg=Item deleted");
    exit();
}

// Save item (Add/Edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);

    if (isset($_POST['item_id']) && $_POST['item_id'] > 0) {
        // Update
        $item_id = intval($_POST['item_id']);
        $sql = "UPDATE menu SET 
                name='$name', 
                description='$description', 
                price=$price, 
                category='$category' 
                WHERE id=$item_id";
    } else {
        // Insert
        $sql = "INSERT INTO menu (name, description, price, category) 
                VALUES ('$name', '$description', $price, '$category')";
    }

    if ($conn->query($sql)) {
        header("Location: menu.php?msg=Item saved");
        exit();
    }
}

// Get item for edit
$edit_item = null;
if ($action == 'edit' && $id > 0) {
    $result = $conn->query("SELECT * FROM menu WHERE id=$id");
    $edit_item = $result->fetch_assoc();
}

// Get all menu items
$menu_items = $conn->query("SELECT * FROM menu ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Menu</title>
    <link rel="stylesheet" href="../Bootstrap/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="../Bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="../Bootstrap/dist/css/bootstrap-utilities.css">
    <link rel="stylesheet" href="../CSS/font.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .form-container {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
            border: 1px solid #ddd;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 14px;
        }

        .form-group textarea {
            height: 80px;
            resize: vertical;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-success {
            background: #470c0c;
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #ddd;
            font-weight: bold;
            color: #333;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            color: #555;
        }

        table tr:hover {
            background: #f9f9f9;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 3px;
        }

        .success {
            background: #d4edda;
            color: #470c0c;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <header class="dashboard-header ">
        <div class="logo">
            <img src="../Logo/logo.png" alt="Logo">
        </div>

        <div class="slider-section">
            <a href="Dashboard.php">
                <h3>Dashboard</h3>
            </a>
            <a href="../Pages/menu-management.php">
                <h3>Menu Management</h3>
            </a>
            <a href="../Pages/inventory.php">
                <h3>Inventory Management</h3>
            </a>
            <a href="../Pages/staff-management.php">
                <h3>Staff Management</h3>
            </a>
            <a href="../Pages/offers.php">
                <h3>Offers & Promotions</h3>
            </a>
            <a href="../Pages/account.php">
                <h3>Account</h3>
            </a>
        </div>

    </header>
    <section class="content-container">
        <?php if (isset($_GET['msg'])): ?>
            <div class="message success">
                <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <?php if ($action == 'add' || $action == 'edit'): ?>
            <!-- Add/Edit Form -->
            <div class="form-container">
                <h2><?php echo $action == 'add' ? 'Add Items' : 'Edit Item'; ?></h2>
                <form method="POST">
                    <input type="hidden" name="item_id" value="<?php echo $edit_item['id'] ?? ''; ?>">

                    <?php if ($edit_item): ?>
                        <div class="form-group">
                            <label>ID:</label>
                            <input type="text" value="<?php echo $edit_item['id']; ?>" disabled style="background: #f0f0f0;">
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" name="name" required value="<?php echo htmlspecialchars($edit_item['name'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Description:</label>
                        <textarea name="description"><?php echo htmlspecialchars($edit_item['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Price:</label>
                        <input type="number" name="price" step="0.01" required value="<?php echo $edit_item['price'] ?? ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Category:</label>
                        <select name="category">
                            <option value="">Select Category</option>
                            <option value="Appetizers" <?php echo ($edit_item['category'] ?? '') == 'Appetizers' ? 'selected' : ''; ?>>Appetizers</option>
                            <option value="Main Course" <?php echo ($edit_item['category'] ?? '') == 'Main Course' ? 'selected' : ''; ?>>Main Course</option>
                            <option value="Desserts" <?php echo ($edit_item['category'] ?? '') == 'Desserts' ? 'selected' : ''; ?>>Desserts</option>
                            <option value="Beverages" <?php echo ($edit_item['category'] ?? '') == 'Beverages' ? 'selected' : ''; ?>>Beverages</option>
                            <option value="Fast Food" <?php echo ($edit_item['category'] ?? '') == 'Fast Food' ? 'selected' : ''; ?>>Fast Food</option>
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary">
                            <?php echo $action == 'add' ? 'Add Item' : 'Update Item'; ?>
                        </button>
                        <a href="menu.php" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <!-- Add New Item Button -->
            <div style="text-align: right; margin-bottom: 20px;">
                <a href="menu.php?action=add" class="btn btn-success">+ Add New Item</a>
            </div>

            <!-- Menu Table -->
            <h2>Restaurant Menu</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($menu_items->num_rows > 0): ?>
                        <?php while ($item = $menu_items->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $item['id']; ?></td>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo htmlspecialchars($item['description']); ?></td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td class="actions">
                                    <a href="menu.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-warning">Update</a>
                                    <a href="menu.php?action=delete&id=<?php echo $item['id']; ?>"
                                        class="btn btn-danger"
                                        onclick="return confirm('Delete this item?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px;">
                                No menu items found. Click "Add New Item" to create one.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>


        <?php endif; ?>

        <footer class="dashboard-footer position-fixed bottom-0">
            <p>&copy; 2024 Restaurant Management System. All rights reserved.</p>
        </footer>
    </section>

    <script>
        // Auto-hide message after 3 seconds
        setTimeout(function() {
            var message = document.querySelector('.message');
            if (message) {
                message.style.display = 'none';
            }
        }, 3000);
    </script>
</body>

</html>

<?php $conn->close(); ?>