<?php
session_start();
include('dbconnect.php');

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Access denied!";
    exit();
}

// Update case_status if form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $report_id = $_POST['report_id'];
    $new_status = $_POST['case_status'];

    // Simply update the status, no admin_user_id involved
    $sql_update = "UPDATE missing_person 
                   SET case_status='$new_status' 
                   WHERE report_id=$report_id";
    $conn->query($sql_update);
}

// Fetch all Missing Person reports
$sql = "SELECT report_id, person_name, age, gender, case_status,
               last_location, last_time_seen, attire
        FROM missing_person
        ORDER BY report_id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Missing Person Reports</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg,#FFD6E8 0%,#E6D5F5 100%); padding:20px;}
        .container { max-width:1300px; margin:0 auto; background-color:rgba(255,255,255,0.95); padding:40px; border-radius:20px; overflow-x:auto; box-shadow:0 8px 32px rgba(0,0,0,0.1);}
        h2 { text-align:center; color:#8B5A8E; margin-bottom:30px;}
        h2::before { content:"👤 "; }
        table { width:100%; border-collapse: collapse; background:white; border-radius:12px; overflow:hidden; box-shadow:0 4px 15px rgba(0,0,0,0.1);}
        th, td { border:1px solid #F5E6F5; padding:12px; text-align:left;}
        th { background: linear-gradient(135deg,#E6B8E8 0%,#C9B3E6 100%); color:#5A3A5E; font-weight:600; text-align:center;}
        tr:nth-child(even) { background:#FFF5FA; }
        tr:hover { background:#F9EBFF; }
        td { color:#5A3A5E; }
        select { padding:8px 12px; border:2px solid #E6D5F5; border-radius:8px; font-size:14px; background:#FEFAFF; color:#5A3A5E; cursor:pointer;}
        select:focus { outline:none; border-color:#C9B3E6; box-shadow:0 0 0 3px rgba(201,179,230,0.2);}
        input[type="submit"] { padding:8px 16px; border:none; border-radius:8px; background: linear-gradient(135deg,#E6B8E8 0%,#C9B3E6 100%); color:#5A3A5E; font-weight:600; cursor:pointer;}
        input[type="submit"]:hover { background: linear-gradient(135deg,#D9A5DB 0%,#B8A3D9 100%);}
        .status-badge { padding:4px 10px; border-radius:6px; font-weight:600; text-align:center; }
        .status-pending { background:#FFE5B4; color:#8B6914; }
        .status-in-progress { background:#D6E5FF; color:#1E3A8A; }
        .status-resolved { background:#D4F4DD; color:#2D5F3C; }
        .back-link { text-align:center; margin-top:30px;}
        .back-link a { text-decoration:none; color:#A87CA8; padding:10px 20px; border-radius:10px; display:inline-block;}
        .back-link a:hover { background:rgba(230,184,232,0.2); color:#8B5A8E;}
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Missing Person Reports</h2>

        <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Report ID</th>
                <th>Person Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Last Location</th>
                <th>Last Time Seen</th>
                <th>Attire</th>
                <th>Status</th>
                <th>Update Status</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td style="text-align:center;"><?php echo $row['report_id']; ?></td>
                <td><?php echo $row['person_name']; ?></td>
                <td style="text-align:center;"><?php echo $row['age']; ?></td>
                <td style="text-align:center;"><?php echo $row['gender']; ?></td>
                <td><?php echo $row['last_location']; ?></td>
                <td><?php echo $row['last_time_seen']; ?></td>
                <td><?php echo $row['attire']; ?></td>
                <td style="text-align:center;">
                    <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $row['case_status'])); ?>">
                        <?php echo $row['case_status']; ?>
                    </span>
                </td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">
                        <select name="case_status">
                            <option value="Pending" <?php if($row['case_status']=='Pending') echo 'selected'; ?>>Pending</option>
                            <option value="In Progress" <?php if($row['case_status']=='In Progress') echo 'selected'; ?>>In Progress</option>
                            <option value="Resolved" <?php if($row['case_status']=='Resolved') echo 'selected'; ?>>Resolved</option>
                        </select>
                        <input type="submit" value="Update">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p>No Missing Person reports found.</p>
        <?php endif; ?>

        <div class="back-link">
            <a href="admin_dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>


