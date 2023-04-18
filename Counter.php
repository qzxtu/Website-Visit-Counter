<?php
// Start session
session_start();

// Check if the visit cookie is set
if (!isset($_COOKIE['visit'])) {
    // If it's not set, create a new cookie and increment the counter
    setcookie('visit', 'true', time() + 3600*24*365); // Valid for 1 year
    $_SESSION['counter']++;
}

// Function to get the visitor's IP address
function getIPAddress() {
    $ip = $_SERVER['REMOTE_ADDR'];
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return $ip;
}

// Check if the IP address is already in the database
function checkIPAddress($ip) {
    $conn = mysqli_connect('localhost', 'username', 'password', 'database_name');
    $result = mysqli_query($conn, "SELECT * FROM visits WHERE ip = '$ip'");
    mysqli_close($conn);
    return mysqli_num_rows($result);
}

// Function to insert a new visit in the database
function insertVisit($ip) {
    $conn = mysqli_connect('localhost', 'username', 'password', 'database_name');
    mysqli_query($conn, "INSERT INTO visits (ip) VALUES ('$ip')");
    mysqli_close($conn);
}

// Get the visitor's IP address
$ip = getIPAddress();

// Check if the IP address is already in the database
if (!checkIPAddress($ip)) {
    // If it's not in the database, insert a new visit
    insertVisit($ip);
    $_SESSION['visits']++;
}

// Display the visit counter
echo "Total visits: " . $_SESSION['visits'];
?>