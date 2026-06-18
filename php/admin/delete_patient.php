<?php
include '../db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM patients_info WHERE patient_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Patient successfully deleted!');</script>";
        header("Location: addPatient.php?delete_msg=Patient deleted successfully");
        exit();
    } else {
        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
} else {
    echo "<p style='color:red;'>No patient ID specified</p>";
}

$conn->close();
?>
