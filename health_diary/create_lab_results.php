<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_config.php';

// Fetch patient list for the dropdown
$patient_result = $conn->query("SELECT patient_id, full_name FROM patients ORDER BY full_name");
$patients = $patient_result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Lab Result</title>
    <link rel="stylesheet" href="css/create_lab_result.css">
    <style>
        body { font-family: Arial; padding: 20px; }
        form { max-width: 500px; margin: auto; }
        label { display: block; margin-top: 10px; }
        input, textarea { width: 100%; padding: 8px; margin-top: 5px; }
        button { margin-top: 15px; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create New Lab Result</h2>
        <form method="post" action="process_lab_result.php">
            <label for="patient_id">Select Patient:</label>
            <select id="patient_id" name="patient_id" required>
                <option value="">-- Select Patient --</option>
                <?php foreach ($patients as $patient): ?>
                    <option value="<?php echo $patient['patient_id']; ?>">
                        <?php echo htmlspecialchars($patient['full_name']) . " (ID: " . $patient['patient_id'] . ")"; ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="test_name">Test Name:</label>
            <input type="text" id="test_name" name="test_name" required><br><br>

            <label for="result">Result:</label>
            <input type="text" id="result" name="result" required><br><br>

            <label for="reference_range">Reference Range (Optional):</label>
            <input type="text" id="reference_range" name="reference_range"><br><br>

            <label for="test_date">Test Date:</label>
            <input type="date" id="test_date" name="test_date" required><br><br>

            <input type="submit" value="Save Lab Result">
        </form>
        <div class="dashboard-link">
            <a href="index.php">Back to Dashboard</a>
        </div>
    </div>
    <br>
<a href="patient_profile.php?id=<?= $patient_id ?>">Back to Profile</a>

</body>
</html>