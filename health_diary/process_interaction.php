<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = $_SESSION['doctor_id'];
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $interaction_datetime = $_POST['interaction_datetime'];
    $interaction_type = mysqli_real_escape_string($conn, $_POST['interaction_type']);
    $interaction_notes = mysqli_real_escape_string($conn, $_POST['interaction_notes']);

    $sql = "INSERT INTO interactions (doctor_id, patient_id, interaction_datetime, interaction_type, interaction_notes) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $doctor_id, $patient_id, $interaction_datetime, $interaction_type, $interaction_notes); // Corrected here

    if ($stmt->execute()) {
        header("Location: index.php?message=Interaction recorded successfully");
        exit();
    } else {
        header("Location: record_interaction.php?error=Error recording interaction");
        exit();
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: record_interaction.php");
    exit();
}
?>