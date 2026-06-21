<?php
include '../db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments | Health Care Hospital</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/user/department_h.css">
</head>

<body class="bg-color">
    <header> <?php include 'user_header.php'; ?> </header>

    <main class="main-section">
        <section class="departments-section">
            <h2 class="montserrat-font">Our Departments</h2>
            <p class="roboto-font">Explore our specialized medical departments for quality healthcare.</p>

            <div class="departments-container">
                <?php
                $sql = "SELECT department_name, department_description FROM departments_info ORDER BY department_name ASC";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="department-card">';
                        echo '<h3>' . htmlspecialchars($row["department_name"]) . '</h3>';
                        echo '<p>' . htmlspecialchars($row["department_description"]) . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>No departments found!</p>";
                }
                ?>
            </div>
        </section>
    </main>

    <footer> <?php include 'user_footer.php'; ?> </footer> </footer>
</body>

</html>