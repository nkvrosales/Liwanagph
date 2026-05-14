<?php 
    require_once('head_html.php'); 
    require_once('../Includes/config.php'); 
    require_once('../Includes/session.php'); 
    require_once('../Includes/user.php');
    if ($logged==false) {
         header("Location:../index.php");
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
                            <div>
                                <h2 class="fw-bold mb-0">Complaints <small class="text-muted fw-light h5 ms-2">Support History</small></h2>
                            </div>
                            <div class="d-flex gap-3 align-items-center">
                                <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#Complaint">
                                    <i class="fas fa-plus-circle me-2"></i>New Complaint
                                </button>
                                <nav aria-label="breadcrumb" class="d-none d-md-block">
                                    <ol class="breadcrumb mb-0 glass-card px-3 py-2">
                                        <li class="breadcrumb-item"><a href="index.php" class="text-info text-decoration-none">Dashboard</a></li>
                                        <li class="breadcrumb-item active text-white" aria-current="page">Complaints</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                        <hr class="opacity-10">
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive glass-card p-4 shadow-lg">
                            <table class="table table-dark table-hover align-middle mb-0">
                                <thead class="bg-primary bg-opacity-10">
                                    <tr>
                                        <th class="ps-4">COMPLAINT NO.</th>
                                        <th>GRIEVANCE</th>
                                        <th class="text-center pe-4">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $id=$_SESSION['uid'];
                                    $query1 = "SELECT COUNT(*) FROM complaint where uid={$id}";
                                    $result1 = mysqli_query($con,$query1);
                                    $row1 = mysqli_fetch_row($result1);
                                    $numrows = $row1[0];
                                    include("paging1.php");

                                    $result = retrieve_complaints($_SESSION['uid'],$offset, $rowsperpage);
                                    
                                    while($row = mysqli_fetch_assoc($result)){
                                    ?>
                                        <tr>
                                            <td class="ps-4 text-info small fw-mono">#CA-<?php echo $row['id'] ?></td>
                                            <td class="text-white"><?php echo $row['complaint'] ?></td>
                                            <td class="text-center pe-4">
                                                <?php if($row['status'] == 'PROCESSED'): ?>
                                                    <span class="badge rounded-pill bg-success bg-opacity-20 text-success px-3 py-2 border border-success border-opacity-25">
                                                        <i class="fas fa-check-circle me-1"></i> RESOLVED
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge rounded-pill bg-warning bg-opacity-20 text-warning px-3 py-2 border border-warning border-opacity-25">
                                                        <i class="fas fa-spinner fa-spin me-1"></i> PENDING
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <div class="mt-4 px-3"><?php include("paging2.php"); ?></div>
                        </div>
                    </div>
                </div>

                <!-- New Complaint Modal -->
                <div class="modal fade" id="Complaint" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content glass-card border-0">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold">Report an Issue</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body py-4">
                                <form action="sub_complaint.php" method="post">
                                    <div class="mb-4 text-center">
                                        <div class="icon-box bg-info bg-opacity-20 text-info rounded-circle p-3 mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-headset fa-3x"></i>
                                        </div>
                                        <p class="text-muted small">Select the category that best describes your issue. Our team will review it shortly.</p>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label text-muted small ms-2">NATURE OF GRIEVANCE</label>
                                        <select class="form-select bg-dark border-secondary text-white py-3" name="complaint" required>
                                            <option value="" disabled selected>Choose an option...</option>
                                            <option value="Bill Not Correct">Bill Not Correct</option>
                                            <option value="Bill Generated Late">Bill Generated Late</option>
                                            <option value="Transaction Not Processed">Transaction Not Processed</option>
                                            <option value="Previous Complaint Not Processed">Previous Complaint Not Processed</option>
                                        </select>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" name="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm py-3 fw-bold">
                                            Submit Complaint
                                        </button>
                                    </div>
                                </form>
                            </div>
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
