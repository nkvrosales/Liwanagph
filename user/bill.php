<?php 
    require_once('head_html.php'); 
    require_once('../Includes/config.php'); 
    require_once('../Includes/session.php'); 
    require_once('../Includes/user.php'); 
    if ($logged==false) { header("Location:../index.php"); }
    $uid = $_SESSION['uid'];
    $query1 = "SELECT COUNT(*) FROM bill where uid=$uid";
    $row1   = mysqli_fetch_row(mysqli_query($con, $query1));
    $numrows = $row1[0];
    include("paging1.php");
    $result = mysqli_query($con, "SELECT * FROM bill WHERE uid=$uid ORDER BY bdate DESC LIMIT $offset, $rowsperpage");
?>

<body>
<div id="wrapper">
    <?php require_once("nav.php"); require_once("sidebar.php"); ?>

    <div id="page-content-wrapper" class="mt-5 pt-4">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="fw-bold mb-0">Billing History <small class="text-muted fw-light h5 ms-2">My Computed Bills</small></h2>
                        <div class="d-flex gap-3 align-items-center">
                            <a href="compute_bill.php" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-plus-circle me-2"></i>Compute New Bill
                            </a>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 glass-card px-3 py-2">
                                    <li class="breadcrumb-item"><a href="index.php" class="text-info text-decoration-none">Dashboard</a></li>
                                    <li class="breadcrumb-item active text-white">Bill History</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <hr class="opacity-10">
                </div>
            </div>

            <div class="table-responsive glass-card p-4 shadow-lg">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead class="bg-primary bg-opacity-10">
                        <tr>
                            <th class="ps-4">BILL NO.</th>
                            <th>MONTH</th>
                            <th>BILL DATE</th>
                            <th>DUE DATE</th>
                            <th>UNITS (KW)</th>
                            <th>AMOUNT</th>
                            <th class="text-center pe-4">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="ps-4 text-info small fw-mono">#EBS_<?php echo $row['id']; ?></td>
                            <td class="text-info small fw-semibold"><?php echo $row['month'] ?: '-' ?></td>
                            <td class="text-muted small"><?php echo $row['bdate']; ?></td>
                            <td class="text-muted small"><?php echo $row['ddate']; ?></td>
                            <td><span class="badge bg-secondary bg-opacity-25 text-white fw-normal px-3"><?php echo $row['units']; ?> KW</span></td>
                            <td class="fw-bold text-white">₱<?php echo number_format($row['amount'], 2); ?></td>
                            <td class="text-center pe-4">
                                <?php if($row['status'] == 'PENDING'): ?>
                                <span class="badge rounded-pill bg-danger bg-opacity-20 text-danger px-3 py-2 border border-danger border-opacity-25">PENDING</span>
                                <?php else: ?>
                                <span class="badge rounded-pill bg-success bg-opacity-20 text-success px-3 py-2 border border-success border-opacity-25"><i class="fas fa-check me-1"></i>PAID</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if($numrows == 0): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-file-invoice fa-3x mb-3 opacity-25 d-block"></i>
                            No bills computed yet. <a href="compute_bill.php" class="text-info">Compute your first bill.</a>
                        </td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="mt-4 px-3"><?php include("paging2.php"); ?></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/jquery-1.11.0.js"></script>
<?php require_once("footer.php"); ?>
</body>
</html>
