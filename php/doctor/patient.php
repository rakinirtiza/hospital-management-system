<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../db_connect.php';

$doctor_fname = '';
$doctor_lname = '';
$doctor_full_name = '';
$department_id = 0;
$department_name = '';

if (isset($_SESSION['user_id'])) {
    $doctor_id = (int) $_SESSION['user_id'];

    $sql = "SELECT * FROM doctors_info WHERE doctor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $doctor_fname = htmlspecialchars($row['doctor_fname']);
        $doctor_lname = htmlspecialchars($row['doctor_lname']);
        $department_id = (int)$row['department_id'];
    }

    $doctor_full_name = $doctor_fname . ' ' . $doctor_lname;

    $stmt->close();

    $sql = "SELECT * FROM departments_info WHERE department_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $department_name = htmlspecialchars($row['department_name']);
    }

    $stmt->close();
}
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

        $fname = $lname = $phone = $email = $dob = $gender = $address = $disease = $department = $doctor = $admission = $room = "";

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
                if (strtotime($admission) < strtotime(date("Y-m-d"))) {
                    $admissionErr = "*Admission date cannot be in the past";
                }
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

    <div class="db-connect">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($fnameErr) && empty($lnameErr) && empty($phoneErr) && empty($emailErr) && empty($dobErr) && empty($genderErr) && empty($addressErr) && empty($diseaseErr) && empty($doctorErr) && empty($admissionErr) && empty($roomErr) && empty($departmentErr)) {

                $stmt = $conn->prepare("INSERT INTO patients_info (patient_fname, patient_lname, patient_phone, patient_email, patient_dob, patient_gender, patient_address, patient_disease, department_id, doctor_id, patient_admission, patient_room) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->bind_param("ssssssssiiss", $fname, $lname, $phone, $email, $dob, $gender, $address, $disease, $department_id, $doctor_id, $admission, $room);

                if ($stmt->execute()) {
                    echo "<script>alert('Patient successfully added!');</script>";
                    $fname = $lname = $phone = $email = $dob = $gender = $address = $disease =  $admission = $room = "";
                } else {
                    echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
                }
            }
        }
        ?>
    </div>

    <header> <?php include 'doctor_header.php'; ?> </header>

    <main class="main-section">
        <h2 class="montserrat-font section-title">Admit New Patient</h2>
        
        <form class="form-container" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <div class="form-group">
                <label for="fname">First Name</label>
                <input type="text" name="fname" id="fname" value="<?php echo $fname; ?>" placeholder="Enter first name">
                <span class="error"><?php echo $fnameErr; ?></span>
            </div>

            <div class="form-group">
                <label for="lname">Last Name</label>
                <input type="text" name="lname" id="lname" value="<?php echo $lname; ?>" placeholder="Enter last name">
                <span class="error"><?php echo $lnameErr; ?></span>
            </div>

            <div class="form-group">
                <label for="phone">Phone No</label>
                <input type="text" name="phone" id="phone" value="<?php echo $phone; ?>" placeholder="Enter phone number">
                <span class="error"><?php echo $phoneErr; ?></span>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" value="<?php echo $email; ?>" placeholder="patient@email.com">
                <span class="error"><?php echo $emailErr; ?></span>
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" name="dob" id="dob" value="<?php echo $dob; ?>">
                <span class="error"><?php echo $dobErr; ?></span>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select name="gender" id="gender">
                    <option value="" disabled <?php if ($gender == "") {
                                                    echo "selected";
                                                } ?>>Select your gender</option>
                    <option value="Male" <?php if ($gender == "Male") {
                                                echo "selected";
                                            } ?>>Male</option>
                    <option value="Female" <?php if ($gender == "Female") {
                                                echo "selected";
                                            } ?>>Female</option>
                    <option value="Other" <?php if ($gender == "Other") {
                                                echo "selected";
                                            } ?>>Other</option>
                </select>
                <span class="error"><?php echo $genderErr; ?></span>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" id="address" placeholder="Example: house no, road no, thana, district" value="<?php echo $address; ?>">
                <span class="error"><?php echo $addressErr; ?></span>
            </div>

            <div class="form-group">
                <label for="disease">Disease</label>
                <input type="text" name="disease" placeholder="Enter disease/problem" value="<?php echo $disease; ?>">
                <span class="error"><?php echo $diseaseErr; ?></span>
            </div>

            <div class="form-group">
                <label for="admission">Date of Admit</label>
                <input type="date" name="admission" id="admission" value="<?php echo !empty($admission) ? $admission : date('Y-m-d'); ?>">
                <span class="error"><?php echo $admissionErr; ?></span>
            </div>

            <div class="form-group">
                <label for="room">Room No</label>
                <input type="text" id="room" name="room" placeholder="Enter room no" value="<?php echo $room; ?>">
                <span class="error"><?php echo $roomErr; ?></span>
            </div>

            <input type="submit" class="save button" id="save" value="Save" name="submit">

        </form>

        <section class="patient-list-section">
            <h2 class="montserrat-font section-title">All Patients</h2>

            <?php
            if (isset($_GET['update_msg'])) {
                echo '<h4 style="color: green;">' . $_GET['update_msg'] . '</h4>';
            }
            if (isset($_GET['delete_msg'])) {
                echo '<h4 style="color: red;">' . $_GET['delete_msg'] . '</h4>';
            }
            ?>

            <table class="patient-table">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Full Name</th>
                        <th>Phone</th>
                        <th>DoB</th>
                        <th>Gender</th>
                        <th>Disease</th>
                        <th>Department</th>
                        <th>Doctor</th>
                        <th>Date of Admit</th>
                        <th>Room</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $sql = "SELECT p.*, d.doctor_fname, d.doctor_lname, dep.department_name FROM patients_info p JOIN doctors_info d ON p.doctor_id = d.doctor_id JOIN departments_info dep ON p.department_id = dep.department_id WHERE p.doctor_id = ? ORDER BY p.patient_id ASC";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $doctor_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $serial = 1;
                        while ($row = $result->fetch_assoc()) {
                            $fullName = $row['patient_fname'] . ' ' . $row['patient_lname'];
                            $doctorFullName = $row['doctor_fname'] . ' ' . $row['doctor_lname'];

                            echo "
                                <tr>
                                    <td>{$serial}</td>
                                    <td>{$fullName}</td>
                                    <td>{$row['patient_phone']}</td>
                                    <td>{$row['patient_dob']}</td>
                                    <td>{$row['patient_gender']}</td>
                                    <td>{$row['patient_disease']}</td>
                                    <td>{$row['department_name']}</td>
                                    <td>{$doctorFullName}</td>
                                    <td>{$row['patient_admission']}</td>
                                    <td>{$row['patient_room']}</td>
                                    <td>
                                        <a href=\"update_patient.php?patient_id={$row['patient_id']}&doctor_id={$row['doctor_id']}&department_id={$row['department_id']}\" class=\"edit-btn\">Update</a>

                                        <a href=\"delete_patient.php?id={$row['patient_id']}\" class=\"delete-btn\">Delete</a>
                                    </td>
                                </tr>";
                            $serial++;
                        }
                    } else {
                        echo "<tr><td colspan='14' style='text-align:center;'>No patients found</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </section>

    </main>

    <footer> <?php include 'doctor_footer.php'; ?> </footer>

    <!-- <script>
        document.getElementById("department_id").addEventListener("change", function() {
            let deptId = this.value;
            let doctorSelect = document.getElementById("doctor_id");

            doctorSelect.innerHTML = '<option value="">Select Doctor</option>';

            let xhr = new XMLHttpRequest();
            xhr.open("GET", "get_doctors.php?department_id=" + deptId, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    doctorSelect.innerHTML = this.responseText;
                } else {
                    doctorSelect.innerHTML = '<option value="">Error loading doctors</option>';
                }
            };
            xhr.send();
        });
    </script> -->

</body>

</html>