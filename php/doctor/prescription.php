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
$doctor_name = $_SESSION['fname'] ?? "Doctor";

$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;

$sql_patient = "SELECT user_fname, user_lname, user_dob, user_gender FROM users_info WHERE user_id = ? LIMIT 1";
$stmt_patient = $conn->prepare($sql_patient);
$stmt_patient->bind_param("i", $patient_id);
$stmt_patient->execute();
$result_patient = $stmt_patient->get_result();
$patient = $result_patient->fetch_assoc();
$stmt_patient->close();

if (!$patient) {
    echo "Patient not found.";
    exit();
}

$dob = new DateTime($patient['user_dob']);
$today = new DateTime();
$age = $today->diff($dob)->y;

$patient_name = $patient['user_fname'] . ' ' . $patient['user_lname'];
$patient_gender = $patient['user_gender'];
$patient_age = $age;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prescription_notes = trim($_POST['prescription_notes']);

    $sql_insert_prescription = "INSERT INTO prescriptions_info (doctor_id, patient_id, patient_name, patient_age, patient_gender, prescription_date, prescription_notes) VALUES (?, ?, ?, ?, ?, NOW(), ?)";
    $stmt_presc = $conn->prepare($sql_insert_prescription);
    $stmt_presc->bind_param("iissss", $doctor_id, $patient_id, $patient_name, $patient_age, $patient_gender, $prescription_notes);
    $stmt_presc->execute();
    $prescription_id = $stmt_presc->insert_id;
    $stmt_presc->close();
    
    if (!empty($_POST['medicine_name'])) {
        $med_names = $_POST['medicine_name'];
        $med_dosage = $_POST['medicine_dosage'];
        $med_frequency = $_POST['medicine_frequency'];
        $med_duration = $_POST['medicine_duration'];
        $med_instruction = $_POST['medicine_instruction'];

        $sql_insert_medicine = "INSERT INTO prescription_medicines (prescription_id, medicine_name, medicine_dosage, medicine_frequency, medicine_duration, medicine_instruction) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_med = $conn->prepare($sql_insert_medicine);

        for ($i = 0; $i < count($med_names); $i++) {
            if (!empty($med_names[$i])) {
                $stmt_med->bind_param(
                    "isssss",
                    $prescription_id,
                    $med_names[$i],
                    $med_dosage[$i],
                    $med_frequency[$i],
                    $med_duration[$i],
                    $med_instruction[$i]
                );
                $stmt_med->execute();
            }
        }
        $stmt_med->close();
    }
    header("Location: doctor_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription for <?php echo htmlspecialchars($patient['user_fname'] . ' ' . $patient['user_lname']); ?></title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/doctor/prescription.css">
</head>

<body class="bg-color">
    <header><?php include 'doctor_header.php'; ?></header>

    <main class="main-section" id="main-section">
        <section class="text-section">
            <h2 class="section-title montserrat-font">Prescription for <?php echo htmlspecialchars($patient['user_fname'] . ' ' . $patient['user_lname']); ?></h2>
            <p class="section-description roboto-font">Age: <?php echo $age; ?> | Gender: <?php echo $patient['user_gender']; ?></p>
        </section>

        <form method="POST">
            <label for="prescription_notes">Prescription Notes:</label><br>
            <textarea name="prescription_notes" id="prescription_notes" rows="4" cols="50" placeholder="Enter notes here..."></textarea>

            <h3>Medicines</h3>
            <div id="medicines-container">
                <div class="medicine-row">
                    <input type="text" name="medicine_name[]" placeholder="Medicine Name" required>
                    <input type="text" name="medicine_dosage[]" placeholder="Dosage" required>
                    <input type="text" name="medicine_frequency[]" placeholder="Frequency" required>
                    <input type="text" name="medicine_duration[]" placeholder="Duration" required>
                    <input type="text" name="medicine_instruction[]" placeholder="Instruction" required>
                </div>
            </div>
            <button type="button" onclick="addMedicineRow()">Add Another Medicine</button><br><br>
            <input type="submit" value="Save Prescription">
        </form>
    </main>

    <footer><?php include 'doctor_footer.php'; ?></footer>

    <script src="../../js/doctor/prescription.js"></script>

</body>

</html>