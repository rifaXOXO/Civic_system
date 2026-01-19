<?php
session_start();
include('dbconnect.php');

// Only allow admin to view this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Access denied!";
    exit();
}

// --- Total Reports Per Type (all types) ---
$typeQuery = "
    SELECT 'Lost & Found' AS report_type, COUNT(*) AS total FROM lost_and_found
    UNION ALL
    SELECT 'Missing Person' AS report_type, COUNT(*) AS total FROM missing_person
    UNION ALL
    SELECT 'Fire Hazard' AS report_type, COUNT(*) AS total FROM fire_hazard
    UNION ALL
    SELECT 'Road Safety' AS report_type, COUNT(*) AS total FROM road_safety
    UNION ALL
    SELECT 'Health & Sanitation' AS report_type, COUNT(*) AS total FROM health_sanitation
    UNION ALL
    SELECT 'Disaster Relief' AS report_type, COUNT(*) AS total FROM disaster_relief
";
$typeResult = $conn->query($typeQuery);

// --- Reports Per Status (only Lost & Found, Missing Person, Fire Hazard) ---
$statusQuery = "
    SELECT status AS report_status, COUNT(*) AS total
    FROM (
        SELECT item_status AS status FROM lost_and_found
        UNION ALL
        SELECT case_status AS status FROM missing_person
        UNION ALL
        SELECT fire_status AS status FROM fire_hazard
    ) AS all_reports
    GROUP BY status
";
$statusResult = $conn->query($statusQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Statistics</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FFD6E8 0%, #E6D5F5 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: rgba(255,255,255,0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; color: #8B5A8E; font-size: 28px; margin-bottom: 30px; }
        h3 { color: #8B5A8E; font-size: 20px; margin-top: 30px; margin-bottom: 15px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #F5E6F5;
            padding: 12px;
            text-align: center;
        }
        th {
            background: linear-gradient(135deg, #E6B8E8 0%, #C9B3E6 100%);
            color: #5A3A5E;
            font-weight: 600;
        }
        td { color: #5A3A5E; }
        tr:nth-child(even) { background-color: #FFF5FA; }
        tr:hover { background-color: #F9EBFF; }
        .no-data {
            text-align: center;
            color: #A87CA8;
            font-size: 16px;
            padding: 40px;
            background-color: rgba(230, 184, 232, 0.1);
            border-radius: 12px;
            margin: 20px 0;
        }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a {
            text-decoration: none;
            color: #A87CA8;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
            display: inline-block;
        }
        .back-link a:hover { background-color: rgba(230,184,232,0.2); color: #8B5A8E; }
    </style>
</head>
<body>
<div class="container">
    <h2>Statistics</h2>

    <h3>Total Reports Per Type</h3>
    <?php if ($typeResult->num_rows > 0): ?>
    <table>
        <tr><th>Report Type</th><th>Total Reports</th></tr>
        <?php while($row = $typeResult->fetch_assoc()): ?>
        <tr>
            <td><?= $row['report_type'] ?></td>
            <td><?= $row['total'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p class="no-data">No reports found.</p>
    <?php endif; ?>

    <h3>Reports Per Status</h3>
    <?php if ($statusResult->num_rows > 0): ?>
    <table>
        <tr><th>Status</th><th>Total Reports</th></tr>
        <?php while($row = $statusResult->fetch_assoc()): ?>
        <tr>
            <td><?= $row['report_status'] ?></td>
            <td><?= $row['total'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p class="no-data">No status data available.</p>
    <?php endif; ?>

    <div class="back-link">
        <a href="admin_dashboard.php">← Back to Dashboard</a>
    </div>
</div>
</body>
</html>

