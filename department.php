<?php
include 'db.php';

// Add Department
if (isset($_POST['add_department'])) {
    $deptID = $_POST['deptID'];
    $deptName = $_POST['deptName'];

    if (!empty($deptID) && !empty($deptName)) {
        $check = $conn->query("SELECT * FROM department WHERE deptID = '$deptID'");
        if ($check->num_rows > 0) {
            echo "Department ID already exists.<br>";
        } else {
            $insert = "INSERT INTO department (deptID, deptName) VALUES ('$deptID', '$deptName')";
            if ($conn->query($insert) === TRUE) {
                echo "Department added successfully.<br>";
            } else {
                echo "Error: " . $conn->error . "<br>";
            }
        }
    } else {
        echo "Please fill up all fields.<br>";
    }
}

// Update Department
if (isset($_POST['update_department'])) {
    $deptID = $_POST['deptID'];
    $deptName = $_POST['deptName'];

    $update = "UPDATE department SET deptName='$deptName' WHERE deptID='$deptID'";
    if ($conn->query($update) === TRUE) {
        echo "Department updated successfully.<br>";
    } else {
        echo "Error updating department: " . $conn->error . "<br>";
    }
}

// Delete Department
if (isset($_GET['delete'])) {
    $deptID = $_GET['delete'];
    $delete = "DELETE FROM department WHERE deptID='$deptID'";
    if ($conn->query($delete) === TRUE) {
        echo "Department deleted successfully.<br>";
    } else {
        echo "Error deleting department: " . $conn->error . "<br>";
    }
}

// Search Department
$searchTerm = "";
if (isset($_POST['search'])) {
    $searchTerm = $_POST['searchTerm'];
    $departments = $conn->query("SELECT * FROM department WHERE deptID LIKE '%$searchTerm%' OR deptName LIKE '%$searchTerm%'");
} else {
    $departments = $conn->query("SELECT * FROM department");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Departments</title>
</head>
<body>

<h2>Admin - Department Management</h2>

<!-- Add or Update Department -->
<form method="POST" action="">
    <label>Department ID:</label><br>
    <input type="text" name="deptID" required><br><br>

    <label>Department Name:</label><br>
    <input type="text" name="deptName" required><br><br>

    <input type="submit" name="add_department" value="Add Department">
    <input type="submit" name="update_department" value="Update Department">
</form>

<hr>

<!-- Search Department -->
<form method="POST" action="">
    <label>Search Department:</label><br>
    <input type="text" name="searchTerm" value="<?php echo $searchTerm; ?>" placeholder="Enter Department ID or Name">
    <input type="submit" name="search" value="Search">
    <input type="submit" value="Show All">
</form>

<hr>

<!-- Display Departments -->
<h3>Department List</h3>
<table border="1" cellpadding="5">
    <tr>
        <th>Department ID</th>
        <th>Department Name</th>
        <th>Action</th>
    </tr>

    <?php
    if ($departments && $departments->num_rows > 0) {
        while ($row = $departments->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['deptID']}</td>
                    <td>{$row['deptName']}</td>
                    <td>
                        <a href='admin.php?delete={$row['deptID']}' onclick=\"return confirm('Delete this department?');\">Delete</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No departments found.</td></tr>";
    }
    ?>
</table>

</body>
</html>
