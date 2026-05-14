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
                            <h2 class="fw-bold mb-0">My Activity <small class="text-muted fw-light h5 ms-2">Your Action History</small></h2>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 glass-card px-3 py-2">
                                    <li class="breadcrumb-item"><a href="index.php" class="text-info text-decoration-none">Dashboard</a></li>
                                    <li class="breadcrumb-item active text-white" aria-current="page">Activity</li>
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
                                        <th class="ps-4">TIMESTAMP</th>
                                        <th>ACTION</th>
                                        <th>DESCRIPTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $uid = $_SESSION['uid'];
                                        $query = "SELECT * FROM audit_logs WHERE user_id = $uid ORDER BY created_at DESC LIMIT 50";
                                        $result = mysqli_query($con, $query);
                                        while($row = mysqli_fetch_assoc($result)){
                                        ?>
                                            <tr class="transition-hover">
                                                <td class="ps-4 text-muted small">
                                                    <i class="far fa-clock me-2 opacity-50"></i><?php echo $row['created_at'] ?>
                                                </td>
                                                <td>
                                                    <span class="badge rounded-pill bg-info bg-opacity-20 text-info px-3 py-2 border border-info border-opacity-25">
                                                        <?php echo $row['action'] ?>
                                                    </span>
                                                </td>
                                                <td class="text-muted small">
                                                    <?php echo $row['description'] ?>
                                                </td>                                                    
                                            </tr>
                                        <?php } ?>
                                        <?php if(mysqli_num_rows($result) == 0): ?>
                                            <tr>
                                                <td colspan="3" class="text-center py-5 text-muted">
                                                    <i class="fas fa-fingerprint fa-3x mb-3 opacity-25"></i>
                                                    <p>No personal activity logs found.</p>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                </tbody>
                            </table>
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
