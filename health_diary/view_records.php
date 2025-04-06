<?php 
session_start();
include 'db_config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Medical History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
            padding: 20px;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 50%;
            margin: auto;
        }

        .search-form {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-form input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px;
        }

        .search-form button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px;
        }

        .search-form button:hover {
            background-color: #218838;
        }

        .patient-info {
            background-color: #f0f8ff;
            border: 1px solid #cce5ff;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: left;
        }

        .patient-info h3 {
            margin-bottom: 10px;
            color: #007bff;
        }

        .patient-info p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .no-records {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>View Medical History</h2>
    <form method="GET" action="view_records.php" class="search-form">
        <input type="text" name="patient_id" placeholder="Patient ID" required>
        <input type="text" name="doctor_id" placeholder="Doctor ID" required>
        <button type="submit">Search</button>
    </form>
</div>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['patient_id'])) {
            $patient_id = $_GET['patient_id'];
            $doctor_id = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : '';

            if (empty($patient_id)) {
                echo "<p class='no-records'>Please enter a Patient ID.</p>";
            } else {
                // ✅ Fetch patient information
                $patient_sql = "SELECT full_name, date_of_birth, gender, contact_info, allergies FROM patients WHERE patient_id = ?";
                $patient_stmt = $conn->prepare($patient_sql);
                $patient_stmt->bind_param("i", $patient_id);
                $patient_stmt->execute();
                $patient_result = $patient_stmt->get_result();

                if ($patient_result->num_rows > 0) {
                    $patient = $patient_result->fetch_assoc();
                    echo "<div class='patient-info'>
                            <h3>Patient Information</h3>
                            <p><strong>Name:</strong> {$patient['full_name']}</p>
                            <p><strong>Date of Birth:</strong> {$patient['date_of_birth']}</p>
                            <p><strong>Gender:</strong> {$patient['gender']}</p>
                            <p><strong>Contact:</strong> {$patient['contact_info']}</p>
                            <p><strong>Allergies:</strong> " . (!empty($patient['allergies']) ? $patient['allergies'] : "None") . "</p>
                          </div>";
                }

                // ✅ Fetch medical records
                $query = "SELECT * FROM medical_records WHERE patient_id = ?";
                $params = [$patient_id];
                $types = "i";

                if (!empty($doctor_id)) {
                    $query .= " AND doctor_id = ?";
                    $params[] = $doctor_id;
                    $types .= "i";
                }

                $stmt = $conn->prepare($query);
                if ($stmt === false) {
                    echo "<p class='no-records'>Database error: " . $conn->error . "</p>";
                    exit();
                }

                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo "<table>
                            <tr>
                                <th>Record ID</th>
                                <th>Patient ID</th>
                                <th>Doctor ID</th>
                                <th>Diagnosis</th>
                                <th>Treatment</th>
                                <th>Record Date</th>
                            </tr>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['record_id']}</td>
                                <td>{$row['patient_id']}</td>
                                <td>{$row['doctor_id']}</td>
                                <td>{$row['diagnosis']}</td>
                                <td>{$row['treatment']}</td>
                                <td>{$row['record_date']}</td>
                              </tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p class='no-records'>No records found.</p>";
                }

                $stmt->close();
                $patient_stmt->close();
            }
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
