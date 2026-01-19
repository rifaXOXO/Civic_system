<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'citizen') {
    echo "Access denied!";
    exit();
}

if (!isset($_GET['report_id'])) {
    echo "No report selected!";
    exit();
}
$report_id = $_GET['report_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Report Type</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FFD6E8 0%, #E6D5F5 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 700px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            text-align: center;
            color: #8B5A8E;
            font-size: 28px;
            margin-bottom: 20px;
        }
        
        .report-id {
            text-align: center;
            color: #A87CA8;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 30px;
            padding: 12px 20px;
            background-color: rgba(230, 184, 232, 0.2);
            border-radius: 10px;
            display: inline-block;
            width: 100%;
            box-sizing: border-box;
        }
        
        ul {
            list-style: none;
            padding: 0;
            margin: 20px 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 15px;
        }
        
        ul li {
            margin: 0;
        }
        
        a {
            text-decoration: none;
            display: block;
            padding: 18px 25px;
            background: linear-gradient(135deg, #E6B8E8 0%, #C9B3E6 100%);
            color: #5A3A5E;
            border-radius: 12px;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(166, 142, 200, 0.3);
            font-size: 15px;
        }
        
        a:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(166, 142, 200, 0.5);
            background: linear-gradient(135deg, #D9A5DB 0%, #B8A3D9 100%);
        }
        
        .back-link {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #E6D5F5;
        }
        
        .back-link a {
            display: inline-block;
            background: linear-gradient(135deg, #FFB8D1 0%, #E6B8E8 100%);
            color: #5A3A5E;
            padding: 12px 30px;
            max-width: 250px;
        }
        
        .back-link a:hover {
            background: linear-gradient(135deg, #FFA5C1 0%, #D9A5DB 100%);
        }

        /* Icon styles for visual interest */
        ul li a::before {
            content: "📋 ";
            margin-right: 8px;
        }
        
        ul li:nth-child(1) a::before { content: "🚗 "; }
        ul li:nth-child(2) a::before { content: "🔍 "; }
        ul li:nth-child(3) a::before { content: "🔥 "; }
        ul li:nth-child(4) a::before { content: "👤 "; }
        ul li:nth-child(5) a::before { content: "🩺 "; }
        ul li:nth-child(6) a::before { content: "🌪️ "; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Select Report Type</h2>
        <p class="report-id">Report ID: <?php echo $report_id; ?></p>
        
        <ul>
            <li><a href="submit_road_safety.php?report_id=<?php echo $report_id; ?>">Road Safety</a></li>
            <li><a href="submit_lost_found.php?report_id=<?php echo $report_id; ?>">Lost & Found</a></li>
            <li><a href="submit_fire.php?report_id=<?php echo $report_id; ?>">Fire Hazard</a></li>
            <li><a href="submit_missing.php?report_id=<?php echo $report_id; ?>">Missing Person</a></li>
            <li><a href="submit_health.php?report_id=<?php echo $report_id; ?>">Health & Sanitation</a></li>
            <li><a href="submit_disaster.php?report_id=<?php echo $report_id; ?>">Disaster Relief</a></li>
        </ul>
        
        <div class="back-link">
            <a href="citizen_dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>