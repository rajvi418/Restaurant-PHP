<?php
// START - DATABASE CONNECTION
$host = "localhost";
$username = "root";
$password = "";
$database = "restraurant_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// END - DATABASE CONNECTION

// HANDLE ALL ACTIONS
$action = isset($_GET['action']) ? $_GET['action'] : 'view';
$id = isset($_GET['id']) ? $_GET['id'] : 0;

// DELETE ACTION
if ($action == 'delete' && $id > 0) {
    $conn->query("DELETE FROM offers WHERE id=$id");
    header("Location: offers.php");
    exit();
}

// ADD/EDIT ACTION (When form is submitted)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $valid_till = $conn->real_escape_string($_POST['valid_till']);
    $status = $conn->real_escape_string($_POST['status']);

    if (isset($_POST['offer_id']) && $_POST['offer_id'] > 0) {
        // UPDATE EXISTING
        $offer_id = $_POST['offer_id'];
        $sql = "UPDATE offers SET 
                title='$title',
                description='$description',
                valid_till='$valid_till',
                status='$status'
                WHERE id=$offer_id";
    } else {
        // INSERT NEW
        $sql = "INSERT INTO offers (title, description, valid_till, status) 
                VALUES ('$title', '$description', '$valid_till', '$status')";
    }

    if ($conn->query($sql)) {
        header("Location: offers.php");
        exit();
    }
}

// GET DATA FOR EDIT
$edit_data = null;
if ($action == 'edit' && $id > 0) {
    $result = $conn->query("SELECT * FROM offers WHERE id=$id");
    if ($result->num_rows > 0) {
        $edit_data = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offers & Promotions</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/font.css">
    <style>
        /* BASIC STYLES */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        /* HEADER */
        .header {
            background: #7b1f1f;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .header h1 {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* OFFER CARDS */
        .offer-card {
            background: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            border-left: 5px solid #7b1f1f;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .offer-card h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .offer-card p {
            color: #666;
            margin-bottom: 10px;
            line-height: 1.5;
        }

        /* BADGES */
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }

        .badge.active {
            background: #28a745;
        }

        .badge.expired {
            background: #6c757d;
        }

        /* BUTTONS */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }

        .btn-primary {
            background: #7b1f1f;
            color: white;
        }

        .btn-edit {
            background: #ffc107;
            color: #212529;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* ACTION BUTTONS */
        .action-buttons {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        /* FORM */
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            margin: 20px auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: #7b1f1f;
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .form-footer {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        /* MESSAGES */
        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        /* NO OFFERS */
        .no-offers {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 18px;
        }

        /* PAGE LINKS */
        .page-links {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .page-links a {
            color: #7b1f1f;
            text-decoration: none;
            margin-right: 20px;
            font-weight: bold;
        }

        .page-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <header class="dashboard-header">
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
        <div class="container">
            <!-- PAGE HEADER -->
            <div class="header">
                <h1>🎉 Offers & Promotions</h1>
            </div>

            <!-- NAVIGATION LINKS -->
            <div class="page-links">
                <a href="offers.php">View All Offers</a>
                <a href="offers.php?action=add">+ Add New Offer</a>
                <a href="Dashboard.php">← Back to Dashboard</a>
            </div>

            <!-- SHOW MESSAGES -->
            <?php if (isset($_GET['success'])): ?>
                <div class="message success">
                    <?php echo htmlspecialchars($_GET['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="message error">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <?php if ($action == 'add' || $action == 'edit'): ?>
                <div class="form-container">
                    <h2><?php echo $action == 'add' ? 'Add New Offer' : 'Edit Offer'; ?></h2>

                    <form method="POST" action="offers.php">
                        <?php if ($action == 'edit'): ?>
                            <input type="hidden" name="offer_id" value="<?php echo $id; ?>">
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="title">Title *</label>
                            <input type="text" id="title" name="title" class="form-control" required
                                value="<?php echo $edit_data ? htmlspecialchars($edit_data['title']) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control"><?php echo $edit_data ? htmlspecialchars($edit_data['description']) : ''; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="valid_till">Valid Till *</label>
                            <input type="date" id="valid_till" name="valid_till" class="form-control" required
                                value="<?php echo $edit_data ? $edit_data['valid_till'] : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="active" <?php echo ($edit_data && $edit_data['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                <option value="expired" <?php echo ($edit_data && $edit_data['status'] == 'expired') ? 'selected' : ''; ?>>Expired</option>
                            </select>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">
                                <?php echo $action == 'add' ? 'Save Offer' : 'Update Offer'; ?>
                            </button>
                            <a href="offers.php" class="btn btn-cancel">Cancel</a>
                        </div>
                    </form>
                </div>

            <?php else: ?>
                <?php
                $result = $conn->query("SELECT * FROM offers ORDER BY status, valid_till DESC");

                if ($result->num_rows > 0):
                ?>
                    <?php while ($row = $result->fetch_assoc()):
                        $status_class = $row['status'] == 'active' ? 'active' : 'expired';
                        $status_text = $row['status'] == 'active' ? 'Active' : 'Expired';
                        $valid_date = date("d M Y", strtotime($row['valid_till']));
                    ?>
                        <div class="offer-card">
                            <div class="action-buttons">
                                <a href="offers.php?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                                <a href="offers.php?action=delete&id=<?php echo $row['id']; ?>"
                                    class="btn btn-delete"
                                    onclick="return confirm('Delete this offer?')">Delete</a>
                            </div>

                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>

                            <?php if (!empty($row['description'])): ?>
                                <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <?php endif; ?>

                            <p><strong>📅 Valid Till:</strong> <?php echo $valid_date; ?></p>

                            <span class="badge <?php echo $status_class; ?>">
                                <?php echo $status_text; ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-offers">
                        <p>No offers available. Click "Add New Offer" to create one.</p>
                    </div>
                <?php endif; ?>

                <!-- ADD BUTTON -->
                <div style="text-align: center; margin-top: 30px;">
                    <a href="offers.php?action=add" class="btn btn-primary">+ Add New Offer</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <script>
        // Set minimum date to today
        window.onload = function() {
            const dateInput = document.getElementById('valid_till');
            if (dateInput) {
                const today = new Date().toISOString().split('T')[0];
                dateInput.min = today;
            }

            // Auto-hide messages after 5 seconds
            setTimeout(() => {
                const messages = document.querySelectorAll('.message');
                messages.forEach(msg => {
                    msg.style.opacity = '0';
                    msg.style.transition = 'opacity 1s';
                    setTimeout(() => msg.remove(), 1000);
                });
            }, 5000);
        };

        // Confirm delete
        function confirmDelete() {
            return confirm("Are you sure you want to delete this offer?");
        }
    </script>
</body>

</html>

<?php
// Close connection
if (isset($conn)) {
    $conn->close();
}
?>