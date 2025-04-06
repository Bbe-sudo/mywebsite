<?php
session_start(); // Move this line to the very top

if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_config.php';

$doctor_id = $_SESSION['doctor_id'];

// Fetch doctor's information
$stmt = $conn->prepare("SELECT full_name, gender FROM doctors WHERE doctor_id = ?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if ($result) {
    $doctor_name = $result['full_name'];
    $doctor_gender = $result['gender'];
} else {
    // Handle case where doctor info is not found
    $doctor_name = "Unknown Doctor";
    $doctor_gender = "";
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="index.css">
    <style>
        .dashboard-header-info {
            display: flex;
            align-items: center;
        }

        .profile-emoji {
            font-size: 24px;
            margin-right: 10px;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-details .name {
            font-weight: bold;
        }

        .user-details .id {
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="dashboard-sidebar">
            <div class="logo">
                <h1>EHR System</h1>
            </div>
            <ul class="nav-links">
            <li class="active"><a href="index.php"><i class="icon">&#128205;</i> Dashboard</a></li>
            <li><a href="view_appointments.php"><i class="icon">&#128197;</i> Appointments</a></li>
            <li><a href="create_record.php"><i class="icon">&#128206;</i> Add Medical Record</a></li>
            <li><a href="view_records.php"><i class="icon">&#128218;</i> Medical Records</a></li>
            <li><a href="lab_results.php"><i class="icon">&#128300;</i> Lab Results</a></li>
            <li><a href="settings.php"><i class="icon">&#9881;</i> Settings</a></li>
            <li><a href="logout.php"><i class="icon">&#128682;</i> Logout</a></li>
            </ul>
        </aside>

        <main class="dashboard-main">
            <header class="dashboard-header">
                <div class="dashboard-header-info">
                    <span class="profile-emoji">
                        <?php
                        if ($doctor_gender === 'male') {
                            echo '👨‍⚕️';
                        } elseif ($doctor_gender === 'female') {
                            echo '👩‍⚕️';
                        } else {
                            echo '👤'; // Default icon
                        }
                        ?>
                    </span>
                    <div class="user-details">
                        <div class="name"><?php echo htmlspecialchars($doctor_name); ?></div>
                        <div class="id">Doctor ID: <?php echo htmlspecialchars($doctor_id); ?></div>
                    </div>
                </div>
            </header>

            <div class="content-wrapper">
                <section class="dashboard-summary">
                    <h2>Dashboard Summary</h2>
                    </section>

                <section class="search-patient">
                    <h2>Search Patient</h2>
                    <form action="patient_search.php" method="GET">
                        <input type="text" name="patient_id" placeholder="Enter Patient ID" required>
                        <button type="submit">Search</button>
                    </form>
                    <a href="add_patient.php" class="add-patient-link">Add New Patient</a>
                </section>

            </div>
        </main>
    </div>
</body>
</html>