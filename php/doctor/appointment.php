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

$sql_today = "SELECT a.appointment_id, a.patient_full_name, a.patient_phone, a.user_id, a.patient_message FROM appointments_info a WHERE a.doctor_id = ? AND a.appointment_date = CURDATE() ORDER BY a.appointment_date ASC";
$stmt_today = $conn->prepare($sql_today);
$stmt_today->bind_param("i", $doctor_id);
$stmt_today->execute();
$result_today = $stmt_today->get_result();
$todaysAppointments = $result_today->fetch_all(MYSQLI_ASSOC);
$stmt_today->close();

$sql_all = "SELECT a.appointment_id, a.patient_full_name, a.patient_phone, a.patient_message, a.appointment_date FROM appointments_info a WHERE a.doctor_id = ? ORDER BY CASE WHEN a.appointment_date > CURDATE() THEN 1 WHEN a.appointment_date = CURDATE() THEN 2 ELSE 3 END, a.appointment_date ASC";
$stmt_all = $conn->prepare($sql_all);
$stmt_all->bind_param("i", $doctor_id);
$stmt_all->execute();
$result_all = $stmt_all->get_result();
$allAppointments = $result_all->fetch_all(MYSQLI_ASSOC);
$stmt_all->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments | Health Care Hospital</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/doctor/appointment.css">
</head>

<body class="bg-color">

    <header> <?php include 'doctor_header.php'; ?> </header>

    <main class="main-section dashboard-main">

        <section class="welcome-section">
            <h2 class="section-title">Appointments Dashboard</h2>
            <p class="section-description">Hi Dr. <?php echo htmlspecialchars($doctor_name); ?>, check today's and all upcoming appointments below.</p>
        </section>

        <section class="todays-appointment">
            <h2>Today's Appointments</h2>
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($todaysAppointments): ?>
                        <?php foreach ($todaysAppointments as $appt): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($appt['patient_full_name']); ?></td>
                                <td><?php echo htmlspecialchars($appt['patient_phone']); ?></td>
                                <td><?php echo htmlspecialchars($appt['patient_message']); ?></td>
                                <td><a href="prescription.php?patient_id=<?php echo $appt['user_id']; ?>" class="btn">Prescription</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No appointments today</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <section class="all-appointment">
            <h2>All Appointments</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Patient Name</th>
                        <th>Phone</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($allAppointments): ?>
                        <?php foreach ($allAppointments as $appt): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($appt['appointment_date']); ?></td>
                                <td><?php echo htmlspecialchars($appt['patient_full_name']); ?></td>
                                <td><?php echo htmlspecialchars($appt['patient_phone']); ?></td>
                                <td><?php echo htmlspecialchars($appt['patient_message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No appointments found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer> <?php include 'doctor_footer.php'; ?> </footer>

</body>

</html>