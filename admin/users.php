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
                            <h2 class="fw-bold mb-0">Customers <small class="text-muted fw-light h5 ms-2">Registered Users</small></h2>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 glass-card px-3 py-2">
                                    <li class="breadcrumb-item"><a href="index.php" class="text-info text-decoration-none">Dashboard</a></li>
                                    <li class="breadcrumb-item active text-white" aria-current="page">Customers</li>
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
                                        <th class="ps-4">#</th>
                                        <th>NAME</th>
                                        <th>EMAIL</th>
                                        <th>CONTACT</th>
                                        <th>ADDRESS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $id=$_SESSION['aid'];
                                        $query1 = "SELECT COUNT(*) FROM user";
                                        $result1 = mysqli_query($con,$query1);
                                        $row1 = mysqli_fetch_row($result1);
                                        $numrows = $row1[0];
                                        include("paging1.php");
                                        $result = retrieve_users_detail($_SESSION['aid'],$offset, $rowsperpage);

                                        $cnt = $offset + 1;
                                        while($row = mysqli_fetch_assoc($result)){
                                        ?>
                                            <tr class="transition-hover">
                                                <td class="ps-4 text-muted small"><?php echo $cnt; ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3 text-center" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                            <?php echo strtoupper(substr($row['name'], 0, 1)); ?>
                                                        </div>
                                                        <span class="fw-semibold text-white"><?php echo $row['name'] ?></span>
                                                    </div>
                                                </td>
                                                <td class="text-muted small">
                                                    <i class="fas fa-envelope-open me-2 text-info opacity-50"></i><?php echo $row['email'] ?>
                                                </td>
                                                <td class="text-muted small">
                                                    <i class="fas fa-phone-alt me-2 text-success opacity-50"></i><?php echo $row['phone'] ?>
                                                </td>
                                                <td class="text-muted small w-25">
                                                    <i class="fas fa-map-marker-alt me-2 text-danger opacity-50"></i><?php echo $row['address'] ?>
                                                </td>                                                    
                                            </tr>
                                        <?php $cnt++; } ?>
                                </tbody>
                            </table>
                            
                            <div class="mt-4">
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
