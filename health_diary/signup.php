<?php
session_start();
include 'db_config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);

    // Common check for existing email
    $check_email_query = "SELECT * FROM " . $user_type . "s WHERE email = ?";
    $stmt_check_email = $conn->prepare($check_email_query);
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $result = $stmt_check_email->get_result();

    if ($result->num_rows > 0) {
        $error = "This email is already registered as a " . $user_type . ".";
    } else {
        $insert_query = "";
        $stmt = null;

        if ($user_type === 'doctor') {
            $specialization = mysqli_real_escape_string($conn, $_POST['specialization']);
            $gender = mysqli_real_escape_string($conn, $_POST['gender']);
            $insert_query = "INSERT INTO doctors (username, password, email, full_name, specialization, gender) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ssssss", $username, $password, $email, $full_name, $specialization, $gender);
        } elseif ($user_type === 'patient') {
            $dob = mysqli_real_escape_string($conn, $_POST['dob']);
            $insert_query = "INSERT INTO patients (username, password, email, full_name, dob) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("sssss", $username, $password, $email, $full_name, $dob);
        } elseif ($user_type === 'admin') {
            $insert_query = "INSERT INTO admins (username, password, email, full_name) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ssss", $username, $password, $email, $full_name);
        }

        if ($stmt && $stmt->execute()) {
            header("Location: login.php?signup=success");
            exit();
        } else {
            $error = "Signup failed. Please try again.";
            if ($stmt) {
                $error .= " Error: " . $stmt->error;
            }
        }

        if ($stmt_check_email) $stmt_check_email->close();
        if ($stmt) $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="signup.css">
    <style>
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="password"], input[type="email"], input[type="date"], select {
            width: calc(100% - 12px); padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
        }
        button { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .error { color: red; margin-top: 10px; }

        .password-toggle-container {
            position: relative;
        }

        .password-toggle-container input[type="password"] {
            padding-right: 40px; /* Make space for the eye icon */
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            user-select: none; /* Prevent text selection on click */
            color: #777;
            font-size: 18px;
        }

        .toggle-password:hover {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Signup</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="signup.php" method="POST">
            <div class="form-group">
                <label for="user_type">Signup as:</label>
                <select name="user_type" id="user_type" required>
                    <option value="">-- Select User Type --</option>
                    <option value="doctor">Doctor</option>
                    <option value="patient">Patient</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group password-toggle-container">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <span class="toggle-password" onclick="togglePasswordVisibility()">
                    &#128065; </span>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" name="full_name" required>
            </div>

            <div id="doctor_fields" style="display: none;">
                <div class="form-group">
                    <label for="specialization">Specialization:</label>
                    <input type="text" name="specialization">
                </div>
                <div class="form-group">
                    <label>Gender:</label><br>
                    <input type="radio" id="male" name="gender" value="male">
                    <label for="male">Male</label><br>
                    <input type="radio" id="female" name="gender" value="female">
                    <label for="female">Female</label><br>
                </div>
            </div>

            <div id="patient_fields" style="display: none;">
                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" name="dob">
                </div>
            </div>

            <button type="submit">Signup</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>

    <script>
        const userTypeSelect = document.getElementById('user_type');
        const doctorFields = document.getElementById('doctor_fields');
        const patientFields = document.getElementById('patient_fields');

        function setRequiredFields(container, isRequired) {
            container.querySelectorAll('input:not([type="radio"]), select').forEach(el => {
                if (isRequired) {
                    el.setAttribute('required', 'required');
                } else {
                    el.removeAttribute('required');
                }
            });
            container.querySelectorAll('input[type="radio"]').forEach(radioGroup => {
                const name = radioGroup.getAttribute('name');
                const atLeastOneChecked = container.querySelector(`input[type="radio"][name="${name}"]:checked`);
                const allRadiosInGroup = Array.from(container.querySelectorAll(`input[type="radio"][name="${name}"]`));

                if (isRequired && !atLeastOneChecked && allRadiosInGroup.length > 0) {
                    allRadiosInGroup.forEach(radio => radio.setAttribute('required', 'required'));
                } else if (!isRequired) {
                    allRadiosInGroup.forEach(radio => radio.removeAttribute('required'));
                }
            });
        }

        userTypeSelect.addEventListener('change', function() {
            doctorFields.style.display = 'none';
            patientFields.style.display = 'none';

            setRequiredFields(doctorFields, false);
            setRequiredFields(patientFields, false);

            if (this.value === 'doctor') {
                doctorFields.style.display = 'block';
                setRequiredFields(doctorFields, true);
            } else if (this.value === 'patient') {
                patientFields.style.display = 'block';
                setRequiredFields(patientFields, true);
            }
            // Admin fields are basic and always visible, so no special handling here for display
        });

        // Initial state on page load
        doctorFields.style.display = 'none';
        patientFields.style.display = 'none';

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById("password");
            const toggleIcon = document.querySelector(".toggle-password");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.innerHTML = "&#128065;"; // You can change this to a closed eye icon if desired
            } else {
                passwordInput.type = "password";
                toggleIcon.innerHTML = "&#128065;"; // You can change this back to an open eye icon if desired
            }
        }
    </script>
</body>
</html>