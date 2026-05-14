<?php 
    require_once('../Includes/config.php'); 
    require_once('../Includes/session.php');
    require_once('../Includes/admin.php');

    $aid = $_SESSION['aid'];
    //set dafaulted variables

    $query  = "SELECT curdate() AS bdate , adddate( curdate(),INTERVAL 30 DAY ) AS ddate , user.id AS uid , user.name AS uname FROM user ";
    $result = mysqli_query($con,$query);
    $row = mysqli_fetch_assoc($result);
    
    $bdate = $row['bdate'];
    $ddate = $row['ddate'];


    // if (isset($_POST['bdate'])) {
    //     $bdate = $_POST['bdate'];
    // }
    // if (isset($_POST['ddate'])) {
    //     $ddate = $_POST['ddate'];    }
    if (isset($_POST['uid'])) {
        $uid = $_POST['uid'];
    }if (isset($_POST['units'])) {
        $units = $_POST['units']; 
    }if (isset($_POST['uname'])) {
        $uname = $_POST['uname']; 
    }if (isset($_POST['month'])) {
        $month = $_POST['month']; 
    }
    
    if (isset($_POST['generate_bill'])) {
        if(isset($_POST["units"]) && !empty($_POST["units"]))
        {
// CONVERTING UNITS TO AMOUNT
            $query1 = "call unitstoamount({$units} , @x)";
            $result1 = mysqli_query($con,$query1);  

// INSERTING VALUES INTO BILL
            $query  = " INSERT INTO bill (aid , uid , units , month, amount , status , bdate , ddate )";
            $query .= " VALUES ( {$aid} , {$uid} , {$units} , '{$month}', @x , 'PENDING' , '{$bdate}' , '{$ddate}' )";
            $result2 = mysqli_query($con,$query);  
            if (!$result2)
            {
                die('Error in bill insertion: ' . mysqli_error($con));
            }

// INSERTING VALUES INTO TRANSACTION            

            $query2 = "SELECT id , amount FROM bill WHERE aid={$aid} AND uid={$uid} AND units={$units} ";
            $query2 .= "AND status='PENDING'  AND bdate='{$bdate}' AND ddate='{$ddate}' ";

            $result3 = mysqli_query($con,$query2);
            if (!$result3)
            {
                die('Error in bill retrieval: ' . mysqli_error($con));
            } 

            $row = mysqli_fetch_row($result3);

            $bid = $row[0];$amount=$row[1];
            insert_into_transaction($bid,$amount);
            log_action($aid, "Generate Bill", "Generated bill for $uname (Units: $units, Amount: ₱$amount)", "admin");
            
        }  
    }
    header("Location:bill.php");
?>