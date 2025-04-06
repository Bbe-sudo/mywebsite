<?php
session_start();
include 'db_config.php'; // Ensure database connection is included
header('Content-Type: application/json'); // Set response type to JSON

// Check if the user is logged in
if (!isset($_SESSION['doctor_id'])) {
    echo json_encode(['message' => 'Session expired. Please log in again.']);
    exit();
}

if (!$conn) {
    echo json_encode(['message' => 'Database connection error.']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate required fields
    if (empty($_POST['patient_id']) || empty($_POST['symptom_date_time']) || empty($_POST['description'])) {
        echo json_encode(['message' => 'Required fields are missing.']);
        exit();
    }

    // Sanitize inputs
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $symptom_date_time = $_POST['symptom_date_time'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $severity = isset($_POST['severity']) ? mysqli_real_escape_string($conn, $_POST['severity']) : NULL;
    $notes = isset($_POST['notes']) ? mysqli_real_escape_string($conn, $_POST['notes']) : NULL;
    $doctor_id = $_SESSION['doctor_id']; // Get doctor ID from session

    // Prepare SQL statement
    $sql = "INSERT INTO symptoms (patient_id, doctor_id, symptom_date_time, description, severity, notes) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['message' => 'Database error: ' . $conn->error]);
        exit();
    }

    $stmt->bind_param("iissss", $patient_id, $doctor_id, $symptom_date_time, $description, $severity, $notes);

    // Execute query and send response
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Symptoms logged successfully.']);
    } else {
        echo json_encode(['message' => 'Error logging symptoms: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['message' => 'Invalid request method.']);
}

$conn->close();
?>
