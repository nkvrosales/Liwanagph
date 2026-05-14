<?php 
    require_once('../Includes/config.php'); 
    require_once('../Includes/session.php');
    require_once('../Includes/admin.php');

    if ($logged==false) {
         header("Location:../index.php");
         exit;
    }

    if (isset($_POST['mark_paid']) && isset($_POST['bid'])) {
        $bid = (int)$_POST['bid'];
        $aid = $_SESSION['aid'];

        // Get bill details for logging
        $bill_query = "SELECT bill.*, user.name as uname FROM bill JOIN user ON bill.uid = user.id WHERE bill.id = $bid";
        $bill_result = mysqli_query($con, $bill_query);
        $bill_data = mysqli_fetch_assoc($bill_result);

        if ($bill_data) {
            $uname = $bill_data['uname'];
            $amount = $bill_data['amount'];
            $month = $bill_data['month'] ?: 'the period';

            // Update Bill
            $q1 = "UPDATE bill SET status = 'PROCESSED' WHERE id = $bid";
            mysqli_query($con, $q1);

            // Update Transaction
            $q2 = "UPDATE transaction SET status = 'PROCESSED', pdate = CURDATE() WHERE bid = $bid";
            mysqli_query($con, $q2);

            log_action($aid, "Process Payment", "Marked bill #$bid for $uname ($month) as PAID. Amount: ₱$amount", "admin");
            
            $_SESSION['pay_success'] = "Bill #$bid for $uname has been marked as paid.";
        }
    }

    header("Location:bill.php");
    exit;
?>
