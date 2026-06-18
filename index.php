<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'php/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Care Hospital</title>
    <link rel="stylesheet" href="css/common/base.css">
    <link rel="stylesheet" href="css/common/nav.css">
    <link rel="stylesheet" href="css/user/user_footer.css">
    <link rel="stylesheet" href="css/index_h.css">
</head>

<body class="bg-color" data-loggedin="<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>">

    <header>
        <div class="navbar-container">
            <nav class="navbar montserrat-font display-flex">
                <div class="brand display-flex">
                    <img class="brand-logo" src="image/main.ico" alt="Health Care Hospital Logo">
                    <h3 class="brand-name">Health Care Hospital</h3>
                </div>
                <ul class="nav-links display-flex">
                    <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="php/user/findDoctors.php" class="nav-link">Doctors</a></li>
                    <li class="nav-item"><a href="php/user/departments.php" class="nav-link">Departments</a></li>
                    <li class="nav-item"><a href="php/user/about.php" class="nav-link">About</a></li>

                    <?php
                    if (isset($_SESSION['user_id'])) {
                        echo '<li class="nav-item"><a href="php/user/contactUs.php" class="nav-link">Contact</a></li>';
                        echo '<li class="nav-item"><a href="php/user/user_profile.php" class="nav-link">Profile</a></li>';
                        echo '<li class="nav-item"><a href="php/user/logout.php" class="nav-link">Logout</a></li>';
                    } else {
                        echo '<li class="nav-item"><a href="php/user/contactUs.php" class="nav-link">Contact Us</a></li>';
                        echo '<li class="nav-item"><a href="php/user/loginForm.php" class="nav-link">Login</a></li>';
                    }
                    ?>

                </ul>

            </nav>
        </div>
    </header>

    <main class="main-section">
        <section class="banner">
            <div class="banner-content">
                <h1 class="banner-title montserrat-font">Welcome to <br>Health Care Hospital</h1>
                <p class="banner-description roboto-font ">
                    Health Care Hospital is committed to providing world-class healthcare services with a team of
                    experienced doctors and modern facilities. Our easy-to-use online system allows patients to book
                    appointments, view prescriptions and test reports, and manage health records efficiently.
                </p>
            </div>
            <img class="banner-image" src="image/banner/2.jpg" alt="Banner first image">
        </section>

        <section class="buttons display-flex">
            <button id="find-a-doctor" class="montserrat-font find-doctor">Find a Doctor</button>
            <!-- <button id="book-appointment-btn" class="montserrat-font book-an-appointment">Book an Appointment</button> -->
            <button id="test-reports" class="montserrat-font reports">Test Reports</button>
            <button id="have-a-query" class="montserrat-font query">Have a Query?</button>
        </section>

        <?php


        ?>

        <section class="doctors-section montserrat-font">
            <h2 class="doctor-list-title">Meet Our Specialist Doctors</h2>

            <div class="doctors-list">
                <?php
                $query = "SELECT d.doctor_id, d.doctor_fname, d.doctor_lname, d.doctor_degree, d.doctor_image, dep.department_name, dep.department_description FROM doctors_info AS d JOIN departments_info AS dep ON d.department_id = dep.department_id LIMIT 6";

                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $fullName = $row['doctor_fname'] . ' ' . $row['doctor_lname'];
                        $degree = $row['doctor_degree'];
                        $departmentName = $row['department_name'];
                        $departmentDesc = $row['department_description'];
                        $image = !empty($row['doctor_image']) ? $row['doctor_image'] : '';

                        if (!file_exists($image) || empty($image)) {
                            $image = "uploads/doctors/default.jpg";
                        }
                ?>
                        <div class="doctor-card display-flex">
                            <img src="<?php echo $image; ?>" alt="<?php echo $fullName; ?>">
                            <div class="doctor-info">
                                <h3><?php echo $fullName; ?></h3>
                                <p class="roboto-font">
                                    <?php echo $degree; ?> (<?php echo $departmentName; ?>) <br>
                                    <?php echo $departmentDesc; ?>
                                </p>
                                <button class="book-btn montserrat-font" data-doctor-id="<?php echo $row['doctor_id']; ?>" data-doctor-name="<?php echo $fullName; ?>">
                                    Book Appointment
                                </button>

                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<p>No doctors found.</p>";
                }
                ?>
            </div>

            <button id="view-all-doctors" class="view-all-doctors montserrat-font">View All Doctors</button>
        </section>

    </main>

    <footer class="user-footer">
        <div class="footer-content">
            <div class="footer-left">
                <h4>HealthCare Hospital</h4>
                <p>Contact: +880 1234 567 890</p>
                <p>Email: support@healthcare.com</p>
                <div class="social-links">
                    <a href=""><img src="image/footer/icon_fb.png" alt="facebook"></a>
                    <a href=""><img src="image/footer/icon_instagram.png" alt="instagram"></a>
                    <a href=""><img src="image/footer/icon_LN.png" alt="linkedin"></a>
                    <a href=""><img src="image/footer/icon_x.png" alt="x"></a>
                </div>
            </div>

            <div class="footer-middle">
                <a href="index.php">Home</a>
                <a href="php/user/findDoctors.php">Doctors</a>
                <a href="php/user/departments.php">Departments</a>
                <a href="php/user/about.php">About</a>
                <a href="php/user/contactUs.php">Contact Us</a>
            </div>

            <div class="footer-right">
                <h3>More Info</h3>
                <ul>
                    <li><a href="php/user/about.php">About Us</a></li>
                    <li><a href="php/user/service.php">Services</a></li>
                    <li><a href="php/user/career.php">Careers</a></li>
                    <li><a href="php/user/faq.php">FAQ</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 HealthCare Hospital | Designed for Users | All Rights Reserved.</p>
        </div>
    </footer>

    <script src="js/index.js"></script>
</body>

</html>