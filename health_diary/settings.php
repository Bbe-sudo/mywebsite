<?php
session_start();

// Save preferences on form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['theme'] = $_POST['theme'];
    $_SESSION['language'] = $_POST['language'];
    $_SESSION['notifications'] = $_POST['notifications'];
    $_SESSION['user_type'] = $_POST['user_type'];
    $_SESSION['date_format'] = $_POST['date_format'];
    $_SESSION['time_format'] = $_POST['time_format'];
    $_SESSION['reminder_email'] = $_POST['reminder_email'];
    $_SESSION['reminder_sms'] = $_POST['reminder_sms'];
    $_SESSION['brightness'] = $_POST['brightness'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Preferences</title>
    <link rel="stylesheet" href="settings.css">
    <style>
        /* Layout for label and input */
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            gap: 15px;
            text-align: left;
        }
        .form-group label {
            flex-basis: 150px;
            font-weight: 500;
            color: #444;
            text-align: left;
        }
        .form-group select,
        .form-group input[type="checkbox"],
        .form-group input[type="range"] {
            flex-grow: 1;
            width: auto;
        }

        /* Layout for Date and Time Format side-by-side */
        .datetime-group {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .datetime-group > div {
            flex: 1; /* Distribute space equally between Date and Time */
        }
        .datetime-group label {
            display: block; /* Ensure label is above the select in this context */
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Preferences</h2>
        <form action="settings.php" method="POST">
            <div class="form-group">
                <label for="theme">Choose Theme:</label>
                <select id="theme" name="theme">
                    <option value="light" <?php if (isset($_SESSION['theme']) && $_SESSION['theme'] == 'light') echo 'selected'; ?>>Light</option>
                    <option value="dark" <?php if (isset($_SESSION['theme']) && $_SESSION['theme'] == 'dark') echo 'selected'; ?>>Dark</option>
                </select>
            </div>

            <div class="form-group">
                <label for="language">Language:</label>
                <select id="language" name="language">
                    <option value="en" <?php if (isset($_SESSION['language']) && $_SESSION['language'] == 'en') echo 'selected'; ?>>English</option>
                    <option value="es" <?php if (isset($_SESSION['language']) && $_SESSION['language'] == 'es') echo 'selected'; ?>>Spanish</option>
                </select>
            </div>

            <div class="form-group">
                <label>Date and Time Format:</label>
                <div class="datetime-group">
                    <div>
                        <label for="date_format">Date Format:</label>
                        <select id="date_format" name="date_format">
                            <option value="mm-dd-yyyy" <?php if (isset($_SESSION['date_format']) && $_SESSION['date_format'] == 'mm-dd-yyyy') echo 'selected'; ?>>MM/DD/YYYY</option>
                            <option value="yyyy-mm-dd" <?php if (isset($_SESSION['date_format']) && $_SESSION['date_format'] == 'yyyy-mm-dd') echo 'selected'; ?>>YYYY-MM-DD</option>
                        </select>
                    </div>
                    <div>
                        <label for="time_format">Time Format:</label>
                        <select id="time_format" name="time_format">
                            <option value="12-hour" <?php if (isset($_SESSION['time_format']) && $_SESSION['time_format'] == '12-hour') echo 'selected'; ?>>12-Hour</option>
                            <option value="24-hour" <?php if (isset($_SESSION['time_format']) && $_SESSION['time_format'] == '24-hour') echo 'selected'; ?>>24-Hour</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label for="reminder_email">Reminder Email:</label>
                <input type="checkbox" id="reminder_email" name="reminder_email" <?php if (isset($_SESSION['reminder_email']) && $_SESSION['reminder_email'] == 'on') echo 'checked'; ?>>
            </div>

            <div class="form-group">
                <label for="reminder_sms">Reminder SMS:</label>
                <input type="checkbox" id="reminder_sms" name="reminder_sms" <?php if (isset($_SESSION['reminder_sms']) && $_SESSION['reminder_sms'] == 'on') echo 'checked'; ?>>
            </div>

            <div class="form-group">
                <label for="notifications">Notifications:</label>
                <input type="checkbox" id="notifications" name="notifications" <?php if (isset($_SESSION['notifications']) && $_SESSION['notifications'] == 'on') echo 'checked'; ?>>
            </div>
            
            <input type="submit" value="Save Preferences">
        </form>

        <p style="text-align: center; margin-top: 20px;"><a href="index.php">Back to Dashboard</a></p>
    </div>
</body>
</html>