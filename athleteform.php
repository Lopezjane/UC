<?php
session_start();
include 'db.php';

// Ensure the student is logged in
if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
    exit();
}

$idNum = $_SESSION['userName']; // student's ID

// Initialize variables
$eventID = $deptID = $lastName = $firstName = $middleName = "";
$course = $year = $civilStatus = $gender = $birthdate = "";
$contactNo = $address = "";
$status = 'pending';
$message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $eventID = $conn->real_escape_string($_POST['eventID']);
    $deptID = $conn->real_escape_string($_POST['deptID']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $middleName = $conn->real_escape_string($_POST['middleName']);
    $course = $conn->real_escape_string($_POST['course']);
    $year = $conn->real_escape_string($_POST['year']);
    $civilStatus = $conn->real_escape_string($_POST['civilStatus']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $birthdate = $conn->real_escape_string($_POST['birthdate']);
    $contactNo = $conn->real_escape_string($_POST['contactNo']);
    $address = $conn->real_escape_string($_POST['address']);

    // Check if student already has an application
    $checkSql = "SELECT * FROM athletesprofile WHERE idNum='$idNum'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        // Update existing application
        $updateSql = "UPDATE athletesprofile SET 
            eventID='$eventID',
            deptID='$deptID',
            lastName='$lastName',
            firstName='$firstName',
            middleName='$middleName',
            course='$course',
            year='$year',
            civilStatus='$civilStatus',
            gender='$gender',
            birthdate='$birthdate',
            contactNo='$contactNo',
            address='$address',
            status='pending'
            WHERE idNum='$idNum'";
        
        if ($conn->query($updateSql) === TRUE) {
            $message = "Application updated successfully! Waiting for approval.";
        } else {
            $message = "Error updating application: " . $conn->error;
        }
    } else {
        // Insert new application
        $insertSql = "INSERT INTO athletesprofile (
            idNum, eventID, deptID, lastName, firstName, middleName,
            course, year, civilStatus, gender, birthdate, contactNo, address, status
        ) VALUES (
            '$idNum', '$eventID', '$deptID', '$lastName', '$firstName', '$middleName',
            '$course', '$year', '$civilStatus', '$gender', '$birthdate', '$contactNo', '$address', 'pending'
        )";

        if ($conn->query($insertSql) === TRUE) {
            $message = "Application submitted successfully! Waiting for approval.";
        } else {
            $message = "Error submitting application: " . $conn->error;
        }
    }
} else {
    // Load existing data to prefill form
    $sql = "SELECT * FROM athletesprofile WHERE idNum='$idNum'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $eventID = $row['eventID'];
        $deptID = $row['deptID'];
        $lastName = $row['lastName'];
        $firstName = $row['firstName'];
        $middleName = $row['middleName'];
        $course = $row['course'];
        $year = $row['year'];
        $civilStatus = $row['civilStatus'];
        $gender = $row['gender'];
        $birthdate = $row['birthdate'];
        $contactNo = $row['contactNo'];
        $address = $row['address'];
        $status = $row['status'];
    }
}

// Fetch options for eventID and deptID dropdowns
$eventResult = $conn->query("SELECT eventID, eventName FROM event");
$deptResult = $conn->query("SELECT deptID, deptName FROM department");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Athlete Application Form</title>
</head>
<body>
    <h2>Athlete Application Form</h2>

    <?php if (!empty($message)) echo "<p><strong>$message</strong></p>"; ?>

    <form method="POST" action="">
        <label>ID Number:</label><br>
        <input type="text" name="idNum" value="<?php echo htmlspecialchars($idNum); ?>" readonly><br><br>

        <label>Event:</label><br>
        <select name="eventID" required>
            <option value="">--Select Event--</option>
            <?php
            if ($eventResult->num_rows > 0) {
                while ($row = $eventResult->fetch_assoc()) {
                    $selected = ($row['eventID'] == $eventID) ? "selected" : "";
                    echo "<option value='{$row['eventID']}' $selected>{$row['eventName']} ({$row['eventID']})</option>";
                }
            }
            ?>
        </select><br><br>

        <label>Department:</label><br>
        <select name="deptID" required>
            <option value="">--Select Department--</option>
            <?php
            if ($deptResult->num_rows > 0) {
                while ($row = $deptResult->fetch_assoc()) {
                    $selected = ($row['deptID'] == $deptID) ? "selected" : "";
                    echo "<option value='{$row['deptID']}' $selected>{$row['deptName']} ({$row['deptID']})</option>";
                }
            }
            ?>
        </select><br><br>

        <label>Last Name:</label><br>
        <input type="text" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" required><br><br>

        <label>First Name:</label><br>
        <input type="text" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" required><br><br>

        <label>Middle Name:</label><br>
        <input type="text" name="middleName" value="<?php echo htmlspecialchars($middleName); ?>"><br><br>

        <label>Course:</label><br>
        <select name="course" required>
            <option value="">--Select Course--</option>
            <option value="BSIT" <?php if($course=='BSIT') echo 'selected'; ?>>BSIT</option>
            <option value="BSCS" <?php if($course=='BSCS') echo 'selected'; ?>>BSCS</option>
            <option value="BPED" <?php if($course=='BPED') echo 'selected'; ?>>BPED</option>
            <option value="HM" <?php if($course=='HM') echo 'selected'; ?>>HM</option>
        </select><br><br>

        <label>Year:</label><br>
        <select name="year" required>
            <option value="">--Select Year--</option>
            <option value="1st year" <?php if($year=='1st year') echo 'selected'; ?>>1st year</option>
            <option value="2nd year" <?php if($year=='2nd year') echo 'selected'; ?>>2nd year</option>
            <option value="3rd year" <?php if($year=='3rd year') echo 'selected'; ?>>3rd year</option>
            <option value="4th year" <?php if($year=='4th year') echo 'selected'; ?>>4th year</option>
        </select><br><br>

        <label>Civil Status:</label><br>
        <select name="civilStatus" required>
            <option value="">--Select Civil Status--</option>
            <option value="single" <?php if($civilStatus=='single') echo 'selected'; ?>>Single</option>
            <option value="married" <?php if($civilStatus=='married') echo 'selected'; ?>>Married</option>
            <option value="widowed" <?php if($civilStatus=='widowed') echo 'selected'; ?>>Widowed</option>
        </select><br><br>

        <label>Gender:</label><br>
        <select name="gender" required>
            <option value="">--Select Gender--</option>
            <option value="male" <?php if($gender=='male') echo 'selected'; ?>>Male</option>
            <option value="female" <?php if($gender=='female') echo 'selected'; ?>>Female</option>
        </select><br><br>

        <label>Birthdate:</label><br>
        <input type="date" name="birthdate" value="<?php echo htmlspecialchars($birthdate); ?>" required><br><br>

        <label>Contact Number:</label><br>
        <input type="text" name="contactNo" value="<?php echo htmlspecialchars($contactNo); ?>" required><br><br>

        <label>Address:</label><br>
        <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" required><br><br>

        <input type="submit" value="Submit Application">
    </form>

    <p><a href="student.php">Back to Dashboard</a></p>
</body>
</html>
