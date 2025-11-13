<?php
include 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST["userName"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $role = $_POST["role"];

    // Extra fields (for coach, dean, manager)
    $fname = $_POST["fName"] ?? '';
    $lname = $_POST["lName"] ?? '';
    $mobile = $_POST["mobile"] ?? '';
    $deptID = $_POST["deptID"] ?? '';

    // Password match checker
    if($password != $confirmPassword){
        echo "Password does not match.<br>";
    }
    // Password validation
    elseif(!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)){
        echo "Password must be at least 8 characters long and include at least one letter, one number and one special character.<br>";
    } else {
        // Check if username already exists
        $check = $conn->query("SELECT * FROM registration WHERE userName = '$username'");
        if($check->num_rows > 0){
            echo "Username already exists.<br>";
        } else {
            // If role requires a department, validate it exists
            if(in_array($role, ['coach', 'dean', 'manager']) && !empty($deptID)){
                $deptCheck = $conn->query("SELECT * FROM department WHERE deptID='$deptID'");
                if($deptCheck->num_rows == 0){
                    echo "Selected Department does not exist.<br>";
                    exit;
                }
            }

            // Insert into registration table
            $sql = "INSERT INTO registration(userName, password, confirmPassword, role)
                    VALUES('$username', '$password', '$confirmPassword', '$role')";
            
            if($conn->query($sql) === TRUE){
                // Additional insertion depending on role
                if($role == "coach"){
                    $conn->query("INSERT INTO coach(userName,fName, lName, mobile, deptID) VALUES('$username', '$fname', '$lname', '$mobile', '$deptID')");
                } elseif($role == "dean"){
                    $conn->query("INSERT INTO dean(userName,fName, lName, mobile, deptID) VALUES('$username', '$fname', '$lname', '$mobile', '$deptID')");
                } elseif($role == "manager"){
                    $conn->query("INSERT INTO tournamentmanager(userName, fName, lName, mobile, deptID) VALUES('$username', '$fName', '$lName', '$mobile', '$deptID')");
                }
                echo "Registration successful. <a href='login.php'>Login here</a><br>";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <script>
        // Show or hide extra fields depending on role
        function showExtraFields(role) {
            const extraFields = document.getElementById("extraFields");
            if (role === "coach" || role === "dean" || role === "manager") {
                extraFields.style.display = "block";
            } else {
                extraFields.style.display = "none";
            }
        }
    </script>
</head>
<body>
    <h2>Registration Page</h2>

    <form method="POST" action="">
        <label>Username:</label><br>
        <input type="text" name="userName" required><br><br>

        <label>Password: </label><br>
        <input type="password" name="password" required><br><br>

        <label>Confirm Password: </label><br>
        <input type="password" name="confirmPassword" required><br><br>

        <label>Role:</label><br>
        <select name="role" onchange="showExtraFields(this.value)" required>
            <option value="">--Select Role--</option>
            <option value="admin">Admin</option>
            <option value="dean">Dean</option>
            <option value="student">Student</option>
            <option value="manager">Tournament Manager</option>
            <option value="coach">Coach</option>
        </select><br><br>

        <!-- Extra Fields (for coach/dean/manager only) -->
        <div id="extraFields" style="display:none;">
            <label>First Name:</label><br>
            <input type="text" name="fName"><br><br>

            <label>Last Name:</label><br>
            <input type="text" name="lName"><br><br>

            <label>Mobile:</label><br>
            <input type="text" name="mobile"><br><br>

            <label>Department:</label><br>
            <select name="deptID">
                <option value="">--Select Department--</option>
                <?php
                // Fetch departments from DB
                $deptResult = $conn->query("SELECT deptID, deptName FROM department");
                if ($deptResult->num_rows > 0) {
                    while ($row = $deptResult->fetch_assoc()) {
                        echo "<option value='{$row['deptID']}'>{$row['deptName']} ({$row['deptID']})</option>";
                    }
                }
                ?>
            </select><br><br>
        </div>

        <input type="submit" value="Register" name="register">
    </form>

    <p>Already have an account? <a href="login.php">Login here!</a></p>
</body>
</html>
