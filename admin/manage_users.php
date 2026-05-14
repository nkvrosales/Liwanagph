<?php 
    require_once('head_html.php'); 
    require_once('../Includes/config.php'); 
    require_once('../Includes/session.php'); 
    require_once('../Includes/admin.php'); 
    if ($logged==false) { header("Location:../index.php"); }

    $msg = '';
    $msg_type = '';

    // CREATE user
    if (isset($_POST['create_user'])) {
        $name     = mysqli_real_escape_string($con, trim($_POST['name']));
        $username = mysqli_real_escape_string($con, trim($_POST['username']));
        $email    = mysqli_real_escape_string($con, trim($_POST['email']));
        $phone    = mysqli_real_escape_string($con, trim($_POST['phone']));
        $address  = mysqli_real_escape_string($con, trim($_POST['address']));
        $pass     = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $check = mysqli_query($con, "SELECT id FROM user WHERE username='$username' OR email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $msg = 'A user with that username or email already exists.';
            $msg_type = 'danger';
        } else {
            mysqli_query($con, "INSERT INTO user (name,username,email,phone,pass,address) VALUES ('$name','$username','$email','$phone','$pass','$address')");
            log_action($_SESSION['aid'], 'Create User', "Admin created account for $name (@$username)", 'admin');
            $msg = "User account for <strong>$name</strong> created successfully.";
            $msg_type = 'success';
        }
    }

    // UPDATE user
    if (isset($_POST['update_user'])) {
        $uid      = (int)$_POST['edit_uid'];
        $name     = mysqli_real_escape_string($con, trim($_POST['edit_name']));
        $username = mysqli_real_escape_string($con, trim($_POST['edit_username']));
        $email    = mysqli_real_escape_string($con, trim($_POST['edit_email']));
        $phone    = mysqli_real_escape_string($con, trim($_POST['edit_phone']));
        $address  = mysqli_real_escape_string($con, trim($_POST['edit_address']));

        $q = "UPDATE user SET name='$name', username='$username', email='$email', phone='$phone', address='$address' WHERE id=$uid";
        mysqli_query($con, $q);

        // Only update password if provided
        if (!empty($_POST['edit_password'])) {
            $pass = password_hash($_POST['edit_password'], PASSWORD_DEFAULT);
            mysqli_query($con, "UPDATE user SET pass='$pass' WHERE id=$uid");
        }
        log_action($_SESSION['aid'], 'Update User', "Admin updated account for $name (@$username)", 'admin');
        $msg = "User <strong>$name</strong> updated successfully.";
        $msg_type = 'info';
    }

    // DELETE user
    if (isset($_POST['delete_user'])) {
        $uid = (int)$_POST['del_uid'];
        $r   = mysqli_query($con, "SELECT name FROM user WHERE id=$uid");
        $del_name = mysqli_fetch_assoc($r)['name'] ?? 'Unknown';
        mysqli_query($con, "DELETE FROM user WHERE id=$uid");
        log_action($_SESSION['aid'], 'Delete User', "Admin deleted account for $del_name (ID: $uid)", 'admin');
        $msg = "User <strong>$del_name</strong> deleted.";
        $msg_type = 'warning';
    }

    $users = mysqli_query($con, "SELECT * FROM user ORDER BY id ASC");
?>

<body>
<div id="wrapper">
    <?php require_once("nav.php"); require_once("sidebar.php"); ?>

    <div id="page-content-wrapper" class="mt-5 pt-4">
        <div class="container-fluid">
            <!-- Heading -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="fw-bold mb-0">Manage Users <small class="text-muted fw-light h5 ms-2">CRUD</small></h2>
                        <div class="d-flex align-items-center gap-3">
                            <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#createModal">
                                <i class="fas fa-user-plus me-2"></i>Add User
                            </button>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 glass-card px-3 py-2">
                                    <li class="breadcrumb-item"><a href="index.php" class="text-info text-decoration-none">Dashboard</a></li>
                                    <li class="breadcrumb-item active text-white" aria-current="page">Manage Users</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <hr class="opacity-10">
                </div>
            </div>

            <?php if ($msg): ?>
            <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show glass-card border-0 mb-4" role="alert">
                <?php echo $msg; ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Users Table -->
            <div class="table-responsive glass-card p-4 shadow-lg">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead class="bg-primary bg-opacity-10">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>NAME / USERNAME</th>
                            <th>EMAIL</th>
                            <th>CONTACT</th>
                            <th>ADDRESS</th>
                            <th class="text-center pe-4">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $cnt = 1; while($row = mysqli_fetch_assoc($users)): ?>
                        <tr>
                            <td class="ps-4 text-muted small"><?php echo $cnt++; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-20 text-primary fw-bold me-3 d-flex align-items-center justify-content-center" style="width:36px;height:36px;font-size:14px;">
                                        <?php echo strtoupper(substr($row['name'],0,1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-white"><?php echo $row['name']; ?></div>
                                        <div class="text-info small fw-mono">@<?php echo $row['username']; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted small"><?php echo $row['email']; ?></td>
                            <td class="text-muted small"><?php echo $row['phone']; ?></td>
                            <td class="text-muted small"><?php echo $row['address']; ?></td>
                            <td class="text-center pe-4">
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-sm btn-outline-info rounded-pill px-3"
                                        data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-uid="<?php echo $row['id']; ?>"
                                        data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                        data-username="<?php echo htmlspecialchars($row['username']); ?>"
                                        data-email="<?php echo htmlspecialchars($row['email']); ?>"
                                        data-phone="<?php echo htmlspecialchars($row['phone']); ?>"
                                        data-address="<?php echo htmlspecialchars($row['address']); ?>">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </button>
                                    <!-- Delete -->
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        data-uid="<?php echo $row['id']; ?>"
                                        data-name="<?php echo htmlspecialchars($row['name']); ?>">
                                        <i class="fas fa-trash me-1"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- CREATE Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-plus me-2 text-primary"></i>Create New User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="post" class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" name="name" placeholder="Full Name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                            <input type="text" class="form-control" name="username" placeholder="Username" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="tel" class="form-control" name="phone" placeholder="Contact No." required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            <input type="text" class="form-control" name="address" placeholder="Address" required>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" name="create_user" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">
                            <i class="fas fa-plus-circle me-2"></i>Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- EDIT Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-edit me-2 text-info"></i>Edit User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="post" class="row g-3">
                    <input type="hidden" name="edit_uid" id="edit_uid">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" name="edit_name" id="edit_name" placeholder="Full Name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                            <input type="text" class="form-control" name="edit_username" id="edit_username" placeholder="Username" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" name="edit_email" id="edit_email" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="tel" class="form-control" name="edit_phone" id="edit_phone" placeholder="Contact No.">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control" name="edit_password" placeholder="New Password (optional)">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            <input type="text" class="form-control" name="edit_address" id="edit_address" placeholder="Address">
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" name="update_user" class="btn btn-info w-100 rounded-pill py-2 fw-bold">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- DELETE Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content glass-card border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Delete User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-3">
                <p class="mb-1">Are you sure you want to delete</p>
                <p class="fw-bold text-danger" id="delete_name_display"></p>
                <p class="text-muted small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 pt-0 d-flex gap-2">
                <form method="post" class="w-100 d-flex gap-2">
                    <input type="hidden" name="del_uid" id="del_uid">
                    <button type="button" class="btn btn-outline-light w-50 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="delete_user" class="btn btn-danger w-50 rounded-pill">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/jquery-1.11.0.js"></script>
<script>
    // Populate Edit Modal
    document.getElementById('editModal').addEventListener('show.bs.modal', function(e) {
        var btn = e.relatedTarget;
        document.getElementById('edit_uid').value     = btn.dataset.uid;
        document.getElementById('edit_name').value    = btn.dataset.name;
        document.getElementById('edit_username').value = btn.dataset.username;
        document.getElementById('edit_email').value   = btn.dataset.email;
        document.getElementById('edit_phone').value   = btn.dataset.phone;
        document.getElementById('edit_address').value = btn.dataset.address;
    });
    // Populate Delete Modal
    document.getElementById('deleteModal').addEventListener('show.bs.modal', function(e) {
        var btn = e.relatedTarget;
        document.getElementById('del_uid').value = btn.dataset.uid;
        document.getElementById('delete_name_display').textContent = btn.dataset.name;
    });
</script>
<?php require_once("footer.php"); ?>
</body>
</html>
