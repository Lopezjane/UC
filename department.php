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



// with design
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Departments</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        h2, h3 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .form-container {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        
        .btn-warning {
            background-color: #ffc107;
            color: black;
        }
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f2f2f2;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        hr {
            margin: 20px 0;
            border: 0;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin - Department Management</h2>
        
        <?php
        include 'db.php';

        // Add Department
        if (isset($_POST['add_department'])) {
            $deptID = $_POST['deptID'];
            $deptName = $_POST['deptName'];

            if (!empty($deptID) && !empty($deptName)) {
                $check = $conn->query("SELECT * FROM department WHERE deptID = '$deptID'");
                if ($check->num_rows > 0) {
                    echo "<div class='message error'>Department ID already exists.</div>";
                } else {
                    $insert = "INSERT INTO department (deptID, deptName) VALUES ('$deptID', '$deptName')";
                    if ($conn->query($insert) === TRUE) {
                        echo "<div class='message success'>Department added successfully.</div>";
                    } else {
                        echo "<div class='message error'>Error: " . $conn->error . "</div>";
                    }
                }
            } else {
                echo "<div class='message error'>Please fill up all fields.</div>";
            }
        }

        // Update Department
        if (isset($_POST['update_department'])) {
            $deptID = $_POST['deptID'];
            $deptName = $_POST['deptName'];

            $update = "UPDATE department SET deptName='$deptName' WHERE deptID='$deptID'";
            if ($conn->query($update) === TRUE) {
                echo "<div class='message success'>Department updated successfully.</div>";
            } else {
                echo "<div class='message error'>Error updating department: " . $conn->error . "</div>";
            }
        }

        // Delete Department
        if (isset($_GET['delete'])) {
            $deptID = $_GET['delete'];
            $delete = "DELETE FROM department WHERE deptID='$deptID'";
            if ($conn->query($delete) === TRUE) {
                echo "<div class='message success'>Department deleted successfully.</div>";
            } else {
                echo "<div class='message error'>Error deleting department: " . $conn->error . "</div>";
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

        <!-- Add or Update Department -->
        <div class="form-container">
            <h3>Add/Update Department</h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Department ID:</label>
                    <input type="text" name="deptID" required>
                </div>
                
                <div class="form-group">
                    <label>Department Name:</label>
                    <input type="text" name="deptName" required>
                </div>
                
                <button type="submit" name="add_department" class="btn btn-primary">Add Department</button>
                <button type="submit" name="update_department" class="btn btn-warning">Update Department</button>
            </form>
        </div>

        <hr>

        <!-- Search Department -->
        <div class="form-container">
            <h3>Search Department</h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Search Department:</label>
                    <input type="text" name="searchTerm" value="<?php echo $searchTerm; ?>" placeholder="Enter Department ID or Name">
                </div>
                
                <button type="submit" name="search" class="btn btn-success">Search</button>
                <a href="admin.php"><button type="button" class="btn">Show All</button></a>
            </form>
        </div>

        <hr>

        <!-- Display Departments -->
        <h3>Department List</h3>
        <table>
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
                                <a href='admin.php?delete={$row['deptID']}' onclick=\"return confirm('Delete this department?');\" class='btn btn-danger'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No departments found.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>