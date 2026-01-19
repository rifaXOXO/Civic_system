<?php
session_start();
include('dbconnect.php');

// Only allow citizens
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'citizen') {
    echo "Access denied!";
    exit();
}

// Road Safety: Top 5 areas by average risk rate
$riskQuery = "
    SELECT l.area_name, l.city_name, AVG(rs.risk_rate) AS avg_risk
    FROM road_safety rs
    JOIN report r ON rs.report_id = r.report_id
    JOIN location l ON r.location_id = l.location_id
    GROUP BY l.area_name, l.city_name
    ORDER BY avg_risk DESC
    LIMIT 5
";
$riskResult = $conn->query($riskQuery);

// Lost & Found: Items lost more than 5 times
$lostQuery = "
    SELECT item_name, COUNT(*) AS lost_count
    FROM lost_and_found
    WHERE report_type = 'Lost'
    GROUP BY item_name
    HAVING lost_count > 5
    ORDER BY lost_count DESC
";
$lostResult = $conn->query($lostQuery);

// Missing Person: Count of active cases
$missingQuery = "
    SELECT COUNT(*) AS active_cases
    FROM missing_person
    WHERE case_status != 'Resolved'
";
$missingResult = $conn->query($missingQuery);
$missingCount = $missingResult->fetch_assoc()['active_cases'] ?? 0;

// Fire Hazard: Count per severity
$fireQuery = "
    SELECT severity_rate, COUNT(*) AS count
    FROM fire_hazard
    GROUP BY severity_rate
";
$fireResult = $conn->query($fireQuery);

// Health & Sanitation: Count per severity
$healthQuery = "
    SELECT severity_rate, COUNT(*) AS count
    FROM health_sanitation
    GROUP BY severity_rate
";
$healthResult = $conn->query($healthQuery);

// Disaster Relief: Count per disaster type
$disasterQuery = "
    SELECT disaster_type, COUNT(*) AS count
    FROM disaster_relief
    GROUP BY disaster_type
";
$disasterResult = $conn->query($disasterQuery);
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
            max-width: 1000px;
            margin: 0 auto;
            background-color: rgba(255,255,255,0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #8B5A8E;
            font-size: 28px;
            margin-bottom: 30px;
        }
        h3 {
            color: #8B5A8E;
            font-size: 20px;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
        td {
            color: #5A3A5E;
        }
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

    <h3>Top 5 Areas by Road Safety Risk Rate</h3>
    <?php if ($riskResult->num_rows > 0): ?>
        <table>
            <tr><th>Area Name</th><th>City Name</th><th>Average Risk Rate</th></tr>
            <?php while ($row = $riskResult->fetch_assoc()): ?>
            <tr>
                <td><?= $row['area_name'] ?></td>
                <td><?= $row['city_name'] ?></td>
                <td><?= number_format($row['avg_risk'],2) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="no-data">No data available.</p>
    <?php endif; ?>

    <h3>Missing Person: Active Cases</h3>
    <table>
        <tr><th>Active Cases</th></tr>
        <tr><td><?= $missingCount ?></td></tr>
    </table>

    <h3>Fire Hazard: Count by Severity</h3>
    <?php if ($fireResult->num_rows > 0): ?>
    <table>
        <tr><th>Severity</th><th>Count</th></tr>
        <?php while ($row = $fireResult->fetch_assoc()): ?>
        <tr>
            <td><?= $row['severity_rate'] ?></td>
            <td><?= $row['count'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p class="no-data">No fire hazard data available.</p>
    <?php endif; ?>

    <h3>Health & Sanitation: Count by Severity</h3>
    <?php if ($healthResult->num_rows > 0): ?>
    <table>
        <tr><th>Severity</th><th>Count</th></tr>
        <?php while ($row = $healthResult->fetch_assoc()): ?>
        <tr>
            <td><?= $row['severity_rate'] ?></td>
            <td><?= $row['count'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p class="no-data">No health & sanitation data available.</p>
    <?php endif; ?>

    <h3>Disaster Relief: Count by Type</h3>
    <?php if ($disasterResult->num_rows > 0): ?>
    <table>
        <tr><th>Disaster Type</th><th>Count</th></tr>
        <?php while ($row = $disasterResult->fetch_assoc()): ?>
        <tr>
            <td><?= $row['disaster_type'] ?></td>
            <td><?= $row['count'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p class="no-data">No disaster relief data available.</p>
    <?php endif; ?>

    <h3>Lost & Found: Items Lost More Than 5 Times</h3>
    <?php if ($lostResult->num_rows > 0): ?>
    <table>
        <tr><th>Item Name</th><th>Times Lost</th></tr>
        <?php while ($row = $lostResult->fetch_assoc()): ?>
        <tr>
            <td><?= $row['item_name'] ?></td>
            <td><?= $row['lost_count'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p class="no-data">No items meet the criteria.</p>
    <?php endif; ?>

    <div class="back-link">
        <a href="citizen_dashboard.php">← Back to Dashboard</a>
    </div>
</div>
</body>
</html>
