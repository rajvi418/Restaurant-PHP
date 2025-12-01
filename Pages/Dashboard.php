<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../Bootstrap/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="../Bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="../Bootstrap/dist/css/bootstrap-utilities.css">
    <link rel="stylesheet" href="../CSS/font.css">
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<style>
    * {
        font-family: 'Outfit';

    }
</style>

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
        <section class="content-container ">
            <div class="dashboard-welcome">
                <h1>Welcome to the Dashboard</h1>
                <p>Here you can manage your restaurant's operations efficiently.</p>
            </div>
            <div class="dashboard-cards">
                <div class="card d-flex ">
                    <div class="d-flex flex-column">
                        <h2>Total Orders</h2>
                        <p>150</p>
                        <span class="update-detail mt-5 mt-5">10% Orders increases this month</span>
                    </div>
                </div>

                <div class="card">
                    <h2>New Customers</h2>
                    <p>30</p>
                    <span class="update-detail mt-5">New 3 customers increases this month</span>
                </div>
                <div class="card revenue">
                    <h2>Total Revenue</h2>
                    <p>₹50,000</p>
                    <span class="update-detail mt-5">Revenue increases this month</span>
                </div>
            </div>
            <div class="dashboard-cards">
                <div class="card">
                    <h2>Pending Orders</h2>
                    <p>5</p>
                    <span class="update-detail mt-5">3 customers completed orders this month</span>
                </div>
                <div class="card">
                    <h2>Staff Online</h2>
                    <p>10</p>
                    <span class="update-detail mt-5">2 Staff online leave this month</span>
                </div>
                <div class="card">
                    <h2>Feedback Received</h2>
                    <p>25</p>
                    <span class="update-detail mt-5">5% Feedback received increases from Customers</span>
                </div>
            </div>
            <div class="dashboard-cards">
                <div class="profit-card">
                    <h2>Profit</h2>
                    <p>₹1,45,200</p>
                    <span class="update-detail mt-5">This month</span>
                </div>
                <div class="profit-card">
                    <h2>Average Daily Sales</h2>
                    <p>₹5K+</p>
                    <span class="update-detail mt-5">Increases by 3.15%</span>
                </div>
            </div>
            </div>
            <footer class="dashboard-footer">
                <p>&copy; 2024 Restaurant Management System. All rights reserved.</p>
            </footer>
        </section>

    </section>
</body>
<link rel="stylesheet" href="../Bootstrap/dist/js/bootstrap.bundle.min.js.map">

</html>