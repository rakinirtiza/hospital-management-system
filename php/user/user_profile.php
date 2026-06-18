<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

include '../db_connect.php';

$userid = $_SESSION['user_id'];

$sql = "SELECT user_fname, user_lname, user_phone, user_email, user_dob, user_gender, user_address, user_role FROM users_info WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    $fullName = $user['user_fname'] . ' ' . $user['user_lname'];
} else {
    echo "User data not found!";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Health Care Hospital</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/user/user_profile.css">
</head>

<body class="bg-color">

    <header> <?php include 'user_header.php'; ?> </header>

    <main class="main-section">

        <section class="text-section">
            <h1 class="section-title montserrat-font">My Profile</h1>
            <p class="section-description roboto-font">View and manage your personal information.</p>
        </section>

        <table class="user-table">
            <tr>
                <th>Full Name</th>
                <td><?php echo $fullName; ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?php echo $user['user_phone']; ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo $user['user_email']; ?></td>
            </tr>
            <tr>
                <th>Date of Birth</th>
                <td><?php echo $user['user_dob']; ?></td>
            </tr>
            <tr>
                <th>Gender</th>
                <td><?php echo $user['user_gender']; ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo $user['user_address']; ?></td>
            </tr>
            <tr>
                <th>Actions</th>
                <td>
                    <a href="update_user_profile.php?id=<?php echo $userid; ?>" class="edit-btn">Edit Profile</a>
                    <a href="delete_user.php?id=<?php echo $userid; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete your account?');">Delete Account</a>
                </td>
            </tr>
        </table>

        <div class="blank-box">

        </div>
    </main>

    <footer> <?php include 'user_footer.php'; ?> </footer>
</body>

</html>