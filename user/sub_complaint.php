    <?php 
    include("../Includes/session.php");
    include("../Includes/config.php");
    
    $id=$_SESSION['uid'];
    $comp=$_POST["complaint"];
    $aid    = rand(1,2);
    $stat   ="NOT PROCESSED"; 
    
    if(isset($_POST["complaint"]) && !empty($_POST["complaint"]))
    {
        $query  = "INSERT INTO complaint(uid,aid,complaint,status)";
        $query .= " VALUES ({$id},{$aid},'{$comp}','NOT PROCESSED')";
        mysqli_query($con,$query);  
        log_action($id, "File Complaint", "Submitted a new complaint: $comp");
    }
    
    header("Location:complaint.php");   
    ?>