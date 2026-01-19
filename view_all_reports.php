<?php
session_start();
include('dbconnect.php');

// Admin only
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Access denied!";
    exit();
}

// Fetch all reports with user info and type
$sql = "
SELECT 
    r.report_id,
    r.date,
    r.time,
    u.user_name,
    l.road_number,
    l.area_name,
    l.city_name,
    CASE
        WHEN mp.report_id IS NOT NULL THEN 'Missing Person'
        WHEN fh.report_id IS NOT NULL THEN 'Fire Hazard'
        WHEN hs.report_id IS NOT NULL THEN 'Health & Sanitation'
        WHEN dr.report_id IS NOT NULL THEN 'Disaster Relief'
        WHEN lf.report_id IS NOT NULL THEN 'Lost & Found'
        WHEN rs.report_id IS NOT NULL THEN 'Road Safety'
        ELSE 'Unknown'
    END AS report_type
FROM report r
JOIN user u ON r.user_id = u.user_id
LEFT JOIN location l ON r.location_id = l.location_id
LEFT JOIN missing_person mp ON r.report_id = mp.report_id
LEFT JOIN fire_hazard fh ON r.report_id = fh.report_id
LEFT JOIN health_sanitation hs ON r.report_id = hs.report_id
LEFT JOIN disaster_relief dr ON r.report_id = dr.report_id
LEFT JOIN lost_and_found lf ON r.report_id = lf.report_id
LEFT JOIN road_safety rs ON r.report_id = rs.report_id
ORDER BY r.report_id DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Reports</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FFD6E8 0%, #E6D5F5 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }
        
        h2 {
            text-align: center;
            color: #8B5A8E;
            font-size: 28px;
            margin-bottom: 30px;
        }
        
        h2::before { content: "📋 "; }
        
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        table th, table td {
            border: 1px solid #F5E6F5;
            padding: 12px;
            text-align: center;
        }
        
        table th {
            background: linear-gradient(135deg, #E6B8E8 0%, #C9B3E6 100%);
            color: #5A3A5E;
            font-weight: 600;
        }
        
        table tr:nth-child(even) { background-color: #FFF5FA; }
        table tr:hover { background-color: #F9EBFF; }
        table td { color: #5A3A5E; }
        table td:first-child { font-weight: 600; }
        
        .no-reports {
            text-align: center;
            color: #A87CA8;
            font-size: 16px;
            padding: 40px;
            background-color: rgba(230, 184, 232, 0.1);
            border-radius: 12px;
            margin: 20px 0;
        }
        
        .back-link {
            text-align: center;
            margin-top: 30px;
        }
        
        .back-link a {
            text-decoration: none;
            color: #A87CA8;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .back-link a:hover {
            background-color: rgba(230, 184, 232, 0.2);
            color: #8B5A8E;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>All Reports</h2>
    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Report ID</th>
                <th>Type</th>
                <th>User</th>
                <th>Date</th>
                <th>Time</th>
                <th>Road Number</th>
                <th>Area Name</th>
                <th>City Name</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['report_id']; ?></td>
                <td><?php echo $row['report_type']; ?></td>
                <td><?php echo $row['user_name']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['time']; ?></td>
                <td><?php echo $row['road_number']; ?></td>
                <td><?php echo $row['area_name']; ?></td>
                <td><?php echo $row['city_name']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="no-reports">No reports found.</p>
    <?php endif; ?>
    
    <div class="back-link">
        <a href="admin_dashboard.php">← Back to Dashboard</a>
    </div>
</div>
</body>
</html>
