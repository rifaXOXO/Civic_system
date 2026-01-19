<?php
session_start();
include('dbconnect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'citizen') {
    echo "Access denied!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $road_number = $_POST['road_number'];
    $area_name = $_POST['area_name'];
    $city_name = $_POST['city_name'];

	// this here is insering the new row in my location table
    $sql_loc = "INSERT INTO location (road_number, area_name, city_name) 
                VALUES ('$road_number', '$area_name', '$city_name')";

    if ($conn->query($sql_loc) === TRUE) {
        $location_id = $conn->insert_id;
        $user_id = $_SESSION['user_id'];
		// this here is insering the new row in my report table
        $sql_report = "INSERT INTO report (`date`, `time`, user_id, location_id)
                       VALUES ('$date', '$time', $user_id, $location_id)";

        if ($conn->query($sql_report) === TRUE) {
            $report_id = $conn->insert_id;
            header("Location: select_report_type.php?report_id=$report_id");
            exit();
        } else {
            echo "Error inserting report: " . $conn->error;
        }
    } else {
        echo "Error inserting location: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Create a New Report</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FFD6E8 0%, #E6D5F5 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 600px;
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
            margin-bottom: 30px;
        }
        
        h3 {
            color: #A87CA8;
            font-size: 20px;
            margin-top: 30px;
            margin-bottom: 20px;
            padding-left: 10px;
            border-left: 4px solid #E6B8E8;
        }
        
        form {
            display: flex;
            flex-direction: column;
        }
        
        label {
            color: #6B4A6E;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        input[type="date"],
        input[type="time"],
        input[type="text"] {
            padding: 12px 15px;
            border: 2px solid #E6D5F5;
            border-radius: 10px;
            font-size: 15px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            background-color: #FEFAFF;
            color: #5A3A5E;
        }
        
        input[type="date"]:focus,
        input[type="time"]:focus,
        input[type="text"]:focus {
            outline: none;
            border-color: #C9B3E6;
            box-shadow: 0 0 0 3px rgba(201, 179, 230, 0.2);
            background-color: white;
        }
        
        input[type="submit"] {
            padding: 15px 30px;
            background: linear-gradient(135deg, #E6B8E8 0%, #C9B3E6 100%);
            color: #5A3A5E;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(166, 142, 200, 0.3);
            margin-top: 10px;
        }
        
        input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(166, 142, 200, 0.5);
            background: linear-gradient(135deg, #D9A5DB 0%, #B8A3D9 100%);
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
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create a New Report</h2>
        <form method="POST">
            <div class="form-group">
                <label>Date:</label>
                <input type="date" name="date" required>
            </div>
            
            <div class="form-group">
                <label>Time:</label>
                <input type="time" name="time" required>
            </div>
            
            <h3>Location Details</h3>
            
            <div class="form-group">
                <label>Road Number:</label>
                <input type="text" name="road_number" required>
            </div>
            
            <div class="form-group">
                <label>Area Name:</label>
                <input type="text" name="area_name" required>
            </div>
            
            <div class="form-group">
                <label>City Name:</label>
                <input type="text" name="city_name" required>
            </div>
            
            <input type="submit" value="Next">
        </form>
        
        <div class="back-link">
            <a href="citizen_dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
