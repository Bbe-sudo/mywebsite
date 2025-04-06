<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_config.php'; // Include your database connection

$doctor_id = $_SESSION['doctor_id'];
$patient_id = $_POST['patient_id'];
$diagnosis = $_POST['diagnosis'];
$treatment = $_POST['treatment'];

$sql = "INSERT INTO medical_records (doctor_id, patient_id, diagnosis, treatment) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $doctor_id, $patient_id, $diagnosis, $treatment);

if ($stmt->execute()) {
    echo "Medical record added successfully!";
    // Optionally redirect to a success page
} else {
    echo "Error adding record: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>