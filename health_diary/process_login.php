<?php
session_start();
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $table_name = $user_type . "s";
    $id_column = $user_type . "_id";
    $name_column = "full_name"; // Default name column
    $redirect_page = "index.php"; // Default doctor dashboard

    if ($user_type === 'patient') {
        $redirect_page = "patient_dashboard.php"; // You'll need to create this
        $name_column = "full_name";
    } elseif ($user_type === 'admin') {
        $redirect_page = "admin_dashboard.php"; // You'll need to create this
        $name_column = "full_name";
    } elseif ($user_type !== 'doctor') {
        // Invalid user type selected
        header("Location: login.php?error=Invalid user type");
        exit();
    }

    $sql = "SELECT $id_column, $name_column, password FROM $table_name WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $full_name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION[$id_column] = $user_id;
            $_SESSION[$user_type . '_name'] = $full_name; // Set a specific name session
            header("Location: $redirect_page");
            exit();
        } else {
            header("Location: login.php?error=Incorrect username or password");
            exit();
        }
    } else {
        header("Location: login.php?error=Incorrect username or password");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: login.php");
    exit();
}
?>