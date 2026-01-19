<?php
session_start();
include('dbconnect.php');

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Access denied!";
    exit();
}

// Determine the timeframe from GET parameter
$timeframe = $_GET['timeframe'] ?? null;

if ($timeframe === 'week') {
    $dateCondition = "r.date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    $title = "Reports From the Last 7 Days";
} elseif ($timeframe === 'month') {
    $dateCondition = "r.date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
    $title = "Reports From the Last 30 Days";
} else {
    $dateCondition = "0"; // ensures no rows returned
    $title = "No timeframe selected";
}

// Fetch reports with type and status (only statuses of 3 types)
$sql = "
SELECT r.report_id, u.user_name, r.date, r.time,
       CASE
            WHEN mp.report_id IS NOT NULL THEN 'Missing Person'
            WHEN fh.report_id IS NOT NULL THEN 'Fire Hazard'
            WHEN lf.report_id IS NOT NULL THEN 'Lost & Found'
            WHEN hs.report_id IS NOT NULL THEN 'Health & Sanitation'
            WHEN dr.report_id IS NOT NULL THEN 'Disaster Relief'
            WHEN rs.report_id IS NOT NULL THEN 'Road Safety'
            ELSE 'Unknown'
       END AS report_type,
       COALESCE(mp.case_status, fh.fire_status, lf.item_status, '-') AS status
FROM report r
JOIN user u ON r.user_id = u.user_id
LEFT JOIN missing_person mp ON r.report_id = mp.report_id
LEFT JOIN fire_hazard fh ON r.report_id = fh.report_id
LEFT JOIN lost_and_found lf ON r.report_id = lf.report_id
LEFT JOIN health_sanitation hs ON r.report_id = hs.report_id
LEFT JOIN disaster_relief dr ON r.report_id = dr.report_id
LEFT JOIN road_safety rs ON r.report_id = rs.report_id
WHERE $dateCondition
ORDER BY r.date DESC, r.time DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports by Timeframe</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FFD6E8 0%, #E6D5F5 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
            background-color: rgba(255,255,255,0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        h2 {
            text-align: center;
            color: #8B5A8E;
            font-size: 28px;
            margin-bottom: 20px;
        }
        .buttons {
            text-align: center;
            margin-bottom: 30px;
        }
        .buttons a {
            display: inline-block;
            padding: 12px 25px;
            margin: 0 10px;
            border-radius: 10px;
            background: linear-gradient(135deg, #E6B8E8 0%, #C9B3E6 100%);
            color: #5A3A5E;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .buttons a:hover {
            background: linear-gradient(135deg, #D9A5DB 0%, #B8A3D9 100%);
        }
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
        tr:nth-child(even) { background-color: #FFF5FA; }
        tr:hover { background-color: #F9EBFF; }
        td { color: #5A3A5E; }
        .no-data {
            text-align: center;
            color: #A87CA8;
            font-size: 16px;
            padding: 40px;
            background-color: rgba(230,184,232,0.1);
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
    <h2><?= $title ?></h2>

    <div class="buttons">
        <a href="?timeframe=week">Last 7 Days</a>
        <a href="?timeframe=month">Last 30 Days</a>
    </div>

    <?php if ($timeframe && $result && $result->num_rows > 0): ?>
    <table>
        <tr>
            <th>Report ID</th>
            <th>Type</th>
            <th>User</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['report_id'] ?></td>
            <td><?= $row['report_type'] ?></td>
            <td><?= $row['user_name'] ?></td>
            <td><?= $row['date'] ?></td>
            <td><?= $row['time'] ?></td>
            <td><?= $row['status'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php elseif ($timeframe): ?>
        <p class="no-data">No reports found for this timeframe.</p>
    <?php else: ?>
        <p class="no-data">Select a timeframe to view reports.</p>
    <?php endif; ?>

    <div class="back-link">
        <a href="admin_dashboard.php">← Back to Dashboard</a>
    </div>
</div>
</body>
</html>
