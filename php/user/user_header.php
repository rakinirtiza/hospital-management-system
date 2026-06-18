<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/common/nav.css">
</head>

<body>
    <header>
        <div class="navbar-container">
            <nav class="navbar montserrat-font display-flex">
                <div class="brand display-flex">
                    <img class="brand-logo" src="../../image/main.ico" alt="Health Care Hospital Logo">
                    <h3 class="brand-name">Health Care Hospital</h3>
                </div>
                <ul class="nav-links display-flex">
                    <li class="nav-item"><a href="../../index.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="findDoctors.php" class="nav-link">Doctors</a></li>
                    <li class="nav-item"><a href="departments.php" class="nav-link">Departments</a></li>
                    <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>

                    <?php
                    if (isset($_SESSION['user_id'])) {
                        echo '<li class="nav-item"><a href="contactUs.php" class="nav-link">Contact</a></li>';
                        echo '<li class="nav-item"><a href="user_profile.php" class="nav-link">Profile</a></li>';
                        echo '<li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>';
                    } else {
                        echo '<li class="nav-item"><a href="contactUs.php" class="nav-link">Contact Us</a></li>';
                        echo '<li class="nav-item"><a href="loginForm.php" class="nav-link">Login</a></li>';
                    }
                    ?>


                </ul>

            </nav>
        </div>
    </header>
</body>

</html>