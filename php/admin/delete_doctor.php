<?php
include '../db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM doctors_info WHERE doctor_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: addDoctor.php?delete_msg=Doctor deleted successfully");
        exit();
    } else {
        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
} else {
    echo "<p style='color:red;'>No Doctor ID Specified</p>";
}

$conn->close();
?>
