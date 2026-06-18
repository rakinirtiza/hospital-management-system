<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find a Doctor | Health Care Hospital</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/user/find_doctors.css">
</head>

<body class="bg-color" data-loggedin="<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>">

    <header><?php include 'user_header.php'; ?></header>

    <main class="main-section">
        <section class="doctors-section montserrat-font">

            <div class="section-title">
                <h2 class="doctor-list-title">Meet Our Specialist Doctors</h2>
            </div>

            <div class="searching-section display-flex">
                <input type="text" id="search_doctor_by_name" placeholder="Search by doctor name">
                <select id="search_doctor_by_department">
                    <option value="">Select Department</option>
                    <?php
                    $dept_sql = "SELECT department_id, department_name FROM departments_info";
                    $dept_result = $conn->query($dept_sql);
                    while ($dept = $dept_result->fetch_assoc()) {
                        echo "<option value='{$dept['department_id']}'>{$dept['department_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="doctors-list" id="doctorsList">

            </div>

        </section>
    </main>

    <footer><?php include 'user_footer.php'; ?></footer>

    <script src="../../js/user/find_doctors.js"></script>

</body>

</html>
