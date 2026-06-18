<?php
include '../db_connect.php';

$search = isset($_GET['name']) ? trim($_GET['name']) : '';
$department_id = isset($_GET['department_id']) ? intval($_GET['department_id']) : 0;

$query = "SELECT d.doctor_id, d.doctor_fname, d.doctor_lname, d.doctor_degree, d.doctor_image, dep.department_name, dep.department_description FROM doctors_info AS d JOIN departments_info AS dep ON d.department_id = dep.department_id WHERE 1";

if ($department_id > 0) {
    $query .= " AND d.department_id = $department_id";
}

if (!empty($search)) {
    $search_escaped = $conn->real_escape_string($search);
    $query .= " AND (d.doctor_fname LIKE '%$search_escaped%' OR d.doctor_lname LIKE '%$search_escaped%')";
}

$query .= " ORDER BY d.doctor_fname ASC";

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fullName = htmlspecialchars($row['doctor_fname'] . ' ' . $row['doctor_lname']);
        $degree = htmlspecialchars($row['doctor_degree']);
        $deptName = htmlspecialchars($row['department_name']);
        $deptDesc = htmlspecialchars($row['department_description']);
        $image = !empty($row['doctor_image']) ? htmlspecialchars($row['doctor_image']) : '';

        if (!file_exists($image) || empty($image)) {
            $image = "../../uploads/doctors/default.jpg";
        }

        echo " 
        <div class='doctor-card display-flex'>
            <img src='$image' alt='$fullName'>
            <div class='doctor-info'>
                <h3>$fullName</h3>
                <p class='roboto-font'>$degree ($deptName)<br>$deptDesc</p>
                <button class='book-btn montserrat-font' data-doctor-id='{$row['doctor_id']}' data-doctor-name='$fullName'>Book Appointment</button>
            </div>
        </div>";
    }
} else {
    echo "<p>No doctors found.</p>";
}
