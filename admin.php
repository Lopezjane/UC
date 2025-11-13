<?php
// dean.php
session_start();
include 'db.php';

// You can add dean session validation here
// e.g., if(!isset($_SESSION['userName']) || $_SESSION['role'] != 'dean') { header("Location: login.php"); exit; }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dean Dashboard</title>
</head>
<body>
    <h2>Welcome</h2>

    <!-- Button to link to department.php -->
    <form action="department.php" method="get">
        <input type="submit" value="Manage Departments">
    </form>
    <form action="event.php" method="get">
        <input type="submit" value="Manage events">
    </form>

    <hr>

    <p>Other dean-specific functionalities can go here...</p>
</body>
</html>
