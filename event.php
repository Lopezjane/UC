<?php
include 'db.php';

// Add Event
if (isset($_POST['add_event'])) {
    $eventID = $_POST['eventID'];
    $category = $_POST['category'];
    $eventName = $_POST['eventName'];
    $noOfParticipant = $_POST['noOfParticipant'];
    $tournamentManager = $_POST['tournamentManager'];

    if (!empty($eventID) && !empty($category) && !empty($eventName) && !empty($noOfParticipant) && !empty($tournamentManager)) {
        $check = $conn->query("SELECT * FROM event WHERE eventID = '$eventID'");
        if ($check->num_rows > 0) {
            echo "Event ID already exists.<br>";
        } else {
            $insert = "INSERT INTO event(eventID, category, eventName, noOfParticipant, tournamentManager)
                       VALUES ('$eventID', '$category', '$eventName', '$noOfParticipant', '$tournamentManager')";
            if ($conn->query($insert) === TRUE) {
                echo "Event added successfully.<br>";
            } else {
                echo "Error: " . $conn->error . "<br>";
            }
        }
    } else {
        echo "Please fill up all fields.<br>";
    }
}

// Update Event
if (isset($_POST['update_event'])) {
    $eventID = $_POST['eventID'];
    $category = $_POST['category'];
    $eventName = $_POST['eventName'];
    $noOfParticipant = $_POST['noOfParticipant'];
    $tournamentManager = $_POST['tournamentManager'];

    $update = "UPDATE event 
               SET category='$category', eventName='$eventName', noOfParticipant='$noOfParticipant', tournamentManager='$tournamentManager'
               WHERE eventID='$eventID'";
    if ($conn->query($update) === TRUE) {
        echo "Event updated successfully.<br>";
    } else {
        echo "Error updating event: " . $conn->error . "<br>";
    }
}

// Delete Event
if (isset($_GET['delete'])) {
    $eventID = $_GET['delete'];
    $delete = "DELETE FROM event WHERE eventID='$eventID'";
    if ($conn->query($delete) === TRUE) {
        echo "Event deleted successfully.<br>";
    } else {
        echo "Error deleting event: " . $conn->error . "<br>";
    }
}

// Search Event
$searchTerm = "";
if (isset($_POST['search'])) {
    $searchTerm = $_POST['searchTerm'];
    $events = $conn->query("SELECT e.*, CONCAT(tm.fName, ' ', tm.lName) AS managerName, tm.userName AS managerUserName
                            FROM event e
                            LEFT JOIN tournamentmanager tm ON e.tournamentManager = tm.userName
                            WHERE e.eventID LIKE '%$searchTerm%' 
                               OR e.eventName LIKE '%$searchTerm%' 
                               OR e.category LIKE '%$searchTerm%' 
                               OR tm.fName LIKE '%$searchTerm%' 
                               OR tm.lName LIKE '%$searchTerm%' 
                               OR tm.userName LIKE '%$searchTerm%'");
} else {
    $events = $conn->query("SELECT e.*, CONCAT(tm.fName, ' ', tm.lName) AS managerName, tm.userName AS managerUserName
                            FROM event e
                            LEFT JOIN tournamentmanager tm ON e.tournamentManager = tm.userName");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Events</title>
</head>
<body>
    <h2>Admin - Event Management</h2>

    <!-- Add or Update Event Form -->
    <form method="POST" action="">
        <label>Event ID:</label><br>
        <input type="text" name="eventID" required><br><br>

        <label>Category:</label><br>
        <select name="category" required>
            <option value="">--Select Category--</option>
            <option value="Athletic">Athletic</option>
            <option value="Cultural">Cultural</option>
            <option value="Academic">Academic</option>
        </select><br><br>

        <label>Event Name:</label><br>
        <input type="text" name="eventName" required><br><br>

        <label>No. of Participants:</label><br>
        <input type="number" name="noOfParticipant" required><br><br>

        <label>Tournament Manager:</label><br>
        <select name="tournamentManager" required>
            <option value="">--Select Manager--</option>
            <?php
            $managerResult = $conn->query("SELECT userName, fName, lName FROM tournamentmanager");
            if ($managerResult->num_rows > 0) {
                while ($row = $managerResult->fetch_assoc()) {
                    $fullName = $row['fName'] . ' ' . $row['lName'];
                    echo "<option value='{$row['userName']}'>{$fullName} ({$row['userName']})</option>";
                }
            }
            ?>
        </select><br><br>

        <input type="submit" name="add_event" value="Add Event">
        <input type="submit" name="update_event" value="Update Event">
    </form>

    <hr>

    <!-- Search Event -->
    <form method="POST" action="">
        <label>Search Event:</label><br>
        <input type="text" name="searchTerm" value="<?php echo $searchTerm; ?>" placeholder="Event ID, Name, Category or Manager">
        <input type="submit" name="search" value="Search">
        <input type="submit" value="Show All">
    </form>

    <hr>

    <!-- Display Events -->
    <h3>Event List</h3>
    <table border="1" cellpadding="5">
        <tr>
            <th>Event ID</th>
            <th>Category</th>
            <th>Event Name</th>
            <th>No. of Participants</th>
            <th>Tournament Manager</th>
            <th>Action</th>
        </tr>

        <?php
        if ($events && $events->num_rows > 0) {
            while ($row = $events->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['eventID']}</td>
                        <td>{$row['category']}</td>
                        <td>{$row['eventName']}</td>
                        <td>{$row['noOfParticipant']}</td>
                        <td>{$row['managerName']} ({$row['tournamentManager']})</td>
                        <td>
                            <a href='event.php?delete={$row['eventID']}' onclick=\"return confirm('Delete this event?');\">Delete</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No events found.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
