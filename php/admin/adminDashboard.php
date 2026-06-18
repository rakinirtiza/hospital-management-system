<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "admin") {
    header("Location: ../user/loginForm.php");
    exit();
}

$admin_name = isset($_SESSION['fname']) ? $_SESSION['fname'] : "Admin";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Health Care Hospital</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/admin/admin_dashboard.css">
</head>

<body class="bg-color">

    <div class="db-query">
        <?php
        $totalPatient = $totalDoctor = $appointment = $revenue = "";

        $sql = "SELECT COUNT(*) AS total FROM patients_info";
        if ($result = $conn->query($sql)) {
            $row = $result->fetch_assoc();
            $totalPatient = $row['total'];
        } else {
            $totalPatient = 0;
        }


        $sql = "SELECT COUNT(*) as total FROM doctors_info";
        $result = $conn->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $totalDoctor = $row['total'];
        } else {
            $totalDoctor = "No Doctor Available";
        }


        $today = date('Y-m-d');

        $sql = "SELECT COUNT(*) AS total FROM appointments_info WHERE DATE(appointment_date) = '$today'";
        $result = $conn->query($sql);

        if ($result && $row = $result->fetch_assoc()) {
            $appointment = $row['total'];
        } else {
            $appointment = 0;
        }

        $revenue = 500000;
        ?>

    </div>

    <header> <?php include 'admin_header.php'; ?> </header>

    <main class="main-section">
        <section class="welcome-section">
            <h1 class="welcome-title montserrat-font"> Welcome back, <?php echo htmlspecialchars($admin_name); ?> </h1>
            <p class="welcome-description roboto-font"> Here's what's happening at your hospital today! </p>
        </section>

        <section class="grid-list">

            <div class="total-patients grid-card">
                <h3 class="montserrat-font">Total Patients</h3> <br>
                <label class="roboto-font" for="patients"><?php echo $totalPatient ?></label>
            </div>

            <div class="active-doctor grid-card">
                <h3 class="montserrat-font">Active Doctors</h3> <br>
                <label class="roboto-font" for="doctors"><?php echo $totalDoctor ?></label>
            </div>

            <div class="todays-appointment grid-card">
                <h3 class="montserrat-font">Today's Appointments</h3> <br>
                <label class="roboto-font" for="appointment"><?php echo $appointment ?></label> <br>
                <!-- <label for="pending">25/<?php echo $appointment ?> pending</label> -->
            </div>
            <div class="monthly-revenue grid-card">
                <h3 class="montserrat-font">This Month's Revenue</h3> <br>
                <label class="roboto-font" for="revenue"><?php echo $revenue ?> TK.</label>
            </div>
        </section>

        <section class="department-occupancy">
            <h1>Department Occupancy</h1>
            <table>
                <thead>
                    <th>Department Name</th>
                    <th>Available Doctors</th>
                    <th>Required Doctors</th>
                </thead>
                <tbody>
                    <?php

                    $sql = " SELECT d.department_name, d.required_doctors, COUNT(di.doctor_id) AS available_doctors FROM departments_info d LEFT JOIN doctors_info di ON d.department_id = di.department_id GROUP BY d.department_id ";

                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['department_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['available_doctors']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['required_doctors']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No department data found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <section class="appointment-list">
            <h1>Today's Appointment</h1>
            <table>
                <thead>
                    <th>Doctor Name</th>
                    <th>Patient Name</th>
                    <th>Patient Phone</th>
                    <th>Room No</th>
                </thead>
                <tbody>
                    <?php 
                    
                    $sql = " SELECT d.doctor_fname, d.doctor_lname, d.doctor_room, a.patient_full_name, a.patient_phone FROM appointments_info a JOIN doctors_info d ON a.doctor_id = d.doctor_id WHERE DATE(a.appointment_date) = CURDATE() ORDER BY a.appointment_date ";

                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>Dr. " . htmlspecialchars($row['doctor_fname'] . ' ' . $row['doctor_lname']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['patient_full_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['patient_phone']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['doctor_room']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' style='text-align:center;'>No appointments today</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <section class="quick-access">
            <h1>Quick Access</h1>
            <div class="quick-buttons">
                <button id="add-patient-btn" class="btn">Add Patient</button>
                <button id="add-doctor-btn" class="btn">Add Doctor</button>
                <button id="view-resources-btn" class="btn">View Resources</button>
                <button id="check-feedback-btn" class="btn">Check Feedback</button>
            </div>
        </section>
    </main>

    <footer> <?php include 'admin_footer.php'; ?> </footer>

    <script src="../../js/admin/admin_dashboard.js"></script>

</body>

</html>