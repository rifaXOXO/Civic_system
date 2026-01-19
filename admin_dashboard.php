<?php
session_start();

// Restrict access to admin only
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Access denied!";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
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
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #8B5A8E;
            font-size: 28px;
            margin-bottom: 20px;
        }

        h2::before {
            content: "👨‍💼 ";
        }

        .menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }

        .menu li {
            margin: 0;
        }

        .menu a {
            text-decoration: none;
            display: block;
            padding: 20px 25px;
            background: linear-gradient(135deg, #E6B8E8 0%, #C9B3E6 100%);
            color: #5A3A5E;
            border-radius: 12px;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(166, 142, 200, 0.3);
        }

        .menu a:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(166, 142, 200, 0.5);
            background: linear-gradient(135deg, #D9A5DB 0%, #B8A3D9 100%);
        }

        /* Icons for each option */
        .menu li:nth-child(1) a::before { content: "🔥 "; margin-right: 6px; }
        .menu li:nth-child(2) a::before { content: "🔍 "; margin-right: 6px; }
        .menu li:nth-child(3) a::before { content: "👤 "; margin-right: 6px; }
        .menu li:nth-child(4) a::before { content: "📊 "; margin-right: 6px; } /* Statistics */
        .menu li:nth-child(5) a::before { content: "🔎 "; margin-right: 6px; } /* Filter */
        .menu li:nth-child(6) a::before { content: "📋 "; margin-right: 6px; } /* View All */

        .logout-link {
            text-align: center;
            margin-top: 40px;
        }

        .logout-link a {
            display: inline-block;
            background: linear-gradient(135deg, #FFB8D1 0%, #E6B8E8 100%);
            color: #5A3A5E;
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .logout-link a:hover {
            background: linear-gradient(135deg, #FFA5C1 0%, #D9A5DB 100%);
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Welcome, <?php echo $_SESSION['user_name']; ?> (Admin)</h2>

    <ul class="menu">
        <li><a href="manage_fire.php">Fire Hazard Reports</a></li>
        <li><a href="manage_lost_found.php">Lost & Found Reports</a></li>
        <li><a href="manage_missing.php">Missing Person Reports</a></li>
        <li><a href="statisticsad.php">Statistics</a></li>
        <li><a href="filter_reports.php">Filter Reports</a></li>
        <li><a href="view_all_reports.php">View All Reports</a></li>
    </ul>

    <div class="logout-link">
        <a href="logout.php">Logout</a>
    </div>
</div>
</body>
</html>




