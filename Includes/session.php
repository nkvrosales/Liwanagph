<!-- username login logic -->
<?php  
    require_once("config.php");
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $logged = false;
    if(isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
        $logged = true ;
        $username = $_SESSION['username'] ?? $_SESSION['email'] ?? "";
    } else {
        $logged = false;
    }

    if(!$logged) {
        if (isset($_POST['username']) && isset($_POST['pass'])) {
            // IMPORTANT: Only escape the username for SQL. The password must NEVER
            // be escaped — it is used exclusively with password_verify() which 
            // handles the raw string directly. Escaping corrupts special characters.
            $username = mysqli_real_escape_string($con, trim($_POST['username']));
            $password = $_POST['pass']; // raw password for password_verify()
            
            // 1. Try logging in as User (Check username OR email)
            $sql = "SELECT * FROM user WHERE username='$username' OR email='$username' OR name='$username'";
            $result = mysqli_query($con, $sql);
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                if (password_verify($password, $row['pass']) || $password === $row['pass']) {
                    $_SESSION['user'] = $row['name'];
                    $_SESSION['logged'] = true;
                    $_SESSION['uid'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['account'] = "user";
                    log_action($_SESSION['uid'], "Login", "User logged into the system");
                    unset($_SESSION['login_error']);
                    header("Location:user/index.php");
                    exit;
                }
            }
            
            // 2. Try logging in as Admin
            $sql = "SELECT * FROM admin WHERE name='$username' OR email='$username'";
            $result = mysqli_query($con, $sql);
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                if (password_verify($password, $row['pass']) || $password === $row['pass']) {
                    $_SESSION['logged'] = true;
                    $_SESSION['username'] = $row['name'];
                    $_SESSION['aid'] = $row['id'];
                    $_SESSION['account'] = "admin";
                    log_action($_SESSION['aid'], "Admin Login", "Administrator logged into the system", "admin");
                    unset($_SESSION['login_error']);
                    header("Location:admin/index.php");
                    exit;
                }
            }

            // 3. If we reach here, login failed
            $_SESSION['login_error'] = "User not found or invalid credentials.";
            header("Location: index.php");
            exit;
        }
    }
?>