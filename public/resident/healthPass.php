<?php require_once('../../private/initialize.php'); ?>

<?php
  if($_SESSION['Active'] == false){
    header("location:../../index.php");
	  exit;
  }
  if(isset($_POST['logout'])) {
    logout();
  }

  $Rid = get_rid($_SESSION["uname"]);

  // information needed to present
  $sql_query = "select R.Rid as id, R.FirstName as fname, R.LastName as lname, R.DOB as dob
                from Resident R";
                // where Rid ='".$Rid."'";
  $result = mysqli_query($conn,$sql_query);
  $fname = '';
  $lname = '';
  $dob = '';
  while ($row = mysqli_fetch_assoc($result)){
    if ($row['id'] === $Rid){
      $fname = $row['fname'];
      $lname = $row["lname"];
      $dob = $row["dob"];
    }
  }
  // $row = mysqli_fetch_assoc($result);
  // $fname = $row['fname'];
  // $lname = $row["lname"];
  // $dob = $row["dob"];
  date_default_timezone_set("America/Vancouver");
  $today = date("Y-m-d");

  $yesterday = date("Y-m-d", strtotime("-1 day"));

  // get the lastest recordDate and endDate from all Resident_Quarantine_Status records
  $sql_query = "select StartDate as recordDate, EndDate as quarantineEnd, Status as quarantineStatus
                from Resident_Quarantine_Status
                where Rid ='".$Rid."'";
  $result = mysqli_query($conn,$sql_query);
  $mostRecentRecord= 0;
  $latestEndDate = 0;
  $quarantineStatus = 0;
  while($row = mysqli_fetch_assoc($result)){
    $recordDate = $row['recordDate'];
    if ($recordDate > $mostRecentRecord){
      $mostRecentRecord = $recordDate;
    }
    $quarantineEnd = $row['quarantineEnd'];
    if ($quarantineEnd > $latestEndDate){
      $latestEndDate = $quarantineEnd;
      $qurantineStatus = $row['quarantineStatus'];
    }
  }

  // check date : if not = $yesterday or today => expired
  // if not expired: check Quarantine Status and Risk_Level for newest record
  if ($mostRecentRecord == $yesterday || $mostRecentRecord == $today){
    // endDate records >= current Date
    if ($latestEndDate >= $today && $qurantineStatus == 1){
      $status = "Stay at Home";
    }else{
      $status = "Healthy";
    }
  } else {
    $status = "Expired";
  }

  // check if in this resident's record, it has all possible risk level value (Low, Medium, High)
  $sql = "select distinct i1.Rid as id
  from Input_Risk_Status i1
  where not exists (
    select distinct r1.RiskLevel
      from Risk_Level r1
      where not exists (
      select r2.RiskLevel
          from Risk_Level r2, Input_Risk_Status i
          where i.CloseContactHasFlu_likeSymptom = r2.CloseContactHasFlu_likeSymptom AND
          i.ConfirmedCaseInNeighbourhood = r2.ConfirmedCaseInNeighbourhood AND
          i.ExposureToConfirmedCase = r2.ExposureToConfirmedCase AND  i.Rid = i1.Rid
          AND r1.RiskLevel = r2.RiskLevel
      )
  );";
  $result = mysqli_query($conn,$sql);
  $warning = "";
  while($row = mysqli_fetch_assoc($result)){
    $check_id = $row['id'];
    if ($check_id === $Rid){
         $warning = "Please keep yourself at low risk. Stay Safe.";
    }
}
?>

<!DOCTYPE html>
    <head>
            <title>Pandemic Tracker</title>

            <style>
              .btn{
                text-decoration: none;
                border: 1px solid black;
                padding: 5px 10px;
                border-radius: 5px;
              }
              .logout{
                position: absolute;
                top: 25px;
                right: 25px;
              }
              .status{
                position: absolute;
                bottom:25px;
                left:25px;
              }
            </style>
    </head>
    
    <body>
        <h1> <?php echo "Health Pass For: " . $fname . " " . $lname ?> </h1>
        <h3>Date: <?php echo $today ?><h3>
        <h3>Date of Birth: <?php echo $dob ?> <h3>
        <h2>Status: <?php echo $status?><h2>
        <h3 <?php if ($warning === '') echo "hidden = \"TRUE\"";?>>Warning: <?php echo $warning?><h3>
        
        <div>
          
            
            <a href="index.php" class="btn">Back</a>

            <form method="post" class="logout"> 
              <input type="submit" name="logout" value="Log Out"/> 
            </form> 

            
              <div class="status">
            <p>Status: <span style="color: green">Logged In<span></p>
              </div>
        </div>
    </body>

</html>

<?php include(PRIVATE_P . '/footer.php'); ?>