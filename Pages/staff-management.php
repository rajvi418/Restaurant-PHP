<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restraurant_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$create_table_sql = "CREATE TABLE IF NOT EXISTS staff_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    role VARCHAR(50) NOT NULL,
    salary DECIMAL(10,2),
    join_date DATE,
    address TEXT,
    status VARCHAR(20) DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$conn->query($create_table_sql);

// Handle form submission for ADD staff
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_staff'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $role = trim($_POST['role']);
    $salary = trim($_POST['salary']);
    $join_date = trim($_POST['join_date']);
    $address = trim($_POST['address']);

    if (!empty($name) && !empty($phone) && !empty($role)) {
        $insert_sql = "INSERT INTO staff_details (name, phone, role, salary, join_date, address) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sssdss", $name, $phone, $role, $salary, $join_date, $address);
        $stmt->execute();
        $stmt->close();
    }
}

// Handle form submission for UPDATE staff
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_staff'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $role = trim($_POST['role']);
    $salary = trim($_POST['salary']);
    $join_date = trim($_POST['join_date']);
    $address = trim($_POST['address']);

    if (!empty($name) && !empty($phone) && !empty($role)) {
        $update_sql = "UPDATE staff_details SET name=?, phone=?, role=?, salary=?, join_date=?, address=? WHERE id=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssdssi", $name, $phone, $role, $salary, $join_date, $address, $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Handle delete staff
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM staff_details WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
}

// Check if we're editing a staff member
$edit_staff = null;
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $edit_sql = "SELECT * FROM staff_details WHERE id = ?";
    $stmt = $conn->prepare($edit_sql);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_result = $stmt->get_result();

    if ($edit_result->num_rows > 0) {
        $edit_staff = $edit_result->fetch_assoc();
    }
    $stmt->close();
}

// Fetch all staff members in ASCENDING order
$staff_sql = "SELECT * FROM staff_details ORDER BY id ASC";
$staff_result = $conn->query($staff_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Staff Management</title>
    <link rel="stylesheet" href="../Bootstrap/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="../Bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="../Bootstrap/dist/css/bootstrap-utilities.css">
    <link rel="stylesheet" href="../CSS/font.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <style>
        body {
            color: black;
        }

        .staff-management {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .staff-management th,
        .staff-management td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .staff-management .table-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .update-btn,
        .delete-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
        }

        .update-btn {
            background-color: #470c0c;
            color: white;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
        }

        .add-staff-form {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .section-label {
            background-color: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            border-left: 4px solid #470c0c;
            font-weight: bold;
            font-size: 18px;
        }

        .table-label {
            background-color: #f8f9fa;
            padding: 15px;
            margin: 30px 0 10px 0;
            border-radius: 8px;
            font-weight: bold;
            font-size: 18px;
        }

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
        <div class="menu-header">
            <h3 class="title">Staff Management</h3>
        </div>

        <!-- Add Staff Section Label -->
        <div class="section-label">
            <?php echo $edit_staff ? 'Update Staff Member' : 'Add New Staff Member'; ?>
        </div>

        <!-- Add/Update Staff Form -->
        <div class="add-staff-form">
            <form method="POST" action="">
                <?php if ($edit_staff): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_staff['id']; ?>">
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name:</label>
                        <input type="text" id="name" name="name"
                            value="<?php echo $edit_staff ? htmlspecialchars($edit_staff['name']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="text" id="phone" name="phone"
                            value="<?php echo $edit_staff ? htmlspecialchars($edit_staff['phone']) : ''; ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="role">Staff Role:</label>
                        <select id="role" name="role" required>
                            <option value="">Select Role</option>
                            <?php
                            $roles = [
                                "Manager",
                                "Head Chef",
                                "Sous Chef",
                                "Line Cook",
                                "Waiter",
                                "Waitress",
                                "Cashier",
                                "Bartender",
                                "Host/Hostess",
                                "Cleaner",
                                "Delivery Driver"
                            ];
                            foreach ($roles as $role) {
                                $selected = ($edit_staff && $edit_staff['role'] == $role) ? 'selected' : '';
                                echo "<option value='$role' $selected>$role</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="salary">Monthly Salary ($):</label>
                        <input type="number" id="salary" name="salary" step="0.01" placeholder="0.00"
                            value="<?php echo $edit_staff ? $edit_staff['salary'] : ''; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="join_date">Join Date:</label>
                        <input type="date" id="join_date" name="join_date"
                            value="<?php echo $edit_staff ? $edit_staff['join_date'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="address">Home Address:</label>
                        <input type="text" id="address" name="address" placeholder="Enter full address"
                            value="<?php echo $edit_staff ? htmlspecialchars($edit_staff['address']) : ''; ?>">
                    </div>
                </div>

                <?php if ($edit_staff): ?>
                    <div class="form-actions">
                        <a href="staff-management.php" class="cancel-btn">Cancel</a>
                        <button type="submit" name="update_staff" class="universal-btn">Update Staff Member</button>
                    </div>
                <?php else: ?>
                    <button type="submit" name="add_staff" class="universal-btn">+ Add Staff Member</button>
                <?php endif; ?>
            </form>
        </div>

        <!-- Staff List Section Label -->
        <div class="table-label">
            Staff Members List
        </div>

        <!-- Staff List Table -->
        <table class="staff-management">
            <tr class="table-header">
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Salary</th>
                <th>Join Date</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($staff_result && $staff_result->num_rows > 0) {
                while ($row = $staff_result->fetch_assoc()) {
                    echo "<tr class='table-data'>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                    echo "<td>$" . ($row['salary'] ? number_format($row['salary'], 2) : '0.00') . "</td>";
                    echo "<td>" . ($row['join_date'] ? htmlspecialchars($row['join_date']) : 'Not set') . "</td>";
                    echo "<td class='action-buttons'>
                            <a href='?edit_id=" . $row['id'] . "' class='update-btn'>Update</a>
                            <a href='?delete_id=" . $row['id'] . "' class='delete-btn' 
                               onclick='return confirm(\"Are you sure you want to delete " . addslashes($row['name']) . "?\")'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' style='text-align: center; padding: 20px;'>No staff members found. Add your first staff member above.</td></tr>";
            }
            ?>
        </table>

        <footer class="dashboard-footer">
            <p>&copy; 2024 Restaurant Management System. All rights reserved.</p>
        </footer>
    </section>

    <script>
        // Close update form with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && <?php echo $edit_staff ? 'true' : 'false'; ?>) {
                window.location.href = 'staff-management.php';
            }
        });
    </script>
</body>

</html>

<?php
$conn->close();
?>