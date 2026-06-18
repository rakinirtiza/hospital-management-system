<?php
include '../db_connect.php';

if (isset($_GET['department_id'])) {
    $dept_id = intval($_GET['department_id']);
    $query = "SELECT doctor_id, doctor_fname, doctor_lname FROM doctors_info WHERE department_id = $dept_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo '<option value="">Select Doctor</option>';
        while ($row = $result->fetch_assoc()) {
            $fullName = $row['doctor_fname'] . ' ' . $row['doctor_lname'];
            echo '<option value="' . $row['doctor_id'] . '">' . $fullName . '</option>';
        }
    } else {
        echo '<option value="">No doctors available</option>';
    }
}
?>
