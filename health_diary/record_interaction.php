<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Interaction</title>
    <link rel="stylesheet" href="record.css">
</head>
<body>
    <div class="interaction-container">
        <h2>Record Interaction</h2>
        <form action="process_interaction.php" method="POST" class="interaction-form">
            <div class="form-group">
                <label for="patient_id">Patient ID:</label>
                <input type="text" id="patient_id" name="patient_id" required>
            </div>
            <div class="form-group">
                <label for="interaction_datetime">Date and Time:</label>
                <input type="datetime-local" id="interaction_datetime" name="interaction_datetime" required>
            </div>
            <div class="form-group">
                <label for="interaction_type">Interaction Type:</label>
                <select id="interaction_type" name="interaction_type">
                    <option value="Phone Call">Phone Call</option>
                    <option value="Email">Email</option>
                    <option value="In-Person">In-Person</option>
                </select>
            </div>
            <div class="form-group">
                <label for="interaction_notes">Notes:</label>
                <textarea id="interaction_notes" name="interaction_notes"></textarea>
            </div>
            <button type="submit">Record Interaction</button>
        </form>
    </div>
</body>
</html>