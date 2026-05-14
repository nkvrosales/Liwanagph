<?php 
require_once("Includes/config.php");
require_once("Includes/session.php");

if(isset($_SESSION['logged']))
{
    if ($_SESSION['logged'] == true)
    {
        if ($_SESSION['account']=="admin") {
                header("Location:admin/index.php");
            }
        elseif ($_SESSION['account']=="user") {
                header("Location:user/index.php");
            }
    }  
    else  {
        header("Location:../index.php");
      }  
}

if(isset($_POST['login_submit'])) {
    if(!(isset($_POST['username']))) {
        if(!(isset($_POST['pass']))) {
            header('Location: index.php');    
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>liwanag.ph | Modern Energy Management</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Modern styles -->
    <link href="assets/css/modern.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top glass-nav">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                liwanag.ph
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <div class="ms-auto">
                    <?php include("login.php"); ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header id="headerwrap">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0 fade-in">
                    <?php if(isset($_SESSION['reg_success'])): ?>
                    <div class="alert alert-success glass-card border-0 mb-4 fade-in" role="alert" style="background: rgba(25, 135, 84, 0.2); color: #d1e7dd; border: 1px solid rgba(25, 135, 84, 0.3) !important;">
                        <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['reg_success']; unset($_SESSION['reg_success']); ?>
                    </div>
                    <?php endif; ?>
                    <div class="hero-content">
                        <h1 class="display-3 fw-bold mb-4"><span class="text-gradient">liwanag</span>.ph</h1>
                        <p class="lead mb-5 opacity-75">Sparking clarity in every bill.</p>
                        <div class="d-flex gap-3">
                            <a href="#how-it-works" class="btn btn-outline-light btn-lg px-4 rounded-pill">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1 fade-in" style="animation-delay: 0.2s;">
                    <div class="glass-card p-5">
                        <h2 class="h3 fw-bold mb-4">Create Account</h2>
                        <?php include("signup.php"); ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section id="how-it-works" class="py-5">
        <div class="container py-5">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-5 fw-bold mb-3">How it Works</h2>
                    <p class="text-muted">Simple steps to manage your bills effectively.</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="glass-card p-4 h-100 text-center transition-hover shadow-sm">
                        <div class="icon-box mb-4 mx-auto" style="width: 80px; height: 80px; background: rgba(13, 110, 253, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-shield fa-2x text-primary"></i>
                        </div>
                        <h4 class="fw-bold">1. Secure Access</h4>
                        <p class="opacity-75">Sign in safely with our encrypted authentication system to manage your personal profile.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="glass-card p-4 h-100 text-center transition-hover shadow-sm">
                        <div class="icon-box mb-4 mx-auto" style="width: 80px; height: 80px; background: rgba(13, 202, 240, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-chart-line fa-2x text-info"></i>
                        </div>
                        <h4 class="fw-bold">2. Monthly Clarity</h4>
                        <p class="opacity-75">Track your monthly energy consumption with precise unit records and historical billing data.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="glass-card p-4 h-100 text-center transition-hover shadow-sm">
                        <div class="icon-box mb-4 mx-auto" style="width: 80px; height: 80px; background: rgba(25, 135, 84, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-wallet fa-2x text-success"></i>
                        </div>
                        <h4 class="fw-bold">3. Instant Settlement</h4>
                        <p class="opacity-75">Settle your pending bills instantly and keep your transaction history always up to date.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 glass-nav mt-auto">
        <div class="container text-center text-muted">
            <p>&copy; <?php echo date('Y'); ?> liwanag.ph.</p>
        </div>
    </footer>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery-1.11.0.js"></script>
</body>
</html>
