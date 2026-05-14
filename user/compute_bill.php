<?php 
    require_once('head_html.php'); 
    require_once('../Includes/config.php'); 
    require_once('../Includes/session.php'); 
    require_once('../Includes/user.php'); 
    if ($logged==false) { header("Location:../index.php"); }

    $uid = $_SESSION['uid'];
    $result_data = null;
    $error = '';

    // Tiered billing computation
    function computeBill($units) {
        $rate1 = 10; // 1-200 KW
        $rate2 = 13; // 201-500 KW
        $rate3 = 15; // 501+ KW

        if ($units <= 0) return null;

        if ($units <= 200) {
            $tier1 = $units * $rate1;
            $tier2 = 0;
            $tier3 = 0;
        } elseif ($units <= 500) {
            $tier1 = 200 * $rate1;
            $tier2 = ($units - 200) * $rate2;
            $tier3 = 0;
        } else {
            $tier1 = 200 * $rate1;
            $tier2 = 300 * $rate2;
            $tier3 = ($units - 500) * $rate3;
        }
        $total = $tier1 + $tier2 + $tier3;
        return compact('units','tier1','tier2','tier3','total','rate1','rate2','rate3');
    }

    if (isset($_POST['compute_bill'])) {
        $client_name = mysqli_real_escape_string($con, trim($_POST['client_name']));
        $units = (int)$_POST['units'];

        if (empty($client_name)) {
            $error = "Client name is required.";
        } elseif ($units <= 0) {
            $error = "Units must be greater than zero.";
        } else {
            $month = $_POST['month'];
            $result_data = computeBill($units);
            $amount = $result_data['total'];

            // Check if client exists
            $client_result = mysqli_query($con, "SELECT id FROM user WHERE name LIKE '%$client_name%' LIMIT 1");
            $client_row = mysqli_fetch_assoc($client_result);
            $client_uid = $client_row ? $client_row['id'] : $uid;

            // Save bill to database
            $bdate = date('Y-m-d');
            $ddate = date('Y-m-d', strtotime('+30 days'));
            $q = "INSERT INTO bill (uid, units, month, amount, status, bdate, ddate) VALUES ($client_uid, $units, '$month', $amount, 'PENDING', '$bdate', '$ddate')";
            mysqli_query($con, $q);
            $bill_id = mysqli_insert_id($con);
            mysqli_query($con, "INSERT INTO transaction (bid, payable, pdate, status) VALUES ($bill_id, $amount, NULL, 'PENDING')");

            log_action($uid, "Compute Bill", "Computed bill for '$client_name' — Units: $units, Amount: ₱$amount");
        }
    }
?>

<body>
<div id="wrapper">
    <?php require_once("nav.php"); require_once("sidebar.php"); ?>

    <div id="page-content-wrapper" class="mt-5 pt-4">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="fw-bold mb-0">Compute Bill <small class="text-muted fw-light h5 ms-2">Electric Billing</small></h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 glass-card px-3 py-2">
                                <li class="breadcrumb-item"><a href="index.php" class="text-info text-decoration-none">Dashboard</a></li>
                                <li class="breadcrumb-item active text-white">Compute Bill</li>
                            </ol>
                        </nav>
                    </div>
                    <hr class="opacity-10">
                </div>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-danger glass-card border-0 mb-4 alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div class="row g-4">
                <!-- Computation Form -->
                <div class="col-lg-5">
                    <div class="glass-card p-4 h-100">
                        <h5 class="fw-bold mb-4"><i class="fas fa-calculator me-2 text-primary"></i>Bill Calculator</h5>
                        <form method="post">
                            <div class="mb-4">
                                <label class="form-label text-muted small ms-1">CLIENT NAME</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="client_name" placeholder="Enter client name" value="<?php echo isset($_POST['client_name']) ? htmlspecialchars($_POST['client_name']) : ''; ?>" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-muted small ms-1">BILLING MONTH</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    <select name="month" class="form-select" required>
                                        <option value="">Select Month</option>
                                        <?php
                                        $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                                        foreach($months as $m) {
                                            $selected = (date('F') == $m) ? "selected" : "";
                                            echo "<option value='$m' $selected>$m</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-muted small ms-1">CONSUMPTION (KW)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-bolt"></i></span>
                                    <input type="number" class="form-control" name="units" placeholder="Enter KW units" min="1" value="<?php echo isset($_POST['units']) ? (int)$_POST['units'] : ''; ?>" required>
                                    <span class="input-group-text" style="border-left:none;">KW</span>
                                </div>
                            </div>

                            <!-- Rate Guide -->
                            <div class="glass-card p-3 mb-4">
                                <p class="text-muted small mb-2 fw-semibold">CURRENT RATES</p>
                                <div class="d-flex justify-content-between py-1 border-bottom border-secondary border-opacity-25">
                                    <span class="small text-muted">1 – 200 KW</span>
                                    <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-25">₱10.00 / KW</span>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom border-secondary border-opacity-25">
                                    <span class="small text-muted">201 – 500 KW</span>
                                    <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-25">₱13.00 / KW</span>
                                </div>
                                <div class="d-flex justify-content-between py-1">
                                    <span class="small text-muted">501+ KW</span>
                                    <span class="badge bg-danger bg-opacity-20 text-danger border border-danger border-opacity-25">₱15.00 / KW</span>
                                </div>
                            </div>

                            <button type="submit" name="compute_bill" class="btn btn-primary w-100 rounded-pill py-3 fw-bold">
                                <i class="fas fa-calculator me-2"></i>Compute Now
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Result -->
                <div class="col-lg-7">
                    <?php if ($result_data): ?>
                    <div class="glass-card p-4">
                        <div class="text-center mb-4">
                            <div class="icon-shape bg-success bg-opacity-20 text-success rounded-circle p-3 mx-auto mb-3" style="width:80px;height:80px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-receipt fa-2x"></i>
                            </div>
                            <h5 class="fw-bold">Bill Computed Successfully</h5>
                            <p class="text-muted small">For <strong class="text-white"><?php echo htmlspecialchars($_POST['client_name']); ?></strong></p>
                        </div>

                        <div class="mb-4">
                            <p class="text-muted small fw-semibold mb-2">COMPUTATION BREAKDOWN</p>

                            <?php if ($result_data['units'] >= 1): ?>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary border-opacity-25">
                                <span class="small">Tier 1 (1–200 KW @ ₱<?php echo $result_data['rate1']; ?>)</span>
                                <span class="fw-semibold text-success">₱<?php echo number_format($result_data['tier1'], 2); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if ($result_data['units'] > 200): ?>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary border-opacity-25">
                                <span class="small">Tier 2 (201–500 KW @ ₱<?php echo $result_data['rate2']; ?>)</span>
                                <span class="fw-semibold text-warning">₱<?php echo number_format($result_data['tier2'], 2); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if ($result_data['units'] > 500): ?>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary border-opacity-25">
                                <span class="small">Tier 3 (501+ KW @ ₱<?php echo $result_data['rate3']; ?>)</span>
                                <span class="fw-semibold text-danger">₱<?php echo number_format($result_data['tier3'], 2); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="glass-card p-4 text-center mt-2" style="background:rgba(13,202,240,0.05);border-color:rgba(13,202,240,0.2);">
                            <p class="text-muted small mb-1">TOTAL AMOUNT DUE</p>
                            <h1 class="fw-bold text-gradient display-5">₱<?php echo number_format($result_data['total'], 2); ?></h1>
                            <p class="text-muted small mb-0"><?php echo number_format($result_data['units']); ?> KW consumed</p>
                        </div>

                        <div class="mt-4 d-flex gap-3">
                            <a href="bill.php" class="btn btn-outline-info rounded-pill flex-fill">
                                <i class="fas fa-history me-2"></i>View History
                            </a>
                            <a href="compute_bill.php" class="btn btn-primary rounded-pill flex-fill">
                                <i class="fas fa-redo me-2"></i>Compute Another
                            </a>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="glass-card p-5 text-center h-100 d-flex flex-column align-items-center justify-content-center">
                        <i class="fas fa-bolt fa-4x text-warning mb-4 opacity-25"></i>
                        <h5 class="text-muted fw-light">Bill result will appear here</h5>
                        <p class="text-muted small">Enter the client name and consumption in KW to compute.</p>
                    </div>
                    <?php endif; ?>
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
