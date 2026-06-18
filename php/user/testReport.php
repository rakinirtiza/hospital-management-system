<?php
// testReport.php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Reports - Health Care Hospital</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/common/nav.css">
    <link rel="stylesheet" href="../../css/common/footer_h.css">
    <link rel="stylesheet" href="../../css/user/test_report.css">
</head>

<body class="bg-color">
 
    <header> <?php include 'user_header.php'; ?> </header>

    <main class="main-section montserrat-font">
        <section class="test-report-section">
            <h2 class="page-title">Your Test Reports</h2>
            <p class="page-description roboto-font">
                Here you can view and download your medical test reports. Please log in to see your personal reports.
            </p>

            <div class="report-list">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Report ID</th>
                            <th>Patient Name</th>
                            <th>Test Name</th>
                            <th>Date</th>
                            <th>Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Example static data (later DB theke fetch korbe) -->
                        <tr>
                            <td>RPT001</td>
                            <td>John Doe</td>
                            <td>Blood Test</td>
                            <td>2025-09-10</td>
                            <td><a href="../../reports/blood_test.pdf" class="download-link">Download</a></td>
                        </tr>
                        <tr>
                            <td>RPT002</td>
                            <td>Jane Smith</td>
                            <td>X-Ray</td>
                            <td>2025-09-12</td>
                            <td><a href="../../reports/xray_report.pdf" class="download-link">Download</a></td>
                        </tr>
                        <tr>
                            <td>RPT003</td>
                            <td>Rahim Khan</td>
                            <td>ECG</td>
                            <td>2025-09-13</td>
                            <td><a href="../../reports/ecg_report.pdf" class="download-link">Download</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer> <?php include 'user_footer.php'; ?> </footer>

</body>

</html>
