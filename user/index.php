<?php 
    require_once('head_html.php'); 
    require_once('../Includes/config.php'); 
    require_once('../Includes/session.php'); 
    require_once('../Includes/user.php'); 
    if ($logged==false) { header("Location:../index.php"); }

    $uid = $_SESSION['uid'];
    $r1 = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM bill WHERE uid=$uid"));
    $r2 = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM bill WHERE uid=$uid AND status='PROCESSED'"));
    $r3 = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM audit_logs WHERE user_id=$uid"));
    $total_computed  = $r1[0];
    $total_paid      = $r2[0];
    $total_actions   = $r3[0];
    $user_name = $_SESSION['user'] ?? 'User';
?>

<body>
<div id="wrapper">
    <?php require_once("nav.php"); require_once("sidebar.php"); ?>

    <div id="page-content-wrapper" class="mt-5 pt-4">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="fw-bold">Welcome, <span class="text-gradient"><?php echo htmlspecialchars($user_name); ?></span> <small class="text-muted fw-light h5 ms-2">Dashboard</small></h2>
                    <hr class="opacity-10">
                </div>
            </div>

            <!-- Stats -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card card-stats h-100 shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="icon-shape bg-primary bg-opacity-20 text-primary rounded-3 p-3">
                                    <i class="fas fa-calculator fa-2x"></i>
                                </div>
                                <div class="text-end">
                                    <h3 class="fw-bold mb-0"><?php echo $total_computed; ?></h3>
                                    <span class="text-muted small">Bills Computed</span>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="compute_bill.php" class="btn btn-sm btn-link text-primary p-0 text-decoration-none">Compute New <i class="fas fa-chevron-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-stats h-100 shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="icon-shape bg-success bg-opacity-20 text-success rounded-3 p-3">
                                    <i class="fas fa-check-double fa-2x"></i>
                                </div>
                                <div class="text-end">
                                    <h3 class="fw-bold mb-0"><?php echo $total_paid; ?></h3>
                                    <span class="text-muted small">Paid Bills</span>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="bill.php" class="btn btn-sm btn-link text-success p-0 text-decoration-none">View History <i class="fas fa-chevron-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-stats h-100 shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="icon-shape bg-warning bg-opacity-20 text-warning rounded-3 p-3">
                                    <i class="fas fa-history fa-2x"></i>
                                </div>
                                <div class="text-end">
                                    <h3 class="fw-bold mb-0"><?php echo $total_actions; ?></h3>
                                    <span class="text-muted small">Actions Logged</span>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="trails.php" class="btn btn-sm btn-link text-warning p-0 text-decoration-none">View Activity <i class="fas fa-chevron-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions + Rate Guide -->
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="glass-card p-4 h-100">
                        <h5 class="fw-bold mb-4"><i class="fas fa-rocket me-2 text-info"></i>Quick Actions</h5>
                        <div class="d-grid gap-3">
                            <a href="compute_bill.php" class="btn btn-primary py-3 rounded-pill fw-semibold">
                                <i class="fas fa-calculator me-2"></i>Compute Electric Bill
                            </a>
                            <a href="bill.php" class="btn btn-outline-success py-3 rounded-pill fw-semibold">
                                <i class="fas fa-file-invoice-dollar me-2"></i>View Billing History
                            </a>
                            <a href="trails.php" class="btn btn-outline-warning py-3 rounded-pill fw-semibold">
                                <i class="fas fa-history me-2"></i>My Activity Log
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="glass-card p-4 h-100">
                        <h5 class="fw-bold mb-4"><i class="fas fa-tags me-2 text-success"></i>Current Billing Rates</h5>
                        <ul class="list-group list-group-flush bg-transparent">
                            <li class="list-group-item bg-transparent text-white border-secondary border-opacity-25 d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <i class="fas fa-bolt me-2 text-success"></i>
                                    <span>1 – 200 KW</span>
                                </div>
                                <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">₱10.00 / KW</span>
                            </li>
                            <li class="list-group-item bg-transparent text-white border-secondary border-opacity-25 d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <i class="fas fa-bolt me-2 text-warning"></i>
                                    <span>201 – 500 KW</span>
                                </div>
                                <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill">₱13.00 / KW</span>
                            </li>
                            <li class="list-group-item bg-transparent text-white border-secondary border-opacity-25 d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <i class="fas fa-bolt me-2 text-danger"></i>
                                    <span>501+ KW</span>
                                </div>
                                <span class="badge bg-danger bg-opacity-20 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill">₱15.00 / KW</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/jquery-1.11.0.js"></script>
<?php require_once("footer.php"); ?>
</body>
</html>
