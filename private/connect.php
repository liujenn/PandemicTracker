<?php
function OpenCon(){
    $dbhost = "pandemic-tracker-aws.clykyqmaokyo.ca-central-1.rds.amazonaws.com";
    $dbuser = "admin";
    $dbpass = "Cpsc304ubc";
    $db = "pandemictracker";

    // $dbhost = "localhost";
    // $dbuser = "root";
    // $dbpass = "root";
    // $db = "pandamic_tracker";

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass,$db); 
    confirm_connection();
    return $conn;
}
function CloseCon($conn){
    if(isset($conn)) {
        mysqli_close($conn);
    }
}
function confirm_connection() {
    if(mysqli_connect_errno()) {
        die("Connection failed: " . mysqli_connect_error());
    }
  }
?>