<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include '../db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
  header("Location: ../user/loginForm.php");
  exit();
}

$doctor_id = $_SESSION['user_id'];
$doctor_name = htmlspecialchars($_SESSION['fname'] ?? "Doctor");

$sql = "SELECT doctor_fname, doctor_lname, doctor_phone, doctor_email, doctor_dob, doctor_doj, doctor_gender, doctor_country, doctor_degree, doctor_room, doctor_fees, doctor_salary, doctor_image, department_id FROM doctors_info WHERE doctor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $doctor = $result->fetch_assoc();
} else {
  echo "<script>alert('Doctor information not found!'); window.location.href='doctor_dashboard.php';</script>";
  exit();
}

$stmt->close();

$image = !empty($doctor['doctor_image']) ? $doctor['doctor_image'] : '';

if (!file_exists($image) || empty($image)) {
  $image = "uploads/doctors/default.jpg";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Doctor Profile | Health Care Hospital</title>
  <link rel="stylesheet" href="../../css/common/base.css">
  <link rel="stylesheet" href="../../css/common/nav.css">
  <link rel="stylesheet" href="../../css/common/footer_h.css">
  <link rel="stylesheet" href="../../css/doctor/profile.css">
</head>

<body class="bg-color">

  <header> <?php include 'doctor_header.php'; ?> </header>

  <section class="main-section profile-section">
    <div class="profile-header">
      <img src="<?php echo $image; ?>" alt="<?php echo $doctor['doctor_fname']; ?>" class="profile-img">
      <h2 class="doctor-name">Dr. <?php echo htmlspecialchars($doctor['doctor_fname'] . " " . $doctor['doctor_lname']); ?></h2>
    </div>

    <section class="doctor-profile-section">
      <h2 class="montserrat-font">Doctor Profile</h2>
      <div class="doctor-profile">

        <div class="profile-row">
          <span class="profile-label">Full Name:</span>
          <span class="profile-value">Dr. <?php echo htmlspecialchars($doctor['doctor_fname'] . " " . $doctor['doctor_lname']); ?></span>
        </div>

        <div class="profile-row">
          <span class="profile-label">Date of Birth:</span>
          <span class="profile-value"><?php echo htmlspecialchars($doctor['doctor_dob']); ?></span>
        </div>

        <div class="profile-row">
          <span class="profile-label">Joining Date:</span>
          <span class="profile-value"><?php echo htmlspecialchars($doctor['doctor_doj']); ?></span>
        </div>

        <div class="profile-row">
          <span class="profile-label">Gender:</span>
          <span class="profile-value"><?php echo htmlspecialchars($doctor['doctor_gender']); ?></span>
        </div>

        <div class="profile-row">
          <span class="profile-label">Country:</span>
          <span class="profile-value"><?php echo htmlspecialchars($doctor['doctor_country']); ?></span>
        </div>

        <div class="profile-row">
          <span class="profile-label">Degree:</span>
          <span class="profile-value"><?php echo htmlspecialchars($doctor['doctor_degree']); ?></span>
        </div>

        <div class="profile-row">
          <span class="profile-label">Room No:</span>
          <span class="profile-value"><?php echo htmlspecialchars($doctor['doctor_room']); ?></span>
        </div>

        <div class="profile-row">
          <span class="profile-label">Current Fees:</span>
          <span class="profile-value"><?php echo htmlspecialchars($doctor['doctor_fees']); ?>.00 TK</span>
        </div>

        <div class="profile-row">
          <span class="profile-label">Salary:</span>
          <span class="profile-value"><?php echo htmlspecialchars($doctor['doctor_salary']); ?> TK</span>
        </div>

        <div class="profile-row">
          <span class="profile-label">Contact:</span>
          <span class="profile-value"><?php echo htmlspecialchars($doctor['doctor_email']) . " | " . htmlspecialchars($doctor['doctor_phone']); ?></span>
        </div>

      </div>
    </section>
  </section>

  <footer> <?php include 'doctor_footer.php'; ?> </footer>

</body>

</html>