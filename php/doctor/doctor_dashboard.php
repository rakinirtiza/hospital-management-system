<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "doctor") {
    header("Location: ../user/loginForm.php");
    exit();
}

$doctor_name = isset($_SESSION['fname']) ? $_SESSION['fname'] : "Doctor";
$doctor_id = $_SESSION['user_id'];
?>

<?php

$sql_today = "SELECT COUNT(*) AS total FROM appointments_info WHERE doctor_id = ? AND appointment_date = CURDATE()";
$stmt_today = $conn->prepare($sql_today);
$stmt_today->bind_param("i", $doctor_id);
$stmt_today->execute();
$result_today = $stmt_today->get_result();
$todaysAppointments = ($row = $result_today->fetch_assoc()) ? $row['total'] : 0;
$stmt_today->close();

$sql_upcoming = "SELECT COUNT(*) AS total FROM appointments_info WHERE doctor_id = ? AND appointment_date > CURDATE()";
$stmt_upcoming = $conn->prepare($sql_upcoming);
$stmt_upcoming->bind_param("i", $doctor_id);
$stmt_upcoming->execute();
$result_upcoming = $stmt_upcoming->get_result();
$upcomingAppointments = ($row = $result_upcoming->fetch_assoc()) ? $row['total'] : 0;
$stmt_upcoming->close();

$sql_active = "SELECT COUNT(DISTINCT user_id) AS total FROM appointments_info WHERE doctor_id = ? AND appointment_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
$stmt_active = $conn->prepare($sql_active);
$stmt_active->bind_param("i", $doctor_id);
$stmt_active->execute();
$result_active = $stmt_active->get_result();
$activePatients = ($row = $result_active->fetch_assoc()) ? $row['total'] : 0;
$stmt_active->close();

$sql_prescriptions = "SELECT COUNT(*) AS total FROM prescriptions_info WHERE doctor_id = ? AND prescription_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
$stmt_prescriptions = $conn->prepare($sql_prescriptions);
$stmt_prescriptions->bind_param("i", $doctor_id);
$stmt_prescriptions->execute();
$result_prescriptions = $stmt_prescriptions->get_result();
$recentPrescriptions = ($row = $result_prescriptions->fetch_assoc()) ? $row['total'] : 0;
$stmt_prescriptions->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard | Health Care Hospital</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/doctor/doctor_dashboard.css">
</head>

<body class="bg-color">

    <header> <?php include 'doctor_header.php'; ?> </header>

    <main class="main-section dashboard-main">
        <section class="welcome-section">
            <h2 class="section-title">Welcome Dr. <?php echo htmlspecialchars($doctor_name); ?></h2>
            <p class="welcome-description">Here's your overview for today and recent activity.</p>
        </section>
        <div class="cards-container">

            <div class="card">
                <h3>Today's Appointments</h3>
                <p><?php echo $todaysAppointments; ?></p>
            </div>

            <div class="card">
                <h3>Upcoming Appointments</h3>
                <p><?php echo $upcomingAppointments; ?></p>
            </div>

            <div class="card">
                <h3>Active Patients (Last 30 Days)</h3>
                <p><?php echo $activePatients; ?></p>
            </div>

            <div class="card">
                <h3>Prescriptions Issued (Last 30 Days)</h3>
                <p><?php echo $recentPrescriptions; ?></p>
            </div>

        </div>

        <section class="todays-appointment">
            <h3>Today's Appointments</h3>
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Phone</th>
                        <th>Disease</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 

                    $sql_today_list = "SELECT a.appointment_id, a.user_id, a.patient_full_name, a.patient_phone, a.patient_message FROM appointments_info a WHERE a.doctor_id = ? AND a.appointment_date = CURDATE()";
                    $stmt_today_list = $conn->prepare($sql_today_list);
                    $stmt_today_list->bind_param("i", $doctor_id);
                    $stmt_today_list->execute();
                    $result_today_list = $stmt_today_list->get_result();

                    if ($result_today_list->num_rows > 0) {
                        while ($row = $result_today_list->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['patient_full_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['patient_phone']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['patient_message']) . "</td>"; 
                            echo "<td><a href='prescription.php?patient_id=" . $row['user_id'] . "' class='btn'>Prescription</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No appointments for today.</td></tr>";
                    }

                    $stmt_today_list->close();
                    ?>
                </tbody>
            </table>
        </section>

        <section class="quick-links">
            <h3>Quick Actions</h3>
            <div class="links">
                <a href="patient.php" class="btn">View Patients</a>
                <a href="appointment.php" class="btn">View Appointments</a>
                <a href="view_prescription.php" class="btn">View Prescription</a>
                <a href="doctor_profile.php" class="btn">View Profile</a>
            </div>
        </section>
    </main>

    <footer> <?php include 'doctor_footer.php'; ?> </footer>

</body>

</html>