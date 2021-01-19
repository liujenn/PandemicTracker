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
$sql_query_doc = "select Did as ID from Link_Doctor_Account
                    where username='".$_SESSION["uname"]."'";
$result_doc = mysqli_query($conn,$sql_query_doc);
$row_doc = mysqli_fetch_assoc($result_doc);
$Did = $row_doc['ID'];

$sql_query_doc = "select Name as name, Specialization as spec from Doctor
                    where Did='".$Did."'";
$result_doc = mysqli_query($conn,$sql_query_doc);
$row_doc = mysqli_fetch_assoc($result_doc);
$name = $row_doc['name'];
$spec = $row_doc["spec"];

$sql_query_doc = "select Name as name, City as city from Works_At
                    where Did='".$Did."'";
$result_doc = mysqli_query($conn,$sql_query_doc);
$row_doc = mysqli_fetch_assoc($result_doc);
$hospital = $row_doc['name'];
$city = $row_doc['city'];

$sql_query_doc = "select StreetAddress as address from Medical_Center
                    where name='".$hospital."' and City='".$city."'";
$result_doc = mysqli_query($conn,$sql_query_doc);
$row_doc = mysqli_fetch_assoc($result_doc);
$address = $row_doc['address'];


$sql_query_doc = "select count(*) as activeCases 
                    from Cases
                    where RecoveryStatus = 'Positive'
                    group by RecoveryStatus";
$result_doc = mysqli_query($conn,$sql_query_doc);
$row_doc = mysqli_fetch_assoc($result_doc);
$activeCases = $row_doc['activeCases'];
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
        <h1> Doctor Account</h1>
        <h3>ID: <?php echo $Did ?></h3>
        <h3>Name: <?php echo $name ?></h3>
        <h3>Specialization: <?php echo $spec ?></h3>
        <h5>Works at: <?php echo $hospital ?>, <?php echo $city ?> <h5>
        <h5>Work Address: <?php echo $address ?><h5>

        <h2> Number of Active Cases: <span style = "color: red"> <?php echo $activeCases ?> </span> </h2>

        <div>
          
            
            <a href="viewcases.php" class="btn">View Existing Cases</a>
            <a href="createcase.php" class="btn">Create New Case</a>

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