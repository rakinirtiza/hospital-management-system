<?php include '../db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Check Feedback | Health Care Hospital</title>
  <link rel="stylesheet" href="../../css/common/base.css">
  <link rel="stylesheet" href="../../css/admin/checkFeedback.css">
</head>

<body class="bg-color">
  <header> <?php include 'admin_header.php'; ?> </header>

  <main class="main-section admin-feedback">
    <h1 class="section-title">Patient Feedback Management</h1>
    <p class="section-description">Here you can review patient feedbacks.</p>

    <table class="feedback-table">
      <thead>
        <tr>
          <th>Patient Name</th>
          <th>Phone</th>
          <th>Feedback</th>
          <th>Rating</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT patient_name, patient_phone, feedback_text, rating, feedback_date FROM feedback_info ORDER BY feedback_date DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<tr>
                        <td>{$row['patient_name']}</td>
                        <td>{$row['patient_phone']}</td>
                        <td>{$row['feedback_text']}</td>
                        <td>{$row['rating']}</td>
                        <td>{$row['feedback_date']}</td>
                  </tr>";
          }
        } else {
          echo "<tr><td colspan='5' style='text-align:center;'>No feedback found</td></tr>";
        }

        $conn->close();
        ?>
      </tbody>
    </table>
  </main>

  <footer> <?php include 'admin_footer.php'; ?> </footer>
</body>

</html>