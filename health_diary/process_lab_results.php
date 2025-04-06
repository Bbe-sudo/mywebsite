<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $test_name = $_POST['test_name'];
    $result = $_POST['result'];
    $reference_range = $_POST['reference_range'];
    $test_date = $_POST['test_date'];
    $doctor_id = $_SESSION['doctor_id']; // Get the logged-in doctor's ID

    // Validate input (you might want to add more robust validation)
    if (empty($patient_id) || empty($test_name) || empty($result) || empty($test_date)) {
        echo "All required fields must be filled.";
        exit();
    }

    // Prepare and execute the SQL query to insert the new lab result
    $stmt = $conn->prepare("INSERT INTO lab_results (patient_id, doctor_id, test_name, result, reference_range, test_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $patient_id, $doctor_id, $test_name, $result, $reference_range, $test_date);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Lab result saved successfully!</p>";
        echo "<p><a href='lab_results.php'>View Lab Results</a></p>"; // Link to view page
    } else {
        echo "Error saving lab result: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // If someone tries to access this page directly without submitting the form
    header("Location: create_lab_result.php");
    exit();
}
?>