<?php 
    require_once('head_html.php'); 
    require_once('../Includes/config.php'); 
    require_once('../Includes/session.php'); 
    require_once('../Includes/admin.php'); 
    if ($logged==false) {
         header("Location:../index.php");
    }

    $aid = $_SESSION['aid'];

    // Handle Edit Bill
    if (isset($_POST['edit_bill'])) {
        $bid    = (int)$_POST['edit_bid'];
        $units  = (int)$_POST['edit_units'];
        $month  = mysqli_real_escape_string($con, $_POST['edit_month']);

        // Recalculate amount using stored procedure
        mysqli_query($con, "CALL unitstoamount($units, @newamt)");
        $res = mysqli_query($con, "SELECT @newamt AS amt");
        $amt = mysqli_fetch_assoc($res)['amt'];

        mysqli_query($con, "UPDATE bill SET units=$units, month='$month', amount=$amt WHERE id=$bid");
        mysqli_query($con, "UPDATE transaction SET payable=$amt WHERE bid=$bid AND status='PENDING'");
        log_action($aid, "Edit Bill", "Edited bill #$bid — Units: $units, Month: $month, Amount: ₱$amt", "admin");
        $_SESSION['pay_success'] = "Bill #$bid has been updated successfully.";
        header("Location:bill.php");
        exit;
    }

    // Handle Delete Bill
    if (isset($_POST['delete_bill'])) {
        $bid = (int)$_POST['del_bid'];
        mysqli_query($con, "DELETE FROM transaction WHERE bid=$bid");
        mysqli_query($con, "DELETE FROM bill WHERE id=$bid");
        log_action($aid, "Delete Bill", "Deleted bill #$bid", "admin");
        $_SESSION['pay_success'] = "Bill #$bid has been deleted.";
        header("Location:bill.php");
        exit;
    }
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
                            <h2 class="fw-bold mb-0">Billings <small class="text-muted fw-light h5 ms-2">Management</small></h2>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 glass-card px-3 py-2">
                                    <li class="breadcrumb-item"><a href="index.php" class="text-info text-decoration-none">Dashboard</a></li>
                                    <li class="breadcrumb-item active text-white" aria-current="page">Billings</li>
                                </ol>
                            </nav>
                        </div>
                        <hr class="opacity-10">
                    </div>
                </div>

                <?php if(isset($_SESSION['pay_success'])): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success glass-card border-0 mb-4 fade-in" role="alert" style="background: rgba(25, 135, 84, 0.2); color: #d1e7dd; border: 1px solid rgba(25, 135, 84, 0.3) !important;">
                            <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['pay_success']; unset($_SESSION['pay_success']); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Bills Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive glass-card p-4 shadow-lg">
                            <table class="table table-dark table-hover align-middle mb-0">
                                <thead class="bg-primary bg-opacity-10">
                                    <tr>
                                        <th class="ps-4">BILL NO.</th>
                                        <th>CUSTOMER</th>
                                        <th>MONTH</th>
                                        <th>DATE</th>
                                        <th>UNITS (KW)</th>
                                        <th>AMOUNT</th>
                                        <th>DUE DATE</th>
                                        <th class="text-center">STATUS</th>
                                        <th class="text-center pe-4">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $query1 = "SELECT COUNT(*) FROM bill INNER JOIN user ON user.id=bill.uid";
                                    $result1 = mysqli_query($con,$query1);
                                    $row1 = mysqli_fetch_row($result1);
                                    $numrows = $row1[0];
                                    include("paging1.php");
                                    $result = retrieve_bills_generated($aid, $offset, $rowsperpage);
                                    while($row = mysqli_fetch_assoc($result)):
                                ?>
                                    <tr>
                                        <td class="ps-4 text-info small fw-mono">#BN_<?php echo $row['bid']?></td>
                                        <td class="fw-semibold"><?php echo $row['user'] ?></td>
                                        <td class="text-info small"><?php echo $row['month'] ?: '-' ?></td>
                                        <td class="text-muted small"><?php echo $row['bdate'] ?></td>
                                        <td>
                                            <span class="badge bg-secondary bg-opacity-25 text-white fw-normal px-3"><?php echo $row['units'] ?> KW</span>
                                        </td>
                                        <td class="fw-bold text-success">₱<?php echo number_format($row['amount'], 2) ?></td>
                                        <td class="text-warning small"><?php echo $row['ddate'] ?></td>
                                        <td class="text-center">
                                            <?php if($row['status'] == 'PENDING'): ?>
                                                <span class="badge rounded-pill bg-danger bg-opacity-20 text-danger px-3 py-2 border border-danger border-opacity-25">
                                                    <i class="fas fa-clock me-1"></i> PENDING
                                                </span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill bg-success bg-opacity-20 text-success px-3 py-2 border border-success border-opacity-25">
                                                    <i class="fas fa-check-circle me-1"></i> <?php echo $row['status'] ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <?php if($row['status'] == 'PENDING'): ?>
                                                    <!-- Mark Paid -->
                                                    <form action="process_payment.php" method="post" class="d-inline">
                                                        <input type="hidden" name="bid" value="<?php echo $row['bid'] ?>">
                                                        <button type="submit" name="mark_paid" class="btn btn-outline-success btn-sm rounded-pill px-2" onclick="return confirm('Mark this bill as PAID?')" title="Mark Paid">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <!-- Edit -->
                                                    <button class="btn btn-outline-info btn-sm rounded-pill px-2" title="Edit Bill"
                                                        data-bs-toggle="modal" data-bs-target="#editBillModal"
                                                        data-bid="<?php echo $row['bid'] ?>"
                                                        data-units="<?php echo $row['units'] ?>"
                                                        data-month="<?php echo $row['month'] ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <span class="text-muted small"><i class="fas fa-lock me-1"></i>Locked</span>
                                                <?php endif; ?>
                                                <!-- Delete (any status) -->
                                                <button class="btn btn-outline-danger btn-sm rounded-pill px-2" title="Delete Bill"
                                                    data-bs-toggle="modal" data-bs-target="#deleteBillModal"
                                                    data-bid="<?php echo $row['bid'] ?>"
                                                    data-user="<?php echo htmlspecialchars($row['user']) ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                <?php if($numrows == 0): ?>
                                    <tr><td colspan="9" class="text-center py-5 text-muted">
                                        <i class="fas fa-file-invoice fa-3x mb-3 opacity-25 d-block"></i>No bills found.
                                    </td></tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="mt-4 px-3">
                                <?php include("paging2.php"); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Bill Modal -->
    <div class="modal fade" id="editBillModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-card border-0">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2 text-info"></i>Edit Bill</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post" class="row g-3">
                        <input type="hidden" name="edit_bid" id="edit_bid">
                        <div class="col-12">
                            <label class="form-label text-muted small ms-1">BILLING MONTH</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                <select name="edit_month" id="edit_month" class="form-select" required>
                                    <option value="">Select Month</option>
                                    <?php
                                    $months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
                                    foreach($months as $m) echo "<option value='$m'>$m</option>";
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small ms-1">UNITS (KW)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-bolt"></i></span>
                                <input type="number" name="edit_units" id="edit_units" class="form-control" placeholder="Units consumed" min="1" required>
                                <span class="input-group-text">KW</span>
                            </div>
                            <small class="text-muted ms-1">Amount will be recalculated automatically.</small>
                        </div>
                        <div class="col-12 mt-2">
                            <button type="submit" name="edit_bill" class="btn btn-info w-100 rounded-pill py-2 fw-bold">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Bill Modal -->
    <div class="modal fade" id="deleteBillModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content glass-card border-0">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Delete Bill</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-3">
                    <p class="mb-1">Delete bill <strong id="del_bill_id" class="text-info"></strong></p>
                    <p class="mb-1">for <strong id="del_bill_user" class="text-white"></strong>?</p>
                    <p class="text-muted small">This action cannot be undone.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <form method="post" class="w-100 d-flex gap-2">
                        <input type="hidden" name="del_bid" id="del_bid">
                        <button type="button" class="btn btn-outline-light w-50 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete_bill" class="btn btn-danger w-50 rounded-pill">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/jquery-1.11.0.js"></script>
    <script>
        // Populate Edit Modal
        document.getElementById('editBillModal').addEventListener('show.bs.modal', function(e) {
            var btn = e.relatedTarget;
            document.getElementById('edit_bid').value = btn.dataset.bid;
            document.getElementById('edit_units').value = btn.dataset.units;
            var monthSel = document.getElementById('edit_month');
            for (var i = 0; i < monthSel.options.length; i++) {
                if (monthSel.options[i].value === btn.dataset.month) {
                    monthSel.selectedIndex = i; break;
                }
            }
        });
        // Populate Delete Modal
        document.getElementById('deleteBillModal').addEventListener('show.bs.modal', function(e) {
            var btn = e.relatedTarget;
            document.getElementById('del_bid').value = btn.dataset.bid;
            document.getElementById('del_bill_id').textContent = '#BN_' + btn.dataset.bid;
            document.getElementById('del_bill_user').textContent = btn.dataset.user;
        });
    </script>
    <?php require_once("footer.php"); ?>
</body>
</html>
