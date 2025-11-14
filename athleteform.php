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
$coachID = $deanID = "";
$status = 'pending';
$message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
    $coachID = $conn->real_escape_string($_POST['coachID']);
    $deanID = $conn->real_escape_string($_POST['deanID']);

    // Check if student already applied
    $checkSql = "SELECT * FROM athletesprofile WHERE idNum='$idNum'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        // Update existing record
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
            coachID='$coachID',
            deanID='$deanID',
            status='pending'
            WHERE idNum='$idNum'";

        if ($conn->query($updateSql) === TRUE) {
            $message = "Application updated successfully! Waiting for approval.";
        } else {
            $message = "Error updating application: " . $conn->error;
        }

    } else {
        // Insert new record
        $insertSql = "INSERT INTO athletesprofile (
            idNum, eventID, deptID, lastName, firstName, middleName,
            course, year, civilStatus, gender, birthdate, contactNo, address,
            coachID, deanID, status
        ) VALUES (
            '$idNum', '$eventID', '$deptID', '$lastName', '$firstName', '$middleName',
            '$course', '$year', '$civilStatus', '$gender', '$birthdate', '$contactNo', '$address',
            '$coachID', '$deanID', 'pending'
        )";

        if ($conn->query($insertSql) === TRUE) {
            $message = "Application submitted successfully! Waiting for approval.";
        } else {
            $message = "Error submitting application: " . $conn->error;
        }
    }

} else {

    // Prefill fields if record exists
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
        $coachID = $row['coachID'];
        $deanID = $row['deanID'];
        $status = $row['status'];
    }
}

// FETCH DROPDOWN DATA
$eventResult = $conn->query("SELECT eventID, eventName FROM event");
$deptResult = $conn->query("SELECT deptID, deptName FROM department");
$coachResult = $conn->query("SELECT userName FROM coach");
$deanResult = $conn->query("SELECT userName FROM dean");
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
    <input type="text" value="<?= $idNum; ?>" readonly><br><br>

    <label>Event:</label><br>
    <select name="eventID" required>
        <option value="">-- Select Event --</option>
        <?php while ($row = $eventResult->fetch_assoc()): ?>
            <option value="<?= $row['eventID']; ?>" <?= ($row['eventID']==$eventID ? "selected" : ""); ?>>
                <?= $row['eventName']; ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Department:</label><br>
    <select name="deptID" required>
        <option value="">-- Select Department --</option>
        <?php while ($row = $deptResult->fetch_assoc()): ?>
            <option value="<?= $row['deptID']; ?>" <?= ($row['deptID']==$deptID ? "selected" : ""); ?>>
                <?= $row['deptName']; ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Coach:</label><br>
    <select name="coachID" required>
        <option value="">-- Select Coach --</option>
        <?php while ($row = $coachResult->fetch_assoc()): ?>
            <option value="<?= $row['userName']; ?>" <?= ($row['userName']==$coachID ? "selected" : ""); ?>>
                <?= $row['userName']; ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Dean:</label><br>
    <select name="deanID" required>
        <option value="">-- Select Dean --</option>
        <?php while ($row = $deanResult->fetch_assoc()): ?>
            <option value="<?= $row['userName']; ?>" <?= ($row['userName']==$deanID ? "selected" : ""); ?>>
                <?= $row['userName']; ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Last Name:</label><br>
    <input type="text" name="lastName" value="<?= $lastName; ?>" required><br><br>

    <label>First Name:</label><br>
    <input type="text" name="firstName" value="<?= $firstName; ?>" required><br><br>

    <label>Middle Name:</label><br>
    <input type="text" name="middleName" value="<?= $middleName; ?>"><br><br>

    <label>Course:</label><br>
    <select name="course" required>
        <option value="">-- Select Course --</option>
        <option value="BSIT" <?= ($course=="BSIT"?"selected":""); ?>>BSIT</option>
        <option value="BSCS" <?= ($course=="BSCS"?"selected":""); ?>>BSCS</option>
        <option value="BPED" <?= ($course=="BPED"?"selected":""); ?>>BPED</option>
        <option value="HM" <?= ($course=="HM"?"selected":""); ?>>HM</option>
    </select><br><br>

    <label>Year:</label><br>
    <select name="year" required>
        <option value="">-- Select Year --</option>
        <option value="1st year" <?= ($year=="1st year"?"selected":""); ?>>1st year</option>
        <option value="2nd year" <?= ($year=="2nd year"?"selected":""); ?>>2nd year</option>
        <option value="3rd year" <?= ($year=="3rd year"?"selected":""); ?>>3rd year</option>
        <option value="4th year" <?= ($year=="4th year"?"selected":""); ?>>4th year</option>
    </select><br><br>

    <label>Civil Status:</label><br>
    <select name="civilStatus" required>
        <option value="">-- Select --</option>
        <option value="single" <?= ($civilStatus=="single"?"selected":""); ?>>Single</option>
        <option value="married" <?= ($civilStatus=="married"?"selected":""); ?>>Married</option>
        <option value="widowed" <?= ($civilStatus=="widowed"?"selected":""); ?>>Widowed</option>
    </select><br><br>

    <label>Gender:</label><br>
    <select name="gender" required>
        <option value="">-- Select --</option>
        <option value="male" <?= ($gender=="male"?"selected":""); ?>>Male</option>
        <option value="female" <?= ($gender=="female"?"selected":""); ?>>Female</option>
    </select><br><br>

    <label>Birthdate:</label><br>
    <input type="date" name="birthdate" value="<?= $birthdate; ?>" required><br><br>

    <label>Contact Number:</label><br>
    <input type="text" name="contactNo" value="<?= $contactNo; ?>" required><br><br>

    <label>Address:</label><br>
    <input type="text" name="address" value="<?= $address; ?>" required><br><br>

    <input type="submit" value="Submit Application">
</form>

<p><a href="student.php">Back to Dashboard</a></p>

</body>
</html>
