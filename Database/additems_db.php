<?php

// Database connection

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "items";
$conn = "";

// $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
// if ($conn) {
//     echo "You are Connected!";
// } else {
//     echo "You are not connected!";
// }

try {
    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
} catch (mysqli_sql_exception) {
    echo "You are Connected!";
}
