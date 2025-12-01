<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link rel="stylesheet" href="../Bootstrap/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="../Bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="../Bootstrap/dist/css/bootstrap-utilities.css">
    <link rel="stylesheet" href="../CSS/font.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <style>
        .account-content {
            width: 50%;
            margin: auto;
            margin-top: 30px;
        }

        .account-card {
            background: white;
            padding: 25px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .account-card h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 30px;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
        }

        .info-group {
            margin-bottom: 15px;
        }

        .info-label {
            font-weight: bold;
            color: #666;
            display: block;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .info-value {
            color: #333;
            font-size: 26px;
        }

        .logout-btn {
            margin-top: 20px;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <section class="dashboard-container">
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
            <div class="dashboard-welcome">
                <h1>Account Settings</h1>
                <p>Manage your account information and preferences here.</p>
            </div>

            <div class="account-content">
                <div class="account-card">
                    <h3> Personal Information</h3>
                    <div class="info-group">
                        <span class="info-label">Full Name</span>
                        <span class="info-value">Rajvi Undhad</span>
                    </div>
                    <div class="info-group">
                        <span class="info-label">Email Address</span>
                        <span class="info-value">lumiere@restaurant.com</span>
                    </div>
                    <div class="info-group">
                        <span class="info-label">Phone Number</span>
                        <span class="info-value">+91 98765 43210</span>
                    </div>
                    <div class="info-group">
                        <span class="info-label">Role</span>
                        <span class="info-value">Restaurant Manager</span>
                    </div>
                    <div>
                        <a href="../index.php">
                            <button class="universal-btn logout-btn">Logout</button>
                        </a>
                    </div>
                </div>
            </div>
            <footer class="dashboard-footer">
                <p>&copy; 2024 Restaurant Management System. All rights reserved.</p>
            </footer>
        </section>
    </section>

</body>

</html>