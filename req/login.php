<?php 
session_start();

if (isset($_POST['uname']) && isset($_POST['pass']) && isset($_POST['role'])) {

    include "../DB_connection.php";
    
    $uname = $_POST['uname'];
    $pass = $_POST['pass'];
    $role = $_POST['role'];
    $remember_me = isset($_POST['remember_me_token']) ? $_POST['remember_me_token'] : '';

    if (empty($uname)) {
        $em  = "Username is required";
        header("Location: ../login.php?error=$em");
        exit;
    } else if (empty($pass)) {
        $em  = "Password is required";
        header("Location: ../login.php?error=$em");
        exit;
    } else if (empty($role)) {
        $em  = "An error occurred";
        header("Location: ../login.php?error=$em");
        exit;
    } else {
        // SQL query based on role
        if ($role == '1') {
            $sql = "SELECT * FROM admin WHERE username = ?";
            $role = "Admin";
        } else if ($role == '2') {
            $sql = "SELECT * FROM teachers WHERE username = ?";
            $role = "Teacher";
        } else if ($role == '3') {
            $sql = "SELECT * FROM students WHERE username = ?";
            $role = "Student";
        } else if ($role == '4') {
            $sql = "SELECT * FROM registrar_office WHERE username = ?";
            $role = "Registrar Office";
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute([$uname]);

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch();
            $username = $user['username'];
            $password = $user['password'];

            if ($username === $uname) {
                if (password_verify($pass, $password)) {
                    $_SESSION['role'] = $role;

                    // Set session variables based on role
                    if ($role == 'Admin') {
                        $id = $user['admin_id'];
                        $_SESSION['admin_id'] = $id;
                        header("Location: ../admin/index.php");
                        exit;
                    } else if ($role == 'Student') {
                        $id = $user['student_id'];
                        $_SESSION['student_id'] = $id;
                        header("Location: ../Student/index.php");
                        exit;
                    } else if ($role == 'Registrar Office') {
                        $id = $user['r_user_id'];
                        $_SESSION['r_user_id'] = $id;
                        header("Location: ../RegistrarOffice/index.php");
                        exit;
                    } else if ($role == 'Teacher') {
                        $id = $user['teacher_id'];
                        $_SESSION['teacher_id'] = $id;
                        header("Location: ../Teacher/index.php");
                        exit;
                    }

                    // Remember Me functionality
                    if ($remember_me == 'on') {
                        // Set cookies for username and password (encrypted)
                        setcookie('uname', $uname, time() + (86400 * 30), "/"); // Expires in 30 days
                        setcookie('pass', $pass, time() + (86400 * 30), "/"); // Expires in 30 days
                    }

                } else {
                    $em  = "Incorrect Username or Password";
                    header("Location: ../login.php?error=$em");
                    exit;
                }
            } else {
                $em  = "Incorrect Username or Password";
                header("Location: ../login.php?error=$em");
                exit;
            }
        } else {
            $em  = "Incorrect Username or Password";
            header("Location: ../login.php?error=$em");
            exit;
        }
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>
