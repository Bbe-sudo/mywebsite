<?php
session_start();
if (!isset($_SESSION['patient_user_id'])) {
    header("Location: login1.php");
    exit();
}

// You can fetch patient data here using $_SESSION['patient_user_id']

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
</head>
<body>
    <h2>Welcome, Patient!</h2>
    <a href="logout1.php">Logout</a>
</body>
</html>