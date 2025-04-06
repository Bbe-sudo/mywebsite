<?php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $full_name = $_POST['full_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $contact_info = $_POST['contact_info'];
    $allergies = $_POST['allergies'];

    // Prepare the INSERT statement
    $sql = "INSERT INTO patients (patient_id, full_name, date_of_birth, gender, address, email, contact_info, allergies) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("isssssss", $patient_id, $full_name, $date_of_birth, $gender, $address, $email, $contact_info, $allergies);

    if ($stmt->execute()) {
        echo "Patient added successfully. <br>";
        // Provide the link to go back to the dashboard
        echo '<a href="index.php">Back to Dashboard</a>';
    } else {
        echo "Error adding patient: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
