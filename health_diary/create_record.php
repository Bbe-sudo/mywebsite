<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];
    $record_date = $_POST['record_date']; // Will now contain date and time
    $doctor_id = $_SESSION['doctor_id'];

    // Lab test data
    $test_name = $_POST['test_name'];
    $test_result = $_POST['test_result'];
    $reference_range = $_POST['reference_range'];
    $test_date = $_POST['test_date'];

    // Check if any required fields are empty
    if (empty($patient_id) || empty($diagnosis) || empty($treatment) || empty($record_date)) {
        echo "<p style='color: red;'>All fields for the medical record are required.</p>";
    } else {
        // Insert medical record data
        $sql_medical_record = "INSERT INTO medical_records (patient_id, diagnosis, treatment, record_date, doctor_id)
                                    VALUES ('$patient_id', '$diagnosis', '$treatment', '$record_date', '$doctor_id')";

        if ($conn->query($sql_medical_record) === TRUE) {
            echo "<p style='color: green;'>Medical record created successfully!</p>";

            // Insert lab result data (if available)
            if (!empty($test_name) && !empty($test_result)) {
                $sql_lab_result = "INSERT INTO lab_results (patient_id, doctor_id, test_name, result, reference_range, test_date)
                                           VALUES ('$patient_id', '$doctor_id', '$test_name', '$test_result', '$reference_range', '$test_date')";

                if ($conn->query($sql_lab_result) === TRUE) {
                    echo "<p style='color: green;'>Lab result added successfully!</p>";
                } else {
                    echo "<p style='color: red;'>Error adding lab result: " . $conn->error . "</p>";
                }
            }

        } else {
            echo "<p style='color: red;'>Error creating medical record: " . $conn->error . "</p>";
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
    <title>Create Medical Record</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #dfe9f3, #ffffff);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
    padding: 20px;
}

.container {
    background: rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 500px;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.4);
}

h2 {
    color: #333;
    font-size: 24px;
    margin-bottom: 20px;
    font-weight: 600;
}

/* Section Titles */
.section-title {
    font-size: 18px;
    color: #444;
    margin-top: 30px;
    margin-bottom: 10px;
    border-bottom: 1px solid #ccc;
    padding-bottom: 5px;
    text-align: left;
}

/* Labels */
label {
    display: block;
    text-align: left;
    color: #444;
    font-weight: 500;
    margin-bottom: 5px;
}

/* Inputs and Textareas */
input[type="text"],
input[type="datetime-local"], /* Changed input type here */
textarea,
input[type="date"] /* Keep this for lab test date */
{
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: none;
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.7);
    color: #333;
    font-size: 16px;
    outline: none;
    box-shadow: inset 2px 2px 5px rgba(0, 0, 0, 0.1);
    transition: 0.3s ease;
    box-sizing: border-box; /* Ensure padding and border are included in the width */
}

input:focus,
textarea:focus {
    background: rgba(255, 255, 255, 1);
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
}

/* Submit Button */
input[type="submit"] {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    padding: 14px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 18px;
    font-weight: bold;
    transition: 0.3s ease-in-out;
    width: 100%;
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
}

input[type="submit"]:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 12px rgba(0, 123, 255, 0.4);
}

/* Back Link */
a {
    display: inline-block;
    margin-top: 15px;
    text-decoration: none;
    color: #007bff;
    font-weight: 600;
    transition: 0.3s;
}

a:hover {
    color: #0056b3;
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 600px) {
    .container {
        width: 90%;
        padding: 20px;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Create Medical Record</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="patient_id">Patient ID:</label>
            <input type="text" id="patient_id" name="patient_id" required><br>

            <label for="diagnosis">Diagnosis:</label>
            <textarea id="diagnosis" name="diagnosis" required></textarea><br>

            <label for="treatment">Treatment:</label>
            <textarea id="treatment" name="treatment" required></textarea><br>

            <label for="record_date">Record Date and Time:</label>
            <input type="datetime-local" id="record_date" name="record_date" required><br>

            <input type="submit" value="Create Record">
        </form>
        <a href="index.php">Back to Dashboard</a>
    </div>
</body>
</html>