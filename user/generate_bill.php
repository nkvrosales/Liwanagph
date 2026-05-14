<?php 
    require_once('../Includes/config.php'); 
    require_once('../Includes/session.php');
    require_once('../Includes/user.php');

    if (!isset($_SESSION['logged']) || $_SESSION['account'] !== 'user') {
        header("Location:../index.php");
        exit;
    }

    $uid = $_SESSION['uid'];

    if (isset($_POST['generate_bill']) && isset($_POST['units']) && !empty($_POST['units'])) {
        $units     = (int)$_POST['units'];
        $client_uid = isset($_POST['client_uid']) ? (int)$_POST['client_uid'] : $uid;

        // Call stored procedure for amount
        mysqli_query($con, "call unitstoamount($units, @x)");
        $r = mysqli_query($con, "SELECT @x AS amount");
        $amount = mysqli_fetch_assoc($r)['amount'];

        $bdate = date('Y-m-d');
        $ddate = date('Y-m-d', strtotime('+30 days'));

        $q = "INSERT INTO bill (uid, units, amount, status, bdate, ddate) VALUES ($client_uid, $units, $amount, 'PENDING', '$bdate', '$ddate')";
        mysqli_query($con, $q);
        $bid = mysqli_insert_id($con);
        mysqli_query($con, "INSERT INTO transaction (bid, payable, pdate, status) VALUES ($bid, $amount, NULL, 'PENDING')");

        log_action($uid, "Generate Bill", "Computed bill for UID $client_uid — Units: $units, Amount: ₱$amount");
    }

    header("Location:compute_bill.php");
?>
