<?php
session_start();
include('dbconnect.php');

// Admin only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied!";
    exit();
}

// Update fire status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_id   = (int) $_POST['report_id'];
    $fire_status = $_POST['fire_status'];

    $update_sql = "
        UPDATE fire_hazard
        SET fire_status = '$fire_status'
        WHERE report_id = $report_id
    ";

    $conn->query($update_sql);
}

// Fetch all fire hazard reports
$sql = "
    SELECT fh.report_id, fh.hazard_type, fh.severity_rate, fh.fire_status
    FROM fire_hazard fh
    ORDER BY fh.report_id DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Fire Hazard Reports</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FFB6D9 0%, #D8B5FF 50%, #C9A0F5 100%);
            background-attachment: fixed;
            padding: 25px;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: rgba(255,255,255,0.97);
            padding: 40px;
            border-radius: 25px;
            box-shadow: 0 15px 45px rgba(200,100,200,0.15);
            border: 1px solid rgba(255,182,217,0.3);
        }

        h2 {
            text-align: center;
            color: #A64D79;
            font-size: 30px;
            margin-bottom: 30px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 14px;
            border: 1px solid #F5D8F5;
            text-align: center;
            color: #6E3A5E;
        }

        th {
            background: linear-gradient(135deg, #E6A8D8 0%, #D89FD8 100%);
            color: white;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #FFF5FF;
        }

        tr:hover {
            background-color: #FFE6F5;
        }

        select {
            padding: 8px 12px;
            border-radius: 8px;
            border: 2px solid #F5D8F5;
            font-weight: 600;
            cursor: pointer;
            background-color: white;
            color: #6E3A5E;
        }

        select:focus {
            outline: none;
            border-color: #E6A8D8;
        }

        input[type="submit"] {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #D89FD8 0%, #C9A0F5 100%);
            color: white;
            font-weight: 700;
            cursor: pointer;
            margin-left: 6px;
            transition: all 0.3s ease;
        }

        input[type="submit"]:hover {
            background: linear-gradient(135deg, #C88FC8 0%, #B890E5 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(200,100,200,0.3);
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 35px;
            text-decoration: none;
            color: #B57BB4;
            font-weight: 600;
            padding: 12px 25px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .back::before {
            content: "← ";
            margin-right: 5px;
        }

        .back:hover {
            color: #A64D79;
            background-color: rgba(255,182,217,0.25);
        }

        .no-data {
            text-align: center;
            font-weight: 600;
            color: #B57BB4;
            padding: 40px;
            background: linear-gradient(135deg, #FFE6F5 0%, #F5D8FF 100%);
            border-radius: 15px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🔥 Manage Fire Hazard Reports</h2>

    <?php if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>Report ID</th>
            <th>Hazard Type</th>
            <th>Severity</th>
            <th>Status</th>
            <th>Update Status</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['report_id']; ?></td>
            <td><?php echo $row['hazard_type']; ?></td>
            <td><?php echo $row['severity_rate']; ?></td>
            <td><strong><?php echo $row['fire_status']; ?></strong></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">
                    <select name="fire_status">
                        <option value="Pending" <?php if($row['fire_status']=='Pending') echo 'selected'; ?>>Pending</option>
                        <option value="In Progress" <?php if($row['fire_status']=='In Progress') echo 'selected'; ?>>In Progress</option>
                        <option value="Resolved" <?php if($row['fire_status']=='Resolved') echo 'selected'; ?>>Resolved</option>
                    </select>
                    <input type="submit" value="Update">
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p class="no-data">
            No fire hazard reports found.
        </p>
    <?php endif; ?>

    <a class="back" href="admin_dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>