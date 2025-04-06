<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_config.php';

if (isset($_GET['patient_id'])) {
    $patient_id = mysqli_real_escape_string($conn, $_GET['patient_id']);

    $sql = "SELECT * FROM patients WHERE patient_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $patient = $result->fetch_assoc();
    } else {
        echo "Patient not found.";
        exit;
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Patient ID not provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Record</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="patient-record-container">
        <h2>Patient Record</h2>
        <p><strong>Patient ID:</strong> <?php echo $patient['patient_id']; ?></p>
        <p><strong>Full Name:</strong> <?php echo $patient['full_name']; ?></p>
        <p><strong>Date of Birth:</strong> <?php echo $patient['date_of_birth']; ?></p>
        <p><strong>Gender:</strong> <?php echo $patient['gender']; ?></p>
        <p><strong>Address:</strong> <?php echo $patient['address']; ?></p>
        <p><strong>Email:</strong> <?php echo $patient['email']; ?></p>

        <?php if (isset($_GET['message']) && $_GET['message'] == "Symptoms logged successfully"): ?>
            <p>Symptoms logged successfully.</p>
            <a href="index.php">Back to Dashboard</a>
        <?php else: ?>
            <a href="patient_symptoms.php?patient_id=<?php echo $patient['patient_id']; ?>">Log Symptoms</a>
        <?php endif; ?>
    </div>
</body>
</html>