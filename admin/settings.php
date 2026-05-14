<?php 
    require_once('head_html.php'); 
    require_once('../Includes/config.php'); 
    require_once('../Includes/session.php'); 
    require_once('../Includes/admin.php'); 
    if ($logged==false) {
         header("Location:../index.php");
    }

    $message = "";
    $msg_type = "";

    if (isset($_POST['update_rates'])) {
        $rate1 = (int)$_POST['rate1'];
        $rate2 = (int)$_POST['rate2'];
        $rate3 = (int)$_POST['rate3'];

        $query = "UPDATE unitsrate SET twohundred = $rate1, fivehundred = $rate2, thousand = $rate3 WHERE sno = 1";
        if (mysqli_query($con, $query)) {
            $message = "Rates updated successfully!";
            $msg_type = "success";
            log_action($_SESSION['aid'], "Update Rates", "Updated billing rates to $rate1, $rate2, $rate3", "admin");
        } else {
            $message = "Error updating rates: " . mysqli_error($con);
            $msg_type = "danger";
        }
    }

    // Fetch current rates
    $query = "SELECT * FROM unitsrate WHERE sno = 1";
    $result = mysqli_query($con, $query);
    $rates = mysqli_fetch_assoc($result);
?>

<body>
    <div id="wrapper">
        <?php 
            require_once("nav.php");
            require_once("sidebar.php");
        ?>

        <!-- Page Content -->
        <div id="page-content-wrapper" class="mt-5 pt-4">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="fw-bold mb-0">Settings <small class="text-muted fw-light h5 ms-2">System Configuration</small></h2>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 glass-card px-3 py-2">
                                    <li class="breadcrumb-item"><a href="index.php" class="text-info text-decoration-none">Dashboard</a></li>
                                    <li class="breadcrumb-item active text-white" aria-current="page">Settings</li>
                                </ol>
                            </nav>
                        </div>
                        <hr class="opacity-10">
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="glass-card p-4 shadow-lg">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-shape bg-primary bg-opacity-20 text-primary rounded-3 p-3 me-3">
                                    <i class="fas fa-sliders-h fa-2x"></i>
                                </div>
                                <div>
                                    <h4 class="fw-bold mb-0">Billing Rates</h4>
                                    <p class="text-muted small mb-0">Configure the tiered pricing for electricity consumption.</p>
                                </div>
                            </div>

                            <?php if ($message): ?>
                                <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show bg-opacity-10 border-0" role="alert">
                                    <i class="fas fa-info-circle me-2"></i><?php echo $message; ?>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <form action="settings.php" method="post" class="mt-3">
                                <div class="mb-4">
                                    <label class="form-label text-muted small ms-2">TIER 1 (0 - 200 KW)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" name="rate1" class="form-control" value="<?php echo $rates['twohundred']; ?>" required>
                                        <span class="input-group-text">/ KW</span>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-muted small ms-2">TIER 2 (201 - 500 KW)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" name="rate2" class="form-control" value="<?php echo $rates['fivehundred']; ?>" required>
                                        <span class="input-group-text">/ KW</span>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-muted small ms-2">TIER 3 (501+ KW)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" name="rate3" class="form-control" value="<?php echo $rates['thousand']; ?>" required>
                                        <span class="input-group-text">/ KW</span>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 mt-5">
                                    <button type="submit" name="update_rates" class="btn btn-primary btn-lg rounded-pill shadow-sm py-3 fw-bold">
                                        <i class="fas fa-save me-2"></i>Save Configuration
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="glass-card p-4 h-100">
                            <h5 class="fw-bold mb-4"><i class="fas fa-info-circle me-2 text-info"></i>About Tiered Billing</h5>
                            <p class="text-muted small">Tiered billing encourages energy conservation by increasing the price as consumption goes up.</p>
                            <div class="alert alert-info bg-opacity-10 border-0 mt-4 small">
                                <ul class="mb-0 ps-3">
                                    <li><strong>Tier 1:</strong> Base rate for minimal usage.</li>
                                    <li><strong>Tier 2:</strong> Mid-range consumption rate.</li>
                                    <li><strong>Tier 3:</strong> Premium rate for high consumption.</li>
                                </ul>
                            </div>
                            <p class="text-warning small mt-4">
                                <i class="fas fa-exclamation-triangle me-1"></i> Changes will apply to all <strong>future</strong> bills generated.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/jquery-1.11.0.js"></script>
    <?php require_once("footer.php"); ?>
</body>
</html>
