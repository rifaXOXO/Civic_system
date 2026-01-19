<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'citizen') {
    echo "Access denied!";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Citizen Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FFD6E8 0%, #E6D5F5 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            text-align: center;
            color: #8B5A8E;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        h3 {
            color: #A87CA8;
            font-size: 20px;
            margin-top: 30px;
            margin-bottom: 15px;
            padding-left: 10px;
            border-left: 4px solid #E6B8E8;
        }
        
        ul {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        
        ul li {
            margin: 15px 0;
        }
        
        a {
            text-decoration: none;
            display: block;
            padding: 15px 25px;
            background: linear-gradient(135deg, #E6B8E8 0%, #C9B3E6 100%);
            color: #5A3A5E;
            border-radius: 12px;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(166, 142, 200, 0.3);
        }
        
        a:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(166, 142, 200, 0.5);
            background: linear-gradient(135deg, #D9A5DB 0%, #B8A3D9 100%);
        }
        
        .logout-link {
            text-align: center;
            margin-top: 40px;
        }
        
        .logout-link a {
            display: inline-block;
            background: linear-gradient(135deg, #FFB8D1 0%, #E6B8E8 100%);
            color: #5A3A5E;
            padding: 12px 30px;
            max-width: 200px;
        }
        
        .logout-link a:hover {
            background: linear-gradient(135deg, #FFA5C1 0%, #D9A5DB 100%);
        }
        
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
        
        table tr:nth-child(even) {
            background-color: #FFF5FA;
        }
        
        table tr:hover {
            background-color: #F9EBFF;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['user_name']; ?> (Citizen)</h2>
        
        <h3>Create a New Report:</h3>
        <ul>
            <li><a href="report.php">Submit a New Report</a></li>
        </ul>
        
        <h3>View Your Reports:</h3>
        <ul>
            <li><a href="view_reports.php">View Your Reports</a></li>
        </ul>
		
		<h3>Analytics & Reports</h3>
		<ul>
			<li><a href="statistics.php">View Statistics</a></li>
		</ul>

		
        
        <div class="logout-link">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>