<?php 
    require_once('head_html.php'); 
    require_once('../Includes/config.php'); 
    require_once('../Includes/session.php'); 
    require_once('../Includes/admin.php'); 
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
                            <h2 class="fw-bold mb-0">Complaints <small class="text-muted fw-light h5 ms-2">Pending Resolution</small></h2>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 glass-card px-3 py-2">
                                    <li class="breadcrumb-item"><a href="index.php" class="text-info text-decoration-none">Dashboard</a></li>
                                    <li class="breadcrumb-item active text-white" aria-current="page">Complaints</li>
                                </ol>
                            </nav>
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
                                        <th>USER</th>
                                        <th>COMPLAINT</th>
                                        <th class="text-center">STATUS</th>
                                        <th class="text-center pe-4">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $id=$_SESSION['aid'];
                                        $query1 = "SELECT COUNT(complaint.id) FROM user , complaint  ";
                                        $query1.= " WHERE complaint.uid=user.id AND status='NOT PROCESSED' AND complaint.aid={$id}";
                                        $result1 = mysqli_query($con,$query1);
                                        $row1 = mysqli_fetch_row($result1);
                                        $numrows = $row1[0];
                                        include("paging1.php");
                                        $result = retrieve_complaints_history($_SESSION['aid'],$offset, $rowsperpage);
                                        while($row = mysqli_fetch_assoc($result)){
                                        ?>
                                            <tr>
                                                <td class="ps-4 text-info small fw-mono">#CA-<?php echo $row['id'] ?></td>
                                                <td>
                                                    <div class="fw-semibold"><?php echo $row['uname'] ?></div>
                                                </td>
                                                <td class="text-muted small">
                                                    <div class="p-2 bg-secondary bg-opacity-10 rounded border border-secondary border-opacity-10" style="max-width: 400px;">
                                                        <?php echo $row['complaint'] ?>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge rounded-pill bg-warning bg-opacity-20 text-warning px-3 py-2 border border-warning border-opacity-25">
                                                        <i class="fas fa-exclamation-circle me-1"></i> <?php echo $row['status'] ?>
                                                    </span>
                                                </td>
                                                <td class="text-center pe-4">
                                                    <form action="process_complaint.php" method="post">
                                                        <input type="hidden" name="cid" value="<?php echo $row['id'] ?>">
                                                        <button type="submit" name="complaint_process" class="btn btn-success btn-sm px-3 rounded-pill shadow-sm">
                                                            <i class="fas fa-check-double me-1"></i> Resolve
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php } ?>
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

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/jquery-1.11.0.js"></script>
    <?php require_once("footer.php"); ?>
</body>
</html>
