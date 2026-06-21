<?php
session_start();
include '../db_connect.php';

$loggedName = $loggedPhone = $loggedEmail = "";
$selectedDoctor = $_GET['doctor_name'] ?? '';
$selectedId = $_GET['doctor_id'] ?? '';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT user_fname, user_lname, user_phone, user_email FROM users_info WHERE user_id=?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $loggedName = $row['user_fname'] . " " . $row['user_lname'];
        $loggedPhone = $row['user_phone'];
        $loggedEmail = $row['user_email'];
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment | Health Care Hospital</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/user/book_appointment.css">
</head>

<body class="bg-color">

    <div class="validation-check">
        <?php

        $fullNameErr = $phoneErr = $emailErr = $doctorErr = $appointmentDateErr = "";
        $fullName = $phone = $email = $doctorId = $appointmentDate = $message = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (empty($_POST["fullName"])) {
                $fullNameErr = "*Name is required";
            } else {
                $fullName = test_input($_POST["fullName"]);
                if (!preg_match("/^[a-zA-Z-' ]*$/", $fullName)) {
                    $fullNameErr = "*Only letters and spaces allowed";
                }
            }

            if (empty($_POST["phone"])) {
                $phoneErr = "*Phone number is required";
            } else {
                $phone = test_input($_POST["phone"]);
                if (!preg_match("/^[0-9]{11}$/", $phone)) {
                    $phoneErr = "*Phone must be 11 digits";
                }
            }

            if (empty($_POST["email"])) {
                $emailErr = "*Email is required";
            } else {
                $email = test_input($_POST["email"]);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "*Invalid email format";
                }
            }

            if (empty($_POST["doctor_id"])) {
                $doctorErr = "*Please select a doctor";
            } else {
                $doctorId = test_input($_POST["doctor_id"]);
            }

            if (empty($_POST["appointmentDate"])) {
                $appointmentDateErr = "*Appointment date is required";
            } else {
                $appointmentDate = test_input($_POST["appointmentDate"]);
                if (strtotime($appointmentDate) < strtotime(date("Y-m-d"))) {
                    $appointmentDateErr = "*Appointment date cannot be in the past";
                }
            }

            if (!empty($_POST["message"])) {
                $message = test_input($_POST["message"]);
            }


            if (empty($fullNameErr) && empty($phoneErr) && empty($emailErr) && empty($doctorErr) && empty($appointmentDateErr)) {
                
                $stmt = $conn->prepare("INSERT INTO appointments_info (patient_full_name, patient_phone, patient_email, user_id, doctor_id, appointment_date, patient_message) VALUES (?, ?, ?, ?, ?, ?,?)");
                $stmt->bind_param("sssiiss", $fullName, $phone, $email, $userId, $doctorId, $appointmentDate, $message);

                if ($stmt->execute()) {
                    echo "<script> alert('Appointment booked successfully!'); window.location.href='findDoctors.php'; </script>";
                    exit();
                } else {
                    echo "<script>alert('Error: Could not book appointment.');</script>";
                }
                $stmt->close();
            }
        }

        function test_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        ?>

    </div>

    <header> <?php include 'user_header.php'; ?> </header>

    <main class="main-section">
        <section class="text-section">
            <h2 class="section-title montserrat-font">Book an Appointment</h2>
            <p class="section-description roboto-font">
                Please fill out the form below to schedule your appointment with our specialist doctors.
            </p>
        </section>

        <section class="form-contain">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="fullName">Patient Full Name</label>
                    <input type="text" id="fullName" name="fullName"
                        value="<?php echo $loggedName ? $loggedName : $fullName; ?>"
                        placeholder="Enter your name">
                    <span class="error"><?php echo $fullNameErr; ?></span>
                </div>

                <div class="form-group">
                    <label for="phone">Phone No</label>
                    <input type="tel" id="phone" name="phone"
                        value="<?php echo $loggedPhone ? $loggedPhone : $phone; ?>"
                        placeholder="Enter your phone number">
                    <span class="error"><?php echo $phoneErr; ?></span>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email"
                        value="<?php echo $loggedEmail ? $loggedEmail : $email; ?>"
                        placeholder="you@email.com">
                    <span class="error"><?php echo $emailErr; ?></span>
                </div>

                <div class="form-group">
                    <label for="doctor">Doctor</label>
                    <input type="text" id="doctor" name="doctor" value="<?php echo htmlspecialchars($selectedDoctor); ?>" readonly>
                    <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($selectedId); ?>">
                </div>

                <div class="form-group">
                    <label for="appointmentDate">Preferred Date</label>
                    <input type="date" id="appointmentDate" name="appointmentDate" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+2 day')); ?>">
                    <span class="error"><?php echo $appointmentDateErr; ?></span>
                </div>

                <div class="form-group">
                    <label for="message">Additional Notes</label>
                    <textarea id="message" name="message" rows="4" placeholder="Explain about your problem or disease(optional)"><?php echo $message; ?></textarea>
                </div>

                <input type="submit" id="submit" name="submit" value="Book Appointment">
            </form>
        </section>
    </main>

    <footer> <?php include 'user_footer.php'; ?> </footer>

</body>

</html>