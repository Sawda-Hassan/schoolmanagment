<?php 
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Admin') {

        // Database connection using PDO
        include "../DB_connection.php"; // Make sure the path is correct

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Function to execute query and handle errors with PDO
        function executeQuery($conn, $query) {
            $stmt = $conn->prepare($query); // Prepare the query
            if (!$stmt) {
                die("Prepare failed: " . $conn->errorInfo());
            }
            $stmt->execute(); // Execute the query
            return $stmt; // Return the prepared statement object
        }

        // Queries to count teachers, students, and classes
        $teacher_count_query = "SELECT COUNT(*) AS count FROM teachers";
        $student_count_query = "SELECT COUNT(*) AS count FROM students";
        $class_count_query = "SELECT COUNT(*) AS count FROM class";

        // Execute queries and handle results using PDO
        $teacher_count_result = executeQuery($conn, $teacher_count_query);
        $student_count_result = executeQuery($conn, $student_count_query);
        $class_count_result = executeQuery($conn, $class_count_query);

        // Fetch the counts from PDO result
        $teacher_count = $teacher_count_result->fetch(PDO::FETCH_ASSOC)['count'];
        $student_count = $student_count_result->fetch(PDO::FETCH_ASSOC)['count'];
        $class_count = $class_count_result->fetch(PDO::FETCH_ASSOC)['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="./logo1.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .sidebar {
            background-color: #162381;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 15px;
            border-radius: 5px;
            margin: 5px 10px;
        }
        .sidebar a:hover {
            background-color: #1a237e;
        }
        .sidebar .active {
            background-color: #3949ab;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            flex: 1;
        }
        .header {
            background-color: #162381;
            color: white;
            padding: 15px;
        }
        .footer {
            background-color: #162381;
            color: white;
            text-align: center;
            padding: 10px;
            position: relative;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center">Admin Dashboard</h4>
        <a href="teacher.php" class="active">
            <i class="fa fa-user-md"></i> Teachers
        </a>
        <a href="student.php">
            <i class="fa fa-graduation-cap"></i> Students
        </a>
        <a href="registrar-office.php">
            <i class="fa fa-pencil-square"></i> Registrar Office
        </a>
        <a href="class.php">
            <i class="fa fa-cubes"></i> Class
        </a>
        <a href="course.php">
            <i class="fa fa-book"></i> Course
        </a>
        <a href="../logout.php" class="text-danger">
            <i class="fa fa-sign-out"></i> Logout
        </a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <h3>Welcome, Admin</h3>
        </div>

        <!-- Dashboard Cards -->
        <div class="container mt-4">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Teachers</h5>
                            <p class="card-text fs-2"><?php echo $teacher_count; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Students</h5>
                            <p class="card-text fs-2"><?php echo $student_count; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Classes</h5>
                            <p class="card-text fs-2"><?php echo $class_count; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> Admin Dashboard. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php 
    } else {
        header("Location: ../login.php");
        exit;
    } 
} else {
    header("Location: ../login.php");
    exit;
} 
?>
