<?php
session_start();
include('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $phone = $_POST['phone_no'];

    // Check user by email and phone_no
    $sql = "SELECT * FROM user WHERE email='$email' AND phone_no='$phone'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Set session variables
        $_SESSION['user_id']   = $row['user_id'];
        $_SESSION['user_name'] = $row['user_name']; // matches your user table
        $_SESSION['role']      = $row['role'];

        // Redirect based on role
        if ($row['role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: citizen_dashboard.php");
        }
        exit();
    } else {
        echo "Invalid login credentials";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FFD6E8 0%, #E6D5F5 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .container {
            max-width: 450px;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            text-align: center;
            color: #8B5A8E;
            font-size: 32px;
            margin-bottom: 30px;
            font-weight: 600;
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
        
        input[type="email"],
        input[type="text"] {
            padding: 14px 15px;
            border: 2px solid #E6D5F5;
            border-radius: 10px;
            font-size: 15px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            background-color: #FEFAFF;
            color: #5A3A5E;
        }
        
        input[type="email"]:focus,
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
        
        .signup-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #E6D5F5;
        }
        
        .signup-link a {
            text-decoration: none;
            color: #A87CA8;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .signup-link a:hover {
            color: #8B5A8E;
            text-decoration: underline;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="POST">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone_no" required>
            </div>
            
            <input type="submit" value="Login">
        </form>
        
        <div class="signup-link">
            <a href="signup.php">Don't have an account? Sign Up</a>
        </div>
    </div>
</body>
</html>
