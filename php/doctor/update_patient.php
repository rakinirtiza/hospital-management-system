<?php 
include '../db_connect.php'; 

$patient_id = isset($_GET['patient_id']) ? (int)$_GET['patient_id'] : 0;
$doctor_id = isset($_GET['doctor_id']) ? (int)$_GET['doctor_id'] : 0;
$department_id = isset($_GET['department_id']) ? (int)$_GET['department_id'] : 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Patient | Health Care Hospital</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/admin/add_patient.css">
</head>

<body class="bg-color">
    <div class="form-validation-php">
        <?php

        $fnameErr = $lnameErr = $phoneErr = $emailErr = $dobErr = $genderErr = $addressErr = $diseaseErr = $departmentErr = $doctorErr = $admissionErr = $roomErr = "";

        $fname = $lname = $phone = $email = $dob = $gender = $address = $disease = $admission = $room = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (empty($_POST["fname"])) {
                $fnameErr = "*First name is required";
            } else {
                $fname = test_input($_POST["fname"]);
                if (!preg_match("/^[a-zA-Z-' ]*$/", $fname)) {
                    $fnameErr = "*Only letters and white space allowed";
                }
            }

            if (empty($_POST["lname"])) {
                $lnameErr = "*Last name is required";
            } else {
                $lname = test_input($_POST["lname"]);
                if (!preg_match("/^[a-zA-Z-' ]*$/", $lname)) {
                    $lnameErr = "*Only letters and white space allowed";
                }
            }

            if (empty($_POST["phone"])) {
                $phoneErr = "*Phone number is required";
            } else {
                $phone = test_input($_POST["phone"]);
                if (!preg_match("/^[0-9]{11}$/", $phone)) {
                    $phoneErr = "*Invalid phone number";
                }
            }

            if (!empty($_POST["email"])) {
                $email = test_input($_POST["email"]);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "*Invalid email format";
                }
            }

            if (empty($_POST["dob"])) {
                $dobErr = "*Date of Birth is required";
            } else {
                $dob = test_input($_POST["dob"]);
                if (strtotime($dob) > strtotime(date("Y-m-d"))) {
                    $dobErr = "*Date of Birth cannot be in the future";
                }
            }

            if (empty($_POST["gender"])) {
                $genderErr = "*Gender is required";
            } else {
                $gender = test_input($_POST["gender"]);
            }

            if (empty($_POST["address"])) {
                $addressErr = "*Address is required";
            } else {
                $address = test_input($_POST["address"]);
            }

            if (empty($_POST["disease"])) {
                $diseaseErr = "*Disease is required";
            } else {
                $disease = test_input($_POST["disease"]);
                if (!preg_match("/^[a-zA-Z0-9 .,-]*$/", $disease)) {
                    $diseaseErr = "*Invalid characters in disease";
                }
            }

            if (empty($_POST["admission"])) {
                $admissionErr = "*Admission date is required";
            } else {
                $admission = $_POST["admission"];
            }

            if (empty($_POST["room"])) {
                $roomErr = "*Room number is required";
            } else {
                $room = test_input($_POST["room"]);
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

    <div class="db-section">
        <?php
        if (isset($_GET['patient_id'])) {

            $sql = "SELECT * FROM patients_info WHERE patient_id = '$patient_id'";
            $result = $conn->query($sql);

            if (!$result) {
                die("query Failed" . $conn->error);
            } else {
                $row = $result->fetch_assoc();
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($fnameErr) && empty($lnameErr) && empty($phoneErr) && empty($emailErr) && empty($dobErr) && empty($genderErr) && empty($addressErr) && empty($diseaseErr) && empty($doctorErr) && empty($admissionErr) && empty($roomErr) && empty($departmentErr)) {

                $stmt = $conn->prepare("UPDATE patients_info SET patient_fname=?, patient_lname=?, patient_phone=?, patient_email=?, patient_dob=?, patient_gender=?, patient_address=?, patient_disease=?,patient_admission=?, patient_room=? WHERE patient_id=?");

                $stmt->bind_param("sssssssssii", $fname, $lname, $phone, $email, $dob, $gender, $address, $disease,  $admission, $room, $patient_id);

                if ($stmt->execute()) {
                    $fname = $lname = $phone = $email = $dob = $gender = $address = $disease = $admission = $room = "";
                    header('location:patient.php?update_msg=You have successfully updated the data.');
                } else {
                    echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
                }
            }
        }

        ?>
    </div>

    <header> <?php include 'doctor_header.php'; ?> </header>

    <main class="main-section">

        <h2 class="montserrat-font section-title">Update Patient Details</h2>
        <p><?php echo $patient_id .' '.$doctor_id.' '.$department_id ?></p>
        <form class="form-container" method="post" action="update_patient.php?patient_id=<?php echo $patient_id ?>">

            <input type="hidden" name="patient_id" value="<?php echo $patient_id ?>">

            <div class="form-group">
                <label for="fname">First Name</label>
                <input type="text" name="fname" id="fname" value="<?php echo $row['patient_fname']; ?>" placeholder="Enter your first name">
                <span class="error"><?php echo $fnameErr; ?></span>
            </div>

            <div class="form-group">
                <label for="lname">Last Name</label>
                <input type="text" name="lname" id="lname" value="<?php echo $row['patient_lname']; ?>" placeholder="Enter your last name">
                <span class="error"><?php echo $lnameErr; ?></span>
            </div>

            <div class="form-group">
                <label for="phone">Phone No</label>
                <input type="text" name="phone" id="phone" value="<?php echo $row['patient_phone']; ?>" placeholder="Enter your phone number">
                <span class="error"><?php echo $phoneErr; ?></span>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" value="<?php echo $row['patient_email']; ?>" placeholder="you@email.com">
                <span class="error"><?php echo $emailErr; ?></span>
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" name="dob" id="dob" value="<?php echo $row['patient_dob']; ?>">
                <span class="error"><?php echo $dobErr; ?></span>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select name="gender" id="gender">
                    <option value="Male" <?php if ($row['patient_gender'] == "Male") echo "selected"; ?>>Male</option>
                    <option value="Female" <?php if ($row['patient_gender'] == "Female") echo "selected"; ?>>Female</option>
                    <option value="Other" <?php if ($row['patient_gender'] == "Other") echo "selected"; ?>>Other</option>
                </select>
                <span class="error"><?php echo $genderErr; ?></span>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" id="address"
                    placeholder="Example: house no, road no, thana, district"
                    value="<?php echo $row['patient_address']; ?>">
                <span class="error"><?php echo $addressErr; ?></span>
            </div>


            <div class="form-group">
                <label for="disease">Disease</label>
                <input type="text" name="disease" placeholder="Enter disease/problem" value="<?php echo $row['patient_disease']; ?>">
                <span class="error"><?php echo $diseaseErr; ?></span>
            </div>

            <div class="form-group">
                <label for="admission">Admission Date</label>
                <input type="date" name="admission" value="<?php echo $row['patient_admission']; ?>" readonly>
                <span class="error"><?php echo $admissionErr; ?></span>
            </div>

            <div class="form-group">
                <label for="room">Room No</label>
                <input type="text" id="room" name="room" placeholder="Enter room no" value="<?php echo $row['patient_room']; ?>">
                <span class="error"><?php echo $roomErr; ?></span>
            </div>

            <input type="submit" class="save button" id="save" value="Save" name="submit">

        </form>

    </main>

    <footer> <?php include 'doctor_footer.php'; ?> </footer>


</body>

</html>