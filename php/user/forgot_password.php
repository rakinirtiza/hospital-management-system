<?php
include '../db_connect.php';

$email = $emailErr = $info = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Please enter a valid email.";
    } else {
        $stmt = $conn->prepare("SELECT user_id FROM users_info WHERE user_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $emailErr = "No account found with that email.";
        } else {
            header("Location: verify_code.php?email=" . urlencode($email));
            exit;
        }

        $stmt->close();
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/user/forgot_password.css">
</head>

<body class="bg-color">
    <header> <?php include 'user_header.php'; ?> </header>

    <main class="main-section">
        <form class="form-content" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <div class="form-title">
                <h1 class="iceland-regular">Forgot Password</h1>
            </div>
            <div class="form-desc">
                <p>Reset your account in 3 simple steps</p>
            </div>

            <div class="form-group">
                <label for="email">Enter your registered Email</label> <br>
                <input type="text" name="email" id="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>">
                <span class="error"><?php echo $emailErr ?? ''; ?></span>
            </div>

            <div>
                <input type="submit" class="form-btn" name="send_code" value="Send Verification Code">
            </div>

        </form>
    </main>

    <footer> <?php include 'user_footer.php'; ?> </footer>
</body>

</html>