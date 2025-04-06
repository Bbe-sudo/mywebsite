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
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Patient Details</title>
            <link rel="stylesheet" href="patient_search.css">
        </head>
        <body>
            <div class="patient-details">
                <h2>Patient Details</h2>
                <p><strong>Patient ID:</strong> <?php echo htmlspecialchars($patient['patient_id']); ?></p>
                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($patient['full_name']); ?></p>
                <a href='patient_symptoms.php?patient_id=<?php echo urlencode($patient['patient_id']); ?>'>Log Symptoms</a>
            </div>
        </body>
        </html>
        <?php
    } else {
        header("Location: index.php?error=Patient not found");
        exit();
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php");
    exit();
}
?>