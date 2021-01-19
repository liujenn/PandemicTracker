<?php require_once('../../private/initialize.php'); ?>

<?php
// Redirects to Login.html if not logged in
  if($_SESSION['Active'] == false){
    header("location:../../index.php");
	  exit;
  }
  if(isset($_POST['logout'])) {
    logout();
  }
  $sql_query_doc = "select Rid as ID from Link_Personal_Account
                      where username='".$_SESSION["uname"]."'";
  $result_doc = mysqli_query($conn,$sql_query_doc);
  $row_doc = mysqli_fetch_assoc($result_doc);
  $Rid = $row_doc['ID'];

  $sql_query_doc = "select Email as email, HealthCare_num as hnum, FirstName as fname, LastName as lname, Phone_num as pnum, DOB as dob from Resident
                      where Rid='".$Rid."'";
  $result_doc = mysqli_query($conn,$sql_query_doc);
  $row_doc = mysqli_fetch_assoc($result_doc);
  $email = $row_doc['email'];
  $hnum = $row_doc["hnum"];
  $fname = $row_doc["fname"];
  $lname = $row_doc["lname"];
  $pnum = $row_doc["pnum"];
  $dob = $row_doc["dob"];


  $sql_query_doc = "select Street as street, PostalCode as postcode from Resident
                      where Rid='".$Rid."'";
  $result_doc = mysqli_query($conn,$sql_query_doc);
  $row_doc = mysqli_fetch_assoc($result_doc);
  $street = $row_doc['street'];
  $postcode = $row_doc['postcode'];

  $sql_query_doc = "select City as city from Main_Address
                      where Street='".$street."' and PostalCode ='".$postcode."'";
  $result_doc = mysqli_query($conn,$sql_query_doc);
  $row_doc = mysqli_fetch_assoc($result_doc);
  $city = $row_doc['city'];
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
        <h1> Personal Account</h1>
        <h1> <?php echo $fname ?> <?php echo $lname ?> </h1>
        <h3>ID: <?php echo $Rid ?></h3>
        <h3>Phone Number <?php echo $pnum ?><h3>
        <h3>Email: <?php echo $email ?></h3>
        <h3>Healthcare Number: <?php echo $hnum ?> <h3>
        <h3>Date of Birth: <?php echo $dob ?> <h3>
        <h3>Main Address: <?php echo $street ?>, <?php echo $city ?>,  <?php echo $postcode ?> <h3>
        
        <div>
          
            
            <a href="createRecord.php" class="btn">Create New Record</a>
            <a href="viewRecord.php" class="btn">View My Records</a>
            <a href="healthPass.php" class="btn">Health Pass</a>

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