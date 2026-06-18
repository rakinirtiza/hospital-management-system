<?php
session_start();
include '../db_connect.php';

$username = $password = "";
$usernameErr = $passwordErr = $loginErr = "";

if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["username"])) {
        $usernameErr = "*Email or phone is required";
    } else {
        $username = trim($_POST["username"]);

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
        } elseif (preg_match("/^[0-9]{11}$/", $username)) {
        } else {
            $usernameErr = "*Enter a valid email or 11-digit phone number";
        }
    }

    if (empty($_POST["password"])) {
        $passwordErr = "*Password is required";
    } else {
        $password = $_POST["password"];
    }

    if (empty($usernameErr) && empty($passwordErr)) {
        $sql = "SELECT user_id AS id, user_password AS password, user_fname AS fname, user_role AS role FROM users_info WHERE user_email=? OR user_phone=? UNION SELECT admin_id AS id, admin_password AS password, admin_fname AS fname, admin_role AS role FROM admins_info WHERE admin_email=? OR admin_phone=? UNION SELECT doctor_id AS id, doctor_password AS password, doctor_fname AS fname, doctor_role AS role FROM doctors_info WHERE doctor_email=? OR doctor_phone=? LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $username, $username, $username, $username, $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if ($password === $row['password']) {

                $_SESSION['user_id'] = $row['id'];
                $_SESSION['fname'] = $row['fname'];
                $_SESSION['role'] = $row['role'];

                if (isset($_POST['remember'])) {
                    setcookie("username", $username, time() + (30 * 24 * 60 * 60), "/");
                } else {
                    if (isset($_COOKIE['username'])) {
                        setcookie("username", "", time() - 3600, "/"); 
                    }
                }

                if ($row['role'] === "user") {
                    header("Location: ../../index.php");
                } elseif ($row['role'] === "admin") {
                    header("Location: ../admin/adminDashboard.php");
                } elseif ($row['role'] === "doctor") {
                    header("Location: ../doctor/doctor_dashboard.php");
                }
                exit();
            } else {
                $loginErr = "Incorrect password!";
            }
        } else {
            $loginErr = "User not found!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Care Hospital/Login</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/user/login.css">
</head>

<body class="bg-color">

    <header> <?php include 'user_header.php'; ?> </header>

    <main class="main-section">
        <form class="form-content" method="post" action="">
            <div class="form-title">
                <h1 class="iceland-regular">Login</h1>
                <p>Welcome onboard with us!</p>
            </div>

            <div class="form-group">
                <label for="username">Email or Phone No</label> <br>
                <input type="text" name="username" id="user" value="<?php echo htmlspecialchars($username); ?>" placeholder="Enter your email or phone no">
                <span class="error"><?php echo $usernameErr; ?></span>
            </div>

            <div class="form-group">
                <label for="password">Password</label> <br>
                <input type="password" name="password" id="password" placeholder="Enter your password">
                <span class="error"><?php echo $passwordErr; ?></span>
                <span class="error"><?php echo $loginErr; ?></span>
            </div>

            <div class="remember-me">
                <div>
                    <input type="checkbox" name="remember" id="remember" <?php if (isset($_COOKIE['username'])) echo "checked"; ?>> <label id="label-remember-me" for="remember">Remember Me</label>
                </div>
                <a class="forgot-password" href="forgot_password.php">Forgot Password?</a>
            </div>

            <div>
                <button class="form-btn">Login</button>
            </div>

            <div class="form-register">
                <label for="">Don't have account?</label>
                <a href="registerForm.php">Register</a>
                <label for="">Here</label>
            </div>
        </form>
    </main>

    <footer> <?php include 'user_footer.php'; ?> </footer>
</body>

</html>