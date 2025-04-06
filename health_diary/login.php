<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc); /* Modern gradient background */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white card */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 450px;
            backdrop-filter: blur(10px); /* Subtle blur effect */
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #444;
            font-weight: 500;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: calc(100% - 24px);
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        select:focus {
            border-color: #6a11cb;
            outline: none;
            box-shadow: 0 0 5px rgba(106, 17, 203, 0.5); /* Subtle focus shadow */
        }

        button {
            background-color: #6a11cb;
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 8px rgba(106, 17, 203, 0.3);
        }

        button:hover {
            background-color: #550fb2;
        }

        p {
            text-align: center;
            margin-top: 25px;
            color: #666;
        }

        a {
            color: #6a11cb;
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 500;
        }

        a:hover {
            color: #550fb2;
            text-decoration: underline;
        }

        .error {
            color: #e53935;
            text-align: center;
            margin-bottom: 20px;
        }

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
    <div class="login-container">
        <form action="process_login.php" method="POST" class="login-form">
            <h2>Login</h2>
            <?php if (isset($_GET['error'])) { ?>
                <p class="error"><?php echo $_GET['error']; ?></p>
            <?php } ?>
            <div class="form-group">
                <label for="user_type">Login as:</label>
                <select name="user_type" id="user_type" required>
                    <option value="">-- Select User Type --</option>
                    <option value="doctor">Doctor</option>
                    <option value="patient">Patient</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group password-toggle-container">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <span class="toggle-password" onclick="togglePasswordVisibility()">
                    &#128065; </span>
            </div>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Signup</a></p>
    </div>

    <script>
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