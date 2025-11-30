<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restraurant_db"; // CORRECTED DATABASE NAME

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create staff table if it doesn't exist
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

// Handle form submission
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

// Handle delete staff
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM staff_details WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all staff members
$staff_sql = "SELECT * FROM staff_details ORDER BY id DESC";
$staff_result = $conn->query($staff_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Staff Management</title>
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
            background-color: #4CAF50;
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
            border-left: 4px solid #4CAF50;
            font-weight: bold;
            font-size: 18px;
        }

        .table-label {
            background-color: #f8f9fa;
            padding: 15px;
            margin: 30px 0 10px 0;
            border-radius: 8px;
            border-left: 4px solid #2196F3;
            font-weight: bold;
            font-size: 18px;
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
            <a href="../Pages/menu-management.php">
                <h3>Menu Management</h3>
            </a>
            <a href="staff-management.php">
                <h3>Staff Management</h3>
            </a>
            <a href="../Pages/settings.php">
                <h3>Settings & Preferences</h3>
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
            Add New Staff Member
        </div>

        <!-- Add Staff Form -->
        <div class="add-staff-form">
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="text" id="phone" name="phone" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="role">Staff Role:</label>
                        <select id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="Manager">Manager</option>
                            <option value="Head Chef">Head Chef</option>
                            <option value="Sous Chef">Sous Chef</option>
                            <option value="Line Cook">Line Cook</option>
                            <option value="Waiter">Waiter</option>
                            <option value="Waitress">Waitress</option>
                            <option value="Cashier">Cashier</option>
                            <option value="Bartender">Bartender</option>
                            <option value="Host/Hostess">Host/Hostess</option>
                            <option value="Cleaner">Cleaner</option>
                            <option value="Delivery Driver">Delivery Driver</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="salary">Monthly Salary ($):</label>
                        <input type="number" id="salary" name="salary" step="0.01" placeholder="0.00">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="join_date">Join Date:</label>
                        <input type="date" id="join_date" name="join_date">
                    </div>
                    <div class="form-group">
                        <label for="address">Home Address:</label>
                        <input type="text" id="address" name="address" placeholder="Enter full address">
                    </div>
                </div>

                <button type="submit" name="add_staff" class="universal-btn">➕ Add Staff Member</button>
            </form>
        </div>

        <!-- Staff List Section Label -->
        <div class="table-label">
            Staff Members List
        </div>

        <!-- Staff List Table -->
        <table class="staff-management">
            <tr class="table-header">
                <th> ID</th>
                <th> Name</th>
                <th> Phone</th>
                <th> Role</th>
                <th> Salary</th>
                <th> Join Date</th>
                <th> Actions</th>
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
                            <a href='?edit_id=" . $row['id'] . "' class='update-btn'> Update</a>
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
</body>

</html>

<?php
$conn->close();
?>