<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_config.php';

$doctor_id = $_SESSION['doctor_id']; // Get the logged-in doctor's ID

// Fetch patient list for the dropdown
$patient_result = $conn->query("SELECT patient_id, full_name FROM patients ORDER BY full_name");
$patients = $patient_result->fetch_all(MYSQLI_ASSOC);

$labResults = [];
$patientName = '';
$message = ''; // To display success or error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['view_results'])) {
        $patient_id = $_POST['patient_id'];

        if (!empty($patient_id)) {
            // Fetch lab results for the selected patient
            $stmt = $conn->prepare("SELECT lr.test_name, lr.result, lr.test_date, lr.reference_range, d.full_name AS doctor_name
                                    FROM lab_results lr
                                    JOIN doctors d ON lr.doctor_id = d.doctor_id
                                    WHERE lr.patient_id = ?
                                    ORDER BY lr.test_date DESC");
            $stmt->bind_param("i", $patient_id);
            $stmt->execute();
            $labResults = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            // Fetch patient's full name for display
            $stmt_patient = $conn->prepare("SELECT full_name FROM patients WHERE patient_id = ?");
            $stmt_patient->bind_param("i", $patient_id);
            $stmt_patient->execute();
            $patient_name_result = $stmt_patient->get_result()->fetch_assoc();
            if ($patient_name_result) {
                $patientName = htmlspecialchars($patient_name_result['full_name']);
            }
            $stmt_patient->close();
        } else {
            $message = "<p class='error'>Please select a patient to view results.</p>";
        }
    } elseif (isset($_POST['add_result'])) {
        $patient_id = $_POST['patient_id_add']; // Hidden field for adding
        $test_name = $_POST['test_name'];
        $test_result = $_POST['test_result'];
        $reference_range = $_POST['reference_range'];
        $test_date = $_POST['test_date'];

        if (!empty($patient_id) && !empty($test_name) && !empty($test_result) && !empty($test_date)) {
            $stmt_insert = $conn->prepare("INSERT INTO lab_results (patient_id, doctor_id, test_name, result, reference_range, test_date) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("isssss", $patient_id, $doctor_id, $test_name, $test_result, $reference_range, $test_date);

            if ($stmt_insert->execute()) {
                $message = "<p class='success'>Lab result added successfully for patient ID: " . htmlspecialchars($patient_id) . "</p>";
            } else {
                $message = "<p class='error'>Error adding lab result: " . $stmt_insert->error . "</p>";
            }
            $stmt_insert->close();
        } else {
            $message = "<p class='error'>Please fill in all the lab result details.</p>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Results</title>
    <link rel="stylesheet" href="lab_results.css">

</head>
<body>
    <div class="container">
        <h2>View Lab Results</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="patient_id">Select Patient:</label>
            <select id="patient_id" name="patient_id">
                <option value="">-- Select Patient --</option>
                <?php foreach ($patients as $patient): ?>
                    <option value="<?php echo $patient['patient_id']; ?>">
                        <?php echo htmlspecialchars($patient['full_name']) . " (ID: " . $patient['patient_id'] . ")"; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="view_results">View Results</button>
        </form>

        <?php echo $message; // Display success or error messages ?>

        <?php if (!empty($patientName)): ?>
            <h3>Lab Results for: <?php echo $patientName; ?></h3>
            <?php if (!empty($labResults)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Test Name</th>
                            <th>Result</th>
                            <th>Reference Range</th>
                            <th>Test Date</th>
                            <th>Reported By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($labResults as $result): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($result['test_name']); ?></td>
                                <td><?php echo htmlspecialchars($result['result']); ?></td>
                                <td><?php echo htmlspecialchars($result['reference_range']); ?></td>
                                <td><?php echo htmlspecialchars($result['test_date']); ?></td>
                                <td><?php echo htmlspecialchars($result['doctor_name']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-results">No lab results found for this patient.</p>
            <?php endif; ?>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['view_results'])): ?>
            <p class="error">Please select a patient.</p>
        <?php endif; ?>

        <div class="add-lab-result">
            <div class="section-title">Add New Lab Result (Optional)</div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="patient_id_add" value="<?php echo isset($_POST['patient_id']) ? htmlspecialchars($_POST['patient_id']) : ''; ?>">

                <label for="test_name">Lab Test Name:</label>
                <input type="text" id="test_name" name="test_name"><br>

                <label for="test_result">Test Result:</label>
                <input type="text" id="test_result" name="test_result"><br>

                <label for="reference_range">Reference Range:</label>
                <input type="text" id="reference_range" name="reference_range"><br>

                <label for="test_date">Test Date:</label>
                <input type="date" id="test_date" name="test_date"><br>

                <button type="submit" name="add_result">Add Lab Result</button>
            </form>
        </div>

        <div class="dashboard-link">
            <a href="index.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>