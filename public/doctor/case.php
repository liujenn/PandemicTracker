<?php require_once('../../private/initialize.php'); ?>
<?php
if ($_SESSION['Active'] == false) {
    header("location:../../index.php");
    exit;
}
if(!isset($_GET['casenum'])) {
    header("location: viewcases.php");
  }
$casenum = $_GET['casenum'] ?? 'null';
$case_detail = get_case_detail($casenum);
$case_med = get_case_medication($casenum);
$med_name = $case_med['Name'] === 'NULL' ? "" : $case_med['Name'];
$med_dosage = $case_med['Dosage'] === 'NULL' ? "" : $case_med['Dosage'];
?>
<!DOCTYPE html>
<html>

<head>
    <style>
        dl {
            width: 60%;
            overflow: hidden;
            padding: 0;
            margin-top: 1em;
            margin-bottom: 1em;
        }

        dt {
            font-weight: bold;
            float: left;
            width: 40%;
            padding: 0;
            margin: 0;
        }

        dd {
            float: left;
            width: 60%;
            padding: 0;
            margin: 0;
        }
    </style>
</head>

<body>
    
    <h2><?php echo $case_detail['LastName'].", ".$case_detail['FirstName']; ?></h2>
    <a href="viewcases.php">Back</a>
    <br>
    <dl>
        <dt>DOB:</dt>
        <dd><?php echo $case_detail['DOB']; ?></dd>
    </dl>
    <dl>
        <dt>Case #:</dt>
        <dd><?php echo $case_detail['CaseNum']; ?></dd>
    </dl>
    <dl>
        <dt>Encounter Date:</dt>
        <dd><?php echo $case_detail['EncounterDate']; ?></dd>
    </dl>
    <dl>
        <dt>Recovery Status:</dt>
        <dd><?php echo $case_detail['RecoveryStatus']; ?></dd>
    </dl>
    <dl>
        <dt>Medication:</dt>
        <dd><?php echo $med_name." ".$med_dosage; ?></dd>
    </dl>
    <dl>
        <dt>Notes:</dt>
        <dd><?php echo $case_detail['Notes']; ?></dd>
    </dl>
    <a href=<?php echo "editcase.php?casenum=".$casenum; ?>>Edit</a>
</body>

</html>
<?php include(PRIVATE_P . '/footer.php'); ?>