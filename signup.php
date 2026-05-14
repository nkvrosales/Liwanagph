<?php
require_once("Includes/session.php");
$nameErr = $phoneErr = $addrErr = $emailErr = $passwordErr = $confpasswordErr = $userErr = "";
$name = $email = $password = $confpassword = $address = $username = "";
$flag = 0;

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if(isset($_POST["reg_submit"])) {
    $name = test_input($_POST["name"]);
    $email = test_input($_POST['email']); 
    $username = test_input($_POST['username']);
    $password = $_POST["inputPassword"];
    $confpassword = $_POST["confirmPassword"];
    $address = test_input($_POST["address"]);
    $contactNo = test_input($_POST["contactNo"]);

    // NAME VALIDATION
    if (empty($name)) { $nameErr = "Name is required"; $flag=1; }
    
    // USERNAME VALIDATION
    if (empty($username)) { 
        $userErr = "Username is required"; 
        $flag=1; 
    } else {
        // Check if username exists
        require_once("Includes/config.php");
        $check = mysqli_query($con, "SELECT id FROM user WHERE username='$username'");
        if (mysqli_num_rows($check) > 0) {
            $userErr = "Username already taken";
            $flag = 1;
        }
    }

    // EMAIL VALIDATION
    if (empty($email)) { $emailErr = "Email is required"; $flag=1; }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $emailErr = "Invalid format"; $flag=1; }

    // PASSWORD VALIDATION
    if (empty($password)) { $passwordErr = "Required"; $flag=1; }
    if ($password !== $confpassword) { $confpasswordErr = "Mismatch"; $flag=1; }

    if($flag == 0) {
        require_once("Includes/config.php");
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (`name`, `username`, `email`, `phone`, `pass`, `address`)
                VALUES('$name', '$username', '$email', '$contactNo', '$hashed_pass', '$address')";
        if (mysqli_query($con, $sql)) {
            $_SESSION['reg_success'] = "Account created successfully! You can now sign in.";
            header("Location:index.php");
            exit;
        } else {
            die('Error: ' . mysqli_error($con));
        }
    }
}
?>

<form action="index.php" method="post" class="row g-3" role="form" id="signupForm">
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
            <input type="text" class="form-control <?php echo $nameErr?'is-invalid':''; ?>" name="name" placeholder="Full Name" value="<?php echo $name; ?>" required>
        </div>
        <?php if($nameErr): ?><small class="text-danger ms-1"><?php echo $nameErr; ?></small><?php endif; ?>
    </div>
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
            <input type="text" class="form-control <?php echo $userErr?'is-invalid':''; ?>" name="username" placeholder="Username" value="<?php echo $username; ?>" required>
        </div>
        <?php if($userErr): ?><small class="text-danger ms-1"><?php echo $userErr; ?></small><?php endif; ?>
    </div>
    <div class="col-12">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            <input type="email" class="form-control <?php echo $emailErr?'is-invalid':''; ?>" name="email" placeholder="Email Address" value="<?php echo $email; ?>" required>
        </div>
        <?php if($emailErr): ?><small class="text-danger ms-1"><?php echo $emailErr; ?></small><?php endif; ?>
    </div>
    <div class="col-12">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-key"></i></span>
            <input type="password" class="form-control" name="inputPassword" id="regPass" placeholder="Password" required>
            <span class="input-group-text bg-transparent" onclick="toggleRegPassword('regPass', 'regIcon')" style="cursor: pointer;">
                <i class="fas fa-eye text-muted" id="regIcon"></i>
            </span>
        </div>
    </div>
    <div class="col-12">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
            <input type="password" class="form-control <?php echo $confpasswordErr?'is-invalid':''; ?>" name="confirmPassword" id="confPass" placeholder="Confirm Password" required oninput="checkPassMatch()">
            <span class="input-group-text bg-transparent" onclick="toggleRegPassword('confPass', 'confIcon')" style="cursor: pointer;">
                <i class="fas fa-eye text-muted" id="confIcon"></i>
            </span>
        </div>
        <div id="passMatchMsg" class="mt-1 ms-1" style="font-size:0.82rem;"></div>
        <?php if($confpasswordErr): ?><small class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>Passwords do not match. Please try again.</small><?php endif; ?>
    </div>
    <script>
    function toggleRegPassword(inputId, iconId) {
        var p = document.getElementById(inputId);
        var icon = document.getElementById(iconId);
        if (p.type === 'password') { 
            p.type = 'text'; 
            icon.className = 'fas fa-eye-slash text-muted'; 
        } else { 
            p.type = 'password'; 
            icon.className = 'fas fa-eye text-muted'; 
        }
    }
    function checkPassMatch() {
        var pass = document.getElementById('regPass').value;
        var conf = document.getElementById('confPass').value;
        var msg  = document.getElementById('passMatchMsg');
        var inp  = document.getElementById('confPass');
        if (conf.length === 0) {
            msg.innerHTML = '';
            inp.classList.remove('is-invalid', 'is-valid');
            return;
        }
        if (pass === conf) {
            msg.innerHTML = '<span style="color:#5dde8f;"><i class="fas fa-check-circle me-1"></i>Passwords match!</span>';
            inp.classList.remove('is-invalid');
            inp.classList.add('is-valid');
        } else {
            msg.innerHTML = '<span style="color:#ff7070;"><i class="fas fa-exclamation-circle me-1"></i>Passwords do not match.</span>';
            inp.classList.remove('is-valid');
            inp.classList.add('is-invalid');
        }
    }
    document.getElementById('signupForm').addEventListener('submit', function(e) {
        var pass = document.getElementById('regPass').value;
        var conf = document.getElementById('confPass').value;
        if (pass !== conf) {
            e.preventDefault();
            document.getElementById('passMatchMsg').innerHTML = '<span style="color:#ff7070;"><i class="fas fa-exclamation-circle me-1"></i>Passwords do not match. Please fix before submitting.</span>';
            document.getElementById('confPass').classList.add('is-invalid');
            document.getElementById('confPass').focus();
        }
    });
    </script>
    <div class="col-12">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-phone"></i></span>
            <input type="tel" class="form-control" name="contactNo" placeholder="Phone number" required>
        </div>
    </div>
    <div class="col-12">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
            <input type="text" class="form-control" name="address" placeholder="Address" value="<?php echo $address; ?>" required>
        </div>
    </div>
    <div class="col-12 mt-4">
        <button name="reg_submit" type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">Create Account</button>
    </div>
</form>



