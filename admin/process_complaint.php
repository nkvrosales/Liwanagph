<?php 
    require_once('../Includes/config.php'); 

    $cid=$_POST['cid'];
    echo "$cid";
    if (isset($_POST["complaint_process"])) {
        if(isset($_POST["cid"])) {
            $query = "UPDATE complaint SET status='PROCESSED' WHERE id={$cid}";
            // echo $query;
            $result = mysqli_query($con,$query);
            if($result === FALSE) {
                die(mysql_error()); // TODO: better error handling
            }
            log_action($_SESSION['aid'], "Resolve Complaint", "Marked complaint #$cid as PROCESSED", "admin");
        }
    }
    header("Location:complaint.php");
 ?>