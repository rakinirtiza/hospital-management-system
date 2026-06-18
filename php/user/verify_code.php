<?php
include '../db_connect.php';

$email = $_GET['email'] ?? '';
$code = "";
$codeErr = $errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $code = trim($_POST['code'] ?? '');

    if ($code === '') {
        $codeErr = "*Verification code is required";
    } elseif ($code !== '1234') {
        $errorMsg = "Invalid verification code!";
    } else {
        header("Location: reset_password.php?email=" . urlencode($email));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code | Health Care Hospital</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/user/verify_code.css">
</head>

<body>
    <header> <?php include 'user_header.php'; ?> </header>

    <main class="main-section">
        <form class="form-content" method="post">

            <div class="form-title">
                <h1 class="iceland-regular">Verify Code</h1>
                <p>Enter the verification code sent to your email</p>
            </div>

            <div class="form-group">
                <label for="code">Verification Code</label><br>
                <input type="text" name="code" id="code" placeholder="Enter code" value="<?php echo htmlspecialchars($code); ?>">
                <span class="error"><?php echo $codeErr; ?></span>
                <span class="error"><?php echo $errorMsg; ?></span>
            </div>

            <div>
                <input type="submit" class="form-btn" value="Verify Code">
            </div>
        </form>
    </main>

    <footer> <?php include 'user_footer.php'; ?> </footer>
</body>

</html>