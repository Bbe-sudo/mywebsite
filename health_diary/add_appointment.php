<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_config.php';

$doctor_id = $_SESSION['doctor_id'];

// Fetch patients for the dropdown
$sql_patients = "SELECT patient_id, full_name FROM patients";
$result_patients = $conn->query($sql_patients);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Appointment</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
            background-color: #f4f4f4; /* Light background for the whole page */
            background-image: url('appointment-background.jpg'); 
    background-size: cover; /* Stretch image to fit */
    background-repeat: no-repeat; /* Prevent repeating */
    background-position: center; /* Center the image */
    height: 100vh; 
        }

        .appointment-form-container h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .appointment-form {
            background-color: #fff; /* White background for the form */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
            max-width: 500px;
            margin: 20px auto;
        }

        .appointment-form label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }

        .appointment-form select,
        .appointment-form input[type="datetime-local"] {
            width: calc(100% - 22px); /* Adjust width for padding and border */
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box; /* Include padding and border in the element's total width and height */
            font-size: 16px;
        }

        .appointment-form button[type="submit"] {
            background-color: #007bff; /* Blue submit button */
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%; /* Make the button full width */
        }

        .appointment-form button[type="submit"]:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .appointment-form {
                padding: 15px;
            }

            .appointment-form select,
            .appointment-form input[type="datetime-local"],
            .appointment-form button[type="submit"] {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="appointment-form-container">
        <h2>Add Appointment</h2>
        <form action="process_appointment.php" method="POST" class="appointment-form">
            <div class="form-group">
                <label for="patient_id">Patient:</label>
                <select id="patient_id" name="patient_id" required>
                    <?php while ($row = $result_patients->fetch_assoc()): ?>
                        <option value="<?php echo $row['patient_id']; ?>"><?php echo $row['full_name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="appointment_datetime">Date and Time:</label>
                <input type="datetime-local" id="appointment_date_time" name="appointment_date_time" required>
                <label for="reason">Reason for Appointment:</label><br>
                <textarea id="reason" name="reason" rows="4" cols="50"></textarea><br><br>
            </div>
            <button type="submit">Add Appointment</button>
        </form>
    </div>
</body>
</html>