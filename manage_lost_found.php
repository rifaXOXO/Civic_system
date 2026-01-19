<?php
session_start();
include('dbconnect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Access denied!";
    exit();
}

// Update item_status if form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $report_id = $_POST['report_id'];
    $new_status = $_POST['item_status'];

    $sql_update = "UPDATE lost_and_found SET item_status='$new_status' WHERE report_id=$report_id";
    $conn->query($sql_update);
}

// Fetch all Lost & Found reports
$sql = "SELECT lf.report_id, lf.item_name, lf.item_description, lf.report_type, lf.item_status, u.user_name
        FROM lost_and_found lf
        JOIN report r ON lf.report_id = r.report_id
        JOIN user u ON r.user_id = u.user_id
        ORDER BY lf.report_id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Lost & Found Reports</title>
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
        
        h2::before {
            content: "🔍 ";
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
            text-align: left;
        }
        
        table th {
            background: linear-gradient(135deg, #E6B8E8 0%, #C9B3E6 100%);
            color: #5A3A5E;
            font-weight: 600;
            text-align: center;
        }
        
        table tr:nth-child(even) {
            background-color: #FFF5FA;
        }
        
        table tr:hover {
            background-color: #F9EBFF;
        }
        
        table td {
            color: #5A3A5E;
        }
        
        select {
            padding: 8px 12px;
            border: 2px solid #E6D5F5;
            border-radius: 8px;
            font-size: 14px;
            background-color: #FEFAFF;
            color: #5A3A5E;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        select:focus {
            outline: none;
            border-color: #C9B3E6;
            box-shadow: 0 0 0 3px rgba(201, 179, 230, 0.2);
        }
        
        input[type="submit"] {
            padding: 8px 16px;
            background: linear-gradient(135deg, #E6B8E8 0%, #C9B3E6 100%);
            color: #5A3A5E;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(166, 142, 200, 0.3);
            margin-left: 8px;
        }
        
        input[type="submit"]:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(166, 142, 200, 0.5);
            background: linear-gradient(135deg, #D9A5DB 0%, #B8A3D9 100%);
        }
        
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
        
        form {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .status-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
        }
        
        .status-pending {
            background-color: #FFE5B4;
            color: #8B6914;
        }
        
        .status-resolved {
            background-color: #D4F4DD;
            color: #2D5F3C;
        }
        
        .status-progress {
            background-color: #D6E5FF;
            color: #1E3A8A;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Lost & Found Reports</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Report ID</th>
                    <th>User</th>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td style="text-align: center; font-weight: 600;"><?php echo $row['report_id']; ?></td>
                    <td><?php echo $row['user_name']; ?></td>
                    <td><?php echo $row['item_name']; ?></td>
                    <td><?php echo $row['item_description']; ?></td>
                    <td style="text-align: center;"><?php echo $row['report_type']; ?></td>
                    <td style="text-align: center;">
                        <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $row['item_status'])); ?>">
                            <?php echo $row['item_status']; ?>
                        </span>
                    </td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">
                            <select name="item_status">
                                <option value="Pending" <?php if($row['item_status']=='Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Resolved" <?php if($row['item_status']=='Resolved') echo 'selected'; ?>>Resolved</option>
                                <option value="In Progress" <?php if($row['item_status']=='In Progress') echo 'selected'; ?>>In Progress</option>
                            </select>
                            <input type="submit" value="Update">
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p class="no-reports">No Lost & Found reports found.</p>
        <?php endif; ?>
        
        <div class="back-link">
            <a href="admin_dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>