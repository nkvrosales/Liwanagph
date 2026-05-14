<?php 
    require_once('head_html.php'); 
    require_once('../Includes/config.php'); 
    require_once('../Includes/session.php'); 
    require_once('../Includes/admin.php'); 
    if ($logged==false) {
         header("Location:../index.php");
    }

    // Admin stats
    $total_users_result = mysqli_query($con, "SELECT COUNT(*) as cnt FROM user");
    $total_users = mysqli_fetch_assoc($total_users_result)['cnt'];

    $total_bills_result = mysqli_query($con, "SELECT COUNT(*) as cnt FROM bill");
    $total_bills = mysqli_fetch_assoc($total_bills_result)['cnt'];

    $total_audit_result = mysqli_query($con, "SELECT COUNT(*) as cnt FROM audit_logs");
    $total_audit = mysqli_fetch_assoc($total_audit_result)['cnt'];

    $new_users_result = mysqli_query($con, "SELECT COUNT(*) as cnt FROM user");
    // Recent audit entries with correct role-based joins
    $query = "
        SELECT a.*, 
               CASE 
                 WHEN a.role = 'admin' THEN COALESCE(adm.name, 'Admin')
                 ELSE COALESCE(u.name, 'User')
               END as performer
        FROM audit_logs a 
        LEFT JOIN user u ON a.user_id = u.id AND a.role = 'user'
        LEFT JOIN admin adm ON a.user_id = adm.id AND a.role = 'admin'
        ORDER BY a.created_at DESC 
        LIMIT 5
    ";
    $recent_audit = mysqli_query($con, $query);
?>

<body>
    <div id="wrapper">
        <?php 
            require_once("nav.php");
            require_once("sidebar.php");
        ?>

        <div id="page-content-wrapper" class="mt-5 pt-4">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-12">
                        <h2 class="fw-bold">Dashboard <small class="text-muted fw-light h5 ms-2">Admin Overview</small></h2>
                        <hr class="opacity-10">
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card card-stats h-100 shadow-sm border-0">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="icon-shape bg-primary bg-opacity-20 text-primary rounded-3 p-3">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                    <div class="text-end">
                                        <h3 class="fw-bold mb-0"><?php echo $total_users; ?></h3>
                                        <span class="text-muted small">Registered Users</span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="manage_users.php" class="btn btn-sm btn-link text-primary p-0 text-decoration-none">Manage Users <i class="fas fa-chevron-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-stats h-100 shadow-sm border-0">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="icon-shape bg-success bg-opacity-20 text-success rounded-3 p-3">
                                        <i class="fas fa-file-invoice-dollar fa-2x"></i>
                                    </div>
                                    <div class="text-end">
                                        <h3 class="fw-bold mb-0"><?php echo $total_bills; ?></h3>
                                        <span class="text-muted small">Total Bills Computed</span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="bill.php" class="btn btn-sm btn-link text-success p-0 text-decoration-none">Manage Billings <i class="fas fa-chevron-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-stats h-100 shadow-sm border-0">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="icon-shape bg-warning bg-opacity-20 text-warning rounded-3 p-3">
                                        <i class="fas fa-fingerprint fa-2x"></i>
                                    </div>
                                    <div class="text-end">
                                        <h3 class="fw-bold mb-0"><?php echo $total_audit; ?></h3>
                                        <span class="text-muted small">Audit Events</span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="audit.php" class="btn btn-sm btn-link text-warning p-0 text-decoration-none">View All <i class="fas fa-chevron-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Audit Activity -->
                <div class="row">
                    <div class="col-12">
                        <div class="glass-card p-4">
                            <h5 class="fw-bold mb-4"><i class="fas fa-history me-2 text-info"></i>Recent System Activity</h5>
                            <div class="table-responsive">
                                <table class="table table-dark table-hover align-middle mb-0">
                                    <thead class="bg-primary bg-opacity-10">
                                        <tr>
                                            <th class="ps-3">TIME</th>
                                            <th>USER</th>
                                            <th>ACTION</th>
                                            <th>DESCRIPTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($row = mysqli_fetch_assoc($recent_audit)): ?>
                                        <tr>
                                            <td class="ps-3 text-muted small"><?php echo $row['created_at']; ?></td>
                                            <td class="fw-semibold small"><?php echo $row['performer']; ?></td>
                                            <td><span class="badge bg-primary bg-opacity-20 text-primary border border-primary border-opacity-25 px-3"><?php echo $row['action']; ?></span></td>
                                            <td class="text-muted small"><?php echo $row['description']; ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                        <?php if($total_audit == 0): ?>
                                        <tr><td colspan="4" class="text-center py-4 text-muted"><i class="fas fa-inbox me-2"></i>No activity yet.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3 text-end">
                                <a href="audit.php" class="btn btn-sm btn-outline-info rounded-pill px-4">View Full Audit Trail</a>
                            </div>
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
