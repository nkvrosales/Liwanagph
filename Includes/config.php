<?php
    $host='localhost'; # MySQL Host
    $mysql_user="root";# MySql Username
    $mysql_pwd=""; # MySql Password
    $dbms="ebillsystem"; # Database

    $con = mysqli_connect($host,$mysql_user,$mysql_pwd,$dbms);
    if (!$con) die('Could not connect: ' . mysql_error());
    mysqli_select_db($con,$dbms) or die("cannot select DB" . mysql_error());

    function log_action($user_id, $action, $description, $role = 'user') {
        global $con;
        $user_id = (int)$user_id;
        $action = mysqli_real_escape_string($con, $action);
        $description = mysqli_real_escape_string($con, $description);
        $role = mysqli_real_escape_string($con, $role);
        $query = "INSERT INTO audit_logs (user_id, role, action, description) VALUES ('$user_id', '$role', '$action', '$description')";
        mysqli_query($con, $query);
    }
?>