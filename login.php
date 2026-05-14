<?php
  require_once("Includes/config.php");
  require_once("Includes/session.php");
?>

<div>
<form action="index.php" class="d-flex align-items-center gap-2" role="form" method="post">
    <div class="input-group" style="width: 180px; height: 34px;">
        <span class="input-group-text"><i class="fas fa-user"></i></span>
        <input type="text" placeholder="Username" name="username" id="username" class="form-control" required>
    </div>
    <div class="input-group" style="width: 180px; height: 34px;">
        <span class="input-group-text"><i class="fas fa-lock"></i></span>
        <input type="password" placeholder="Password" name="pass" id="pass" class="form-control" required>
        <span class="input-group-text" onclick="togglePassword()" style="cursor: pointer;">
            <i class="fas fa-eye small text-muted" id="toggleIcon"></i>
        </span>
    </div>
    <script>
    function togglePassword() {
        var p = document.getElementById('pass');
        var icon = document.getElementById('toggleIcon');
        if (p.type === 'password') { 
            p.type = 'text'; 
            icon.className = 'fas fa-eye-slash small text-muted'; 
        } else { 
            p.type = 'password'; 
            icon.className = 'fas fa-eye small text-muted'; 
        }
    }
    </script>
    <button type="submit" name="login_submit" class="btn btn-primary btn-sm rounded-pill fw-bold" style="width: 160px; height: 34px;">Sign In</button>
</form>
<?php if(isset($_SESSION['login_error'])): ?>
<div class="mt-2 px-1" style="font-size: 0.75rem; color: #ff8080; line-height: 1.2;">
    <i class="fas fa-exclamation-circle me-1"></i> <?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
</div>
<?php endif; ?>
</div>
