<?php
session_start();
include 'db.php';

// OPTIONAL: Check if admin is logged in
// if (!isset($_SESSION['adminUser'])) {
//     header("Location: login.php");
//     exit();
// }

// Handle Approve / Reject actions
if (isset($_GET['action']) && isset($_GET['idNum'])) {

    $id = $_GET['idNum'];
    $action = $_GET['action'];

    if ($action == "approve" || $action == "reject") {
        $status = ($action == "approve") ? "approved" : "rejected";

        $stmt = $conn->prepare("UPDATE athletesprofile SET status=? WHERE idNum=?");
        $stmt->bind_param("ss", $status, $id);
        
        if ($stmt->execute()) {
            // Success
            $stmt->close();
        } else {
            echo "Error updating record: " . $conn->error;
            exit();
        }
    }

    header("Location: admin_athletes.php");
    exit();
}

// Fetch all applications
$sql = "SELECT a.*, e.eventName, d.deptName
        FROM athletesprofile a
        LEFT JOIN event e ON a.eventID = e.eventID
        LEFT JOIN department d ON a.deptID = d.deptID
        ORDER BY a.status ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Athlete Applicants</title>
</head>
<body>

<h2>Student Athlete Applicants</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID Number</th>
        <th>Full Name</th>
        <th>Event</th>
        <th>Department</th>
        <th>Coach</th>
        <th>Dean</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>

        <tr>
            <td><?= htmlspecialchars($row['idNum']); ?></td>
            <td><?= htmlspecialchars($row['lastName'] . ", " . $row['firstName'] . " " . $row['middleName']); ?></td>
            <td><?= htmlspecialchars($row['eventName']); ?></td>
            <td><?= htmlspecialchars($row['deptName']); ?></td>
            <td><?= htmlspecialchars($row['coachID']); ?></td>
            <td><?= htmlspecialchars($row['deanID']); ?></td>
            <td>
                <?php 
                    if ($row['status'] == "pending") {
                        echo "<span style='color: orange;'>Pending</span>";
                    } elseif ($row['status'] == "approved") {
                        echo "<span style='color: green;'>Approved</span>";
                    } else {
                        echo "<span style='color: red;'>Rejected</span>";
                    }
                ?>
            </td>
            <td>
                <?php if ($row['status'] == "pending"): ?>
                    <a href="admin_athletes.php?action=approve&idNum=<?= urlencode($row['idNum']); ?>" 
                       onclick="return confirm('Approve this application?');">Approve</a>
                    |
                    <a href="admin_athletes.php?action=reject&idNum=<?= urlencode($row['idNum']); ?>" 
                       onclick="return confirm('Reject this application?');">Reject</a>
                <?php else: ?>
                    <i>No action available</i>
                <?php endif; ?>
            </td>
        </tr>

        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="8">No applicants found.</td></tr>
    <?php endif; ?>
</table>

</body>
</html>
