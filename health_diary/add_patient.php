<?php
session_start();

// Include database configuration
include 'db_config.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Patient</title>
    <link rel="stylesheet" href="add_patient.css">
</head>
<body>

    <form action="process_add_patient.php" method="POST" class="add-patient-form">

        <div class="form-group">
            <label for="patient_id">Patient ID:</label>
            <input type="text" id="patient_id" name="patient_id" required>
        </div>

        <div class="form-group">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" required>
        </div>

        <div class="form-group">
            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth">
        </div>

        <div class="form-group">
            <label for="gender">Gender:</label>
            <select id="gender" name="gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <textarea id="address" name="address"></textarea>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email">
        </div>

        <div class="form-group">
            <label for="contact">Contact Information:</label>
            <input type="text" id="contact" name="contact_info" required>
        </div>

        <div class="form-group">
            <label for="allergies">Allergies (if any):</label>
            <textarea id="allergies" name="allergies"></textarea>
        </div>

        <button type="submit">Add Patient</button>
    </form>

    <a href="index.php">Back to Dashboard</a>

</body>
</html>
<?php
// Close the database connection (ensure $conn is defined if needed elsewhere)
if (isset($conn)) {
    $conn->close();
}
?>