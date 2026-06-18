<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

include '../db_connect.php';
$userid = $_SESSION['user_id'];

$usernameErr = $phoneErr = $emailErr = "";
$successMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = trim($_POST['user_fname']);
    $lname = trim($_POST['user_lname']);
    $phone = trim($_POST['user_phone']);
    $email = trim($_POST['user_email']);
    $dob = $_POST['user_dob'];
    $gender = $_POST['user_gender'];
    $address = trim($_POST['user_address']);

    if (empty($fname) || empty($lname)) $usernameErr = "Name required";
    if (empty($phone)) $phoneErr = "Phone required";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $emailErr = "Valid email required";

    if (empty($usernameErr) && empty($phoneErr) && empty($emailErr)) {
        $sql = "UPDATE users_info SET user_fname=?, user_lname=?, user_phone=?, user_email=?, user_dob=?, user_gender=?, user_address=? WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $fname, $lname, $phone, $email, $dob, $gender, $address, $userid);

        if($stmt->execute()){
            echo "<script>
                alert('Profile updated successfully!');
                window.location.href='user_profile.php';
            </script>";
            exit();
        } else {
            echo "<script>
                alert('Profile update failed!');
            </script>";
        }

        $stmt->close();
    }
}

$sql = "SELECT * FROM users_info WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | Health Care Hospital</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/user/update_user_profile.css">
</head>

<body class="bg-color">

    <header> <?php include 'user_header.php'; ?> </header>

    <main class="main-section">
        <section class="text-section">
            <h1 class="section-title montserrat-font">Update Profile</h1>
            <p class="section-description roboto-font">Update your personal information below.</p>
        </section>

        <?php if ($successMsg != "") echo "<p style='text-align:center;color:green;'>$successMsg</p>"; ?>

        <form action="" method="post" class="form-contain">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="user_fname" value="<?php echo htmlspecialchars($user['user_fname']); ?>">
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="user_lname" value="<?php echo htmlspecialchars($user['user_lname']); ?>">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="user_phone" value="<?php echo htmlspecialchars($user['user_phone']); ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="user_email" value="<?php echo htmlspecialchars($user['user_email']); ?>">
            </div>
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="user_dob" value="<?php echo $user['user_dob']; ?>">
            </div>
            <div class="form-group">
                <label>Gender</label>
                <select name="user_gender">
                    <option value="Male" <?php if ($user['user_gender'] == "Male") echo "selected"; ?>>Male</option>
                    <option value="Female" <?php if ($user['user_gender'] == "Female") echo "selected"; ?>>Female</option>
                    <option value="Other" <?php if ($user['user_gender'] == "Other") echo "selected"; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea name="user_address"><?php echo htmlspecialchars($user['user_address']); ?></textarea>
            </div>
            <div>
                <button type="submit" id="submit">Update Profile</button>
            </div>
        </form>
    </main>

    <footer> <?php include 'user_footer.php'; ?> </footer>
</body>

</html>