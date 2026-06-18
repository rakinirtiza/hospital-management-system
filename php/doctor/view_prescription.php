<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "doctor") {
    header("Location: ../user/loginForm.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];

$sql_prescriptions = "SELECT * FROM prescriptions_info WHERE doctor_id = ? ORDER BY prescription_date DESC";
$stmt_presc = $conn->prepare($sql_prescriptions);
$stmt_presc->bind_param("i", $doctor_id);
$stmt_presc->execute();
$result_presc = $stmt_presc->get_result();
$stmt_presc->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Prescriptions</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/doctor/view_prescriptions.css">
</head>
<body class="bg-color">
    <header><?php include 'doctor_header.php'; ?></header>

    <main class="main-section" id="main-section">
        <h2>All Prescriptions</h2>

        <?php
        if ($result_presc->num_rows > 0) {
            while ($presc = $result_presc->fetch_assoc()) {
                echo "<div class='prescription-card'>";
                echo "<h3>Prescription ID: " . $presc['prescription_id'] . "</h3>";
                echo "<p><strong>Patient:</strong> " . htmlspecialchars($presc['patient_name']) . " | <strong>Age:</strong> " . $presc['patient_age'] . " | <strong>Gender:</strong> " . $presc['patient_gender'] . "</p>";
                echo "<p><strong>Date:</strong> " . $presc['prescription_date'] . "</p>";
                echo "<p><strong>Notes:</strong> " . nl2br(htmlspecialchars($presc['prescription_notes'])) . "</p>";

                $sql_meds = "SELECT * FROM prescription_medicines WHERE prescription_id = ?";
                $stmt_meds = $conn->prepare($sql_meds);
                $stmt_meds->bind_param("i", $presc['prescription_id']);
                $stmt_meds->execute();
                $result_meds = $stmt_meds->get_result();
                
                if ($result_meds->num_rows > 0) {
                    echo "<table class='medicine-table'>";
                    echo "<thead><tr><th>Medicine Name</th><th>Dosage</th><th>Frequency</th><th>Duration</th><th>Instruction</th></tr></thead>";
                    echo "<tbody>";
                    while ($med = $result_meds->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($med['medicine_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($med['medicine_dosage']) . "</td>";
                        echo "<td>" . htmlspecialchars($med['medicine_frequency']) . "</td>";
                        echo "<td>" . htmlspecialchars($med['medicine_duration']) . "</td>";
                        echo "<td>" . htmlspecialchars($med['medicine_instruction']) . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p>No medicines added.</p>";
                }

                $stmt_meds->close();
                echo "<hr><br>";
                echo "</div>";
            }
        } else {
            echo "<p>No prescriptions found.</p>";
        }
        ?>
    </main>

    <footer><?php include 'doctor_footer.php'; ?></footer>
</body>
</html>
