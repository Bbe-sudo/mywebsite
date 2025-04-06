<?php
session_start();
// ... (Your session and patient_id handling code)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Patient Symptoms</title>
    <link rel="stylesheet" href="patient_symptoms.css">
</head>
<body>
    <div class="symptom-container">
        <h2>Log Patient Symptoms</h2>
        <form id="symptomForm" action="process_patient_symptoms.php" method="POST" class="symptom-form">
            <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($patient_id); ?>">
            <div class="form-group">
                <label for="symptom_date_time">Date and Time:</label>
                <input type="datetime-local" id="symptom_date_time" name="symptom_date_time" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="severity">Severity:</label>
                <input type="text" id="severity" name="severity">
            </div>
            <div class="form-group">
                <label for="notes">Notes:</label>
                <textarea id="notes" name="notes"></textarea>
            </div>
            <div class="form-group">
    <label for="doctor_id">Doctor ID:</label>
    <input type="number" id="doctor_id" name="doctor_id">
</div>
            <button type="submit">Log Symptoms</button>
            <p><a href="index.php">Back to Dashboard</a></p>
        </form>
    </div>

    <div id="popup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border: 1px solid #ccc; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <p id="popupMessage"></p>
        <button id="closePopup">Close</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('symptomForm');
            const popup = document.getElementById('popup');
            const popupMessage = document.getElementById('popupMessage');
            const closePopup = document.getElementById('closePopup');

            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                const formData = new FormData(form);

                fetch('process_patient_symptoms.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    popupMessage.textContent = data.message;
                    popup.style.display = 'block';
                })
                .catch(error => {
                    popupMessage.textContent = 'An error occurred.';
                    popup.style.display = 'block';
                });
            });

            closePopup.addEventListener('click', function() {
                popup.style.display = 'none';
            });
        });
    </script>
</body>
</html>