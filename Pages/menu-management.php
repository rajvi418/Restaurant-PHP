<?php
// --- Connect to the database ---
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restraurant_db"; // 🔹 Replace with your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Fetch all items from database ---
$sql = "SELECT * FROM items";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Restaurant Menu</title>
    <link rel="stylesheet" href="../CSS/font.css">
    <link rel="stylesheet" href="../CSS/style.css">
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
            <h3 class="title">Restaurant Menu</h3>
            <a href="additems.php"><button class="universal-btn">+ Add Item </button></a>
        </div>
        <table class="menu-management">
            <tr class="table-header">
                <th>ID</th>
                <th>Name</th>
                <!-- <th>Description</th> -->
                <th>Price</th>
                <!-- <th>Category</th> -->
                <th>Details</th>
            </tr>
            <!-- <?php
            // --- Display each row ---
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr class='table-data'>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td>$" . number_format($row['price'], 2) . "</td>";
                    echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                    echo "<td>
                            <a href='detail_item.php?id=" . $row['id'] . "' class='btn btn-warning'>Edit</a>
                            <a href='delete_item.php?id=" . $row['id'] . "' class='btn btn-danger' 
                               onclick='return confirm(\"Are you sure you want to delete this item?\")'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No menu items found.</td></tr>";
            }

            $conn->close();
            ?> -->

            <tr class="table-data">
                <td>1</td>
                <td>Margherita Pizza</td>
                <!-- <td>Classic cheese & tomato</td> -->
                <td>$8.00</td>
                <!-- <td>Pizza</td> -->
                <td class="detail-container">
                    <div class="center">
                        <button class="universal-btn" id="open-popup">Details</button>
                    </div>
                    <div class="popup" id="popup">
                        <div class="overlay"></div>
                        <div class="popup-content">
                            <h4>Margherita Pizza</h4>
                            <p>Classic cheese & tomato pizza.</p>
                            <p>Price: $8.00</p>

                            <div class="controls">
                                <button class="universal-btn" id="close-btn">Close</button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="table-data">
                <td>2</td>
                <td>Spaghetti Carbonara</td>
                <!-- <td>Pasta, egg, cheese, pork.</td> -->
                <td>$9.00</td>
                <!-- <td>Pasta</td> -->
                <td class="detail-container">
                    <div class="center">
                        <button class="universal-btn" id="open-popup">Details</button>
                    </div>
                    <div class="popup" id="popup">
                        <div class="overlay"></div>
                        <div class="popup-content">
                            <h4>Margherita Pizza</h4>
                            <p>Classic cheese & tomato pizza.</p>
                            <p>Price: $8.00</p>

                            <div class="controls">
                                <button class="universal-btn" id="close-btn">Close</button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="table-data">
                <td>3</td>
                <td>Lasagna alla Bolognese.</td>
                <!-- <td>Layered pasta, meat, creamy sauce.</td> -->
                <td>$6.85</td>
                <!-- <td>Pasta</td> -->
                <td class="detail-container">
                    <div class="center">
                        <button class="universal-btn" id="open-popup">Details</button>
                    </div>
                    <div class="popup" id="popup">
                        <div class="overlay"></div>
                        <div class="popup-content">
                            <h4>Margherita Pizza</h4>
                            <p>Classic cheese & tomato pizza.</p>
                            <p>Price: $8.00</p>

                            <div class="controls">
                                <button class="universal-btn" id="close-btn">Close</button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="table-data">
                <td>4</td>
                <td>Pizza Marinara</td>
                <!-- <td>Tomato, garlic, oregano.</td> -->
                <td>$7.50</td>
                <!-- <td>Pizza</td> -->
                <td class="detail-container">
                    <div class="center">
                        <button class="universal-btn" id="open-popup">Details</button>
                    </div>
                    <div class="popup" id="popup">
                        <div class="overlay"></div>
                        <div class="popup-content">
                            <h4>Margherita Pizza</h4>
                            <p>Classic cheese & tomato pizza.</p>
                            <p>Price: $8.00</p>

                            <div class="controls">
                                <button class="universal-btn" id="close-btn">Close</button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="table-data">
                <td>5</td>
                <td>Spaghetti Aglio e Olio</td>
                <!-- <td>Pasta, garlic, olive oil..</td> -->
                <td>$8.00</td>
                <!-- <td>Main Courses</td> -->
                <td class="detail-container">
                    <div class="center">
                        <button class="universal-btn" id="open-popup">Details</button>
                    </div>
                    <div class="popup" id="popup">
                        <div class="overlay"></div>
                        <div class="popup-content">
                            <h4>Margherita Pizza</h4>
                            <p>Classic cheese & tomato pizza.</p>
                            <p>Price: $8.00</p>

                            <div class="controls">
                                <button class="universal-btn" id="close-btn">Close</button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="table-data">
                <td>6</td>
                <td>Minestrone</td>
                <!-- <td>Hearty Italian vegetable soup.</td> -->
                <td>$9.00</td>
                <!-- <td>Soups & Starters</td> -->
                <td class="detail-container">
                    <div class="center">
                        <button class="universal-btn" id="open-popup">Details</button>
                    </div>
                    <div class="popup" id="popup">
                        <div class="overlay"></div>
                        <div class="popup-content">
                            <h4>Margherita Pizza</h4>
                            <p>Classic cheese & tomato pizza.</p>
                            <p>Price: $8.00</p>

                            <div class="controls">
                                <button class="universal-btn" id="close-btn">Close</button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="table-data">
                <td>7</td>
                <td>Panna Cotta</td>
                <!-- <td>Creamy set Italian dessert. </td> -->
                <td>$7.00</td>
                <!-- <td>Desserts</td> -->
                <td class="detail-container">
                    <div class="center">
                        <button class="universal-btn" id="open-popup">Details</button>
                    </div>
                    <div class="popup" id="popup">
                        <div class="overlay"></div>
                        <div class="popup-content">
                            <h4>Margherita Pizza</h4>
                            <p>Classic cheese & tomato pizza.</p>
                            <p>Price: $8.00</p>

                            <div class="controls">
                                <button class="universal-btn" id="close-btn">Close</button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="table-data">
                <td>8</td>
                <td>Gelato</td>
                <!-- <td>Dense Italian-style ice cream.</td> -->
                <td>$6.00</td>
                <!-- <td>Desserts</td> -->
                <td class="detail-container">
                    <div class="center">
                        <button class="universal-btn" id="open-popup">Details</button>
                    </div>
                    <div class="popup" id="popup">
                        <div class="overlay"></div>
                        <div class="popup-content">
                            <h4>Margherita Pizza</h4>
                            <p>Classic cheese & tomato pizza.</p>
                            <p>Price: $8.00</p>

                            <div class="controls">
                                <button class="universal-btn" id="close-btn">Close</button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="table-data">
                <td>9</td>
                <td>Pizza Quattro Formaggi</td>
                <!-- <td>Four-cheese Italian-style pizza.</td> -->
                <td>$6.00</td>
                <!-- <td>Pizza</td> -->
                <td class="detail-container">
                    <div class="center">
                        <button class="universal-btn" id="open-popup">Details</button>
                    </div>
                    <div class="popup" id="popup">
                        <div class="overlay"></div>
                        <div class="popup-content">
                            <h4>Margherita Pizza</h4>
                            <p>Classic cheese & tomato pizza.</p>
                            <p>Price: $8.00</p>

                            <div class="controls">
                                <button class="universal-btn" id="close-btn">Close</button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="table-data">
                <td>10</td>
                <td>Caprese Salad</td>
                <!-- <td>Tomato, mozzarella, basil salad.</td> -->
                <td>$4.00</td>
                <!-- <td>Salad</td> -->
                <td class="detail-container">
                    <div class="center">
                        <button class="universal-btn" id="open-popup">Details</button>
                    </div>
                    <div class="popup" id="popup">
                        <div class="overlay"></div>
                        <div class="popup-content">
                            <h4>Margherita Pizza</h4>
                            <p>Classic cheese & tomato pizza.</p>
                            <p>Price: $8.00</p>

                            <div class="controls">
                                <button class="universal-btn" id="close-btn">Close</button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>

        </table>
        <footer class="dashboard-footer">
            <p>&copy; 2024 Restaurant Management System. All rights reserved.</p>
        </footer>
    </section>
</body>
<script src="../JavaScript/popup-fun.js"></script>

</html>