<?php
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Admin') {
        include "../DB_connection.php";
        include "data/grade.php";
        include "data/section.php";
        $grades = getAllGrades($conn);
        $sections = getAllSections($conn);

        // Pre-fill form fields if they exist in GET request
        $fname = $_GET['fname'] ?? '';
        $lname = $_GET['lname'] ?? '';
        $uname = $_GET['uname'] ?? '';
        $address = $_GET['address'] ?? '';
        $email = $_GET['email'] ?? '';
        $pfn = $_GET['pfn'] ?? '';
        $pln = $_GET['pln'] ?? '';
        $ppn = $_GET['ppn'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php include "inc/navbar.php"; ?>
    <div class="container mt-5">
        <a href="student.php" class="btn btn-dark">Go Back</a>

        <form method="post" class="shadow p-3 mt-5 form-w" action="req/student-add.php" enctype="multipart/form-data">
            <h3>Add New Student</h3><hr>
            <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php } ?>
            <?php if (isset($_GET['success'])) { ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($_GET['success']) ?>
                </div>
            <?php } ?>

            <!-- Student Details -->
            <div class="mb-3">
                <label class="form-label">First name</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($fname) ?>" name="fname" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Last name</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($lname) ?>" name="lname" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($address) ?>" name="address" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control" value="<?= htmlspecialchars($email) ?>" name="email_address" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Date of birth</label>
                <input type="date" class="form-control" name="date_of_birth" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Gender</label><br>
                <input type="radio" value="Male" checked name="gender"> Male
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="Female" name="gender"> Female
            </div><br><hr>

            <!-- Login Credentials -->
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($uname) ?>" name="username" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="pass" id="passInput" required>
                    <button class="btn btn-secondary" id="gBtn">Random</button>
                </div>
            </div><br><hr>

            <!-- Parent Details -->
            <div class="mb-3">
                <label class="form-label">Parent first name</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($pfn) ?>" name="parent_fname" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Parent last name</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($pln) ?>" name="parent_lname" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Parent phone number</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($ppn) ?>" name="parent_phone_number" required>
            </div><br><hr>

            <!-- Grade and Section -->
            <div class="mb-3">
                <label class="form-label">Grade</label>
                <div class="row row-cols-5">
                    <?php foreach ($grades as $grade): ?>
                        <div class="col">
                            <input type="radio" name="grade" value="<?= $grade['grade_id'] ?>" required>
                            <?= htmlspecialchars($grade['grade_code']) ?>-<?= htmlspecialchars($grade['grade']) ?>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Section</label>
                <div class="row row-cols-5">
                    <?php foreach ($sections as $section): ?>
                        <div class="col">
                            <input type="radio" name="section" value="<?= $section['section_id'] ?>" required>
                            <?= htmlspecialchars($section['section']) ?>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <!-- New Fields -->
            <div class="mb-3">
                <label class="form-label">Profile Image</label>
                <input type="file" class="form-control" name="profile_image" accept="image/*">
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-control" name="status" required>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">User Type</label>
                <select class="form-control" name="user_type" required>
                    <option value="Student">Student</option>
                    <option value="Teacher">Teacher</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>    
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(3) a").addClass('active');
        });

        function makePass(length) {
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            var passInput = document.getElementById('passInput');
            passInput.value = result;
        }

        var gBtn = document.getElementById('gBtn');
        gBtn.addEventListener('click', function(e){
            e.preventDefault();
            makePass(8); // Generate an 8-character password
        });
    </script>
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