<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $appointment_datetime = $_POST['appointment_date_time'];
    $reason = $_POST['reason'];
    $doctor_id = $_SESSION['doctor_id']; // Get doctor_id from session

    if (empty($patient_id) || empty($appointment_datetime) || empty($reason)) {
        echo "All fields are required.";
    } else {
        $patient_id = mysqli_real_escape_string($conn, $patient_id);
        $appointment_datetime = mysqli_real_escape_string($conn, $appointment_datetime);
        $reason = mysqli_real_escape_string($conn, $reason);
        $doctor_id = mysqli_real_escape_string($conn, $doctor_id); //Sanitize doctor id.

        $sql = "INSERT INTO appointments (patient_id, appointment_date_time, reason, doctor_id) VALUES ('$patient_id', '$appointment_datetime', '$reason', '$doctor_id')"; //Added doctor_id

        if ($conn->query($sql) === TRUE) {
            echo "New appointment created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>