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

  // Get the resident's name:
  $sql_query = "select R.FirstName as fname, R.LastName as lname
                from Resident R
                where Rid ='".$Rid."'";
  $result = mysqli_query($conn,$sql_query);
  $row = mysqli_fetch_assoc($result);
  $fname = $row['fname'];
  $lname = $row["lname"];

  // get today's date
  date_default_timezone_set("America/Vancouver");
  $today = date("Y-m-d");

  // get the information about Date, Fever Status and Risk Level (as the entered record)
  // TODO: We assume that the quarantineStartDate = Date we entered the record
  $sql_query = "select H.Date as Date, F.Fever_Status as FeverStatus, L.RiskLevel as RiskLevel, Q.status as quarantineStatus
                from Input_Health_Status H, Input_Risk_Status R, Fever_Logic F, Risk_Level L, Resident_Quarantine_Status Q
                where H.Date = R.Date and H.Rid = R.Rid and H.Rid = '" . $Rid . "'
                    and H.Temperature = F.Temperature 
                    and R.ExposureToConfirmedCase = L.ExposureToConfirmedCase 
                    and R.ConfirmedCaseInNeighbourhood = L.ConfirmedCaseInNeighbourhood 
                    and R.CloseContactHasFlu_likeSymptom = L.CloseContactHasFlu_likeSymptom
                    and Q.Rid = H.Rid and Q.StartDate = H.Date
                order by H.Date desc";
  $result_FS_RL = mysqli_query($conn,$sql_query);

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
            <style>
              table {
                border-collapse: collapse;
                width: 100%;
              }

              td,
              th {
                border: 1px solid #000000;
                text-align: left;
                padding: 8px;
              }

              tr:nth-child(even) {
                text-align: left;
                background-color: #dddddd;
              }
            </style>
    </head>
    
    <body>
        <h1> <?php echo $fname . " " . $lname ?> </h1>
        <table>
            <tr>
                <th>Date</th>
                <th>Fever Status</th>
                <th>Risk Level</th>
                <th>Quarantine Status</th>
            </tr>

            <?php while ($result = mysqli_fetch_assoc($result_FS_RL)) { ?>
                <tr>
                    <td><?php echo $result['Date']; ?></td>
                    <td><?php echo $result['FeverStatus']; ?></td>
                    <td><?php echo $result['RiskLevel']; ?></td>
                    <td>
                      <?php 
                        if ($result['quarantineStatus'] == 0) {
                          echo "Not In Quarantine";
                        }else{
                          echo "In Quarantine";
                        } 
                      ?></td>
                </tr>
            <?php } ?>
          </table>
          <br>
        
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