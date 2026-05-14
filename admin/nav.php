<?php 
    require_once ("../Includes/session.php") ;
?>
<nav class="navbar navbar-expand-lg navbar-dark glass-nav fixed-top px-4">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="index.php">
            liwanag.ph ADMIN
        </a>
        
        <div class="ms-auto d-flex align-items-center">
            <div class="dropdown">
                <a href="#" class="btn btn-outline-light btn-sm rounded-pill dropdown-toggle px-3" data-bs-toggle="dropdown">
                    <i class="fas fa-user-shield me-2"></i><?php echo $_SESSION['username'] ?? 'ADMIN'; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end glass-card mt-2 border-0 shadow">
                    <li>
                        <a class="dropdown-item py-2" href="logout.php">
                            <i class="fas fa-power-off me-2 text-danger"></i>Log Out
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
